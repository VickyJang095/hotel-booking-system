<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ── Step 1: Trang điền thông tin ──
    public function showDetails($id, Request $request)
    {
        $hotel = Hotel::findOrFail($id);
        $user  = Auth::user();
        return view('hotels.hotels-payment', compact('hotel', 'user'));
    }

    // ── Step 2: Trang thanh toán ──
    public function showPayment($id, Request $request)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.hotels-payment-finishing', compact('hotel'));
    }

    // ── Store booking ──
    public function store(Request $request)
    {
        $user  = Auth::user();
        $hotel = Hotel::findOrFail($request->hotel_id);

        $pm = $request->payment_method;
        if (in_array($pm, ['visa', 'mastercard', 'diners'])) {
            $pm = 'credit_card';
        }

        $nights = Carbon::parse($request->check_in)
            ->diffInDays(Carbon::parse($request->check_out));

        $booking = Booking::create([
            'hotel_id'           => $hotel->id,
            'user_id'            => Auth::id(),

            'guest_name'         => $request->guest_name  ?? $user?->name,
            'guest_email'        => $request->guest_email ?? $user?->email,
            'guest_phone'        => $request->guest_phone ?? $user?->phone,

            'check_in'           => $request->check_in,
            'check_out'          => $request->check_out,
            'nights'             => max(1, $nights),

            'rooms'              => $request->rooms   ?? 1,
            'adults'             => $request->adults  ?? 1,
            'children'           => $request->children ?? 0,

            'price_per_night'    => $hotel->price_per_night,
            'total_amount'       => $request->total ?? 0,

            'payment_method'     => $pm,
            'phone_confirmation' => $request->phone_confirmed  ?? 0,
            'travel_insurance'   => $request->travel_insurance ?? 0,

            'special_requests'   => $request->special_requests,
            'status'             => 'confirmed',
            'booking_code'       => 'TRP-' . strtoupper(Str::random(8)),
        ]);

        // ── Gửi email xác nhận ──
        try {
            Mail::to($booking->guest_email)->send(new BookingSuccessMail($booking));
        } catch (\Exception $e) {
            // Không crash nếu mail lỗi
        }

        return redirect()
            ->route('profile.trips')
            ->with('success', 'Đặt phòng thành công! Mã đặt phòng: ' . $booking->booking_code);
    }

    // ── Lịch sử đặt phòng ──
    public function history(Request $request)
    {
        $user = Auth::user();

        $tab    = $request->get('tab', 'upcoming');
        $search = $request->get('search');

        $query = Booking::with('hotel')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at');

        // Filter theo tab
        $now = Carbon::now();
        match ($tab) {
            'upcoming'  => $query->where('check_out', '>=', $now)->whereIn('status', ['confirmed', 'pending']),
            'past'      => $query->where('check_out', '<',  $now),
            'cancelled' => $query->where('status', 'cancelled'),
            default     => null,
        };

        // Search theo booking code hoặc tên khách sạn
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('hotel', fn($h) => $h->where('name', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(8)->withQueryString();

        // Stats
        $stats = [
            'upcoming'  => Booking::where('user_id', $user->id)->where('check_out', '>=', $now)->whereIn('status', ['confirmed', 'pending'])->count(),
            'past'      => Booking::where('user_id', $user->id)->where('check_out', '<',  $now)->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
            'total_spent' => Booking::where('user_id', $user->id)->where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        return view('profile.trips', compact('bookings', 'tab', 'search', 'stats'));
    }

    // ── Chi tiết booking ──
    public function show($bookingCode)
    {
        $booking = Booking::with('hotel')
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('profile.booking-detail', compact('booking'));
    }

    // ── Huỷ booking ──
    public function cancel($bookingCode, Request $request)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Chỉ cho huỷ nếu check-in còn > 24h
        if (Carbon::parse($booking->check_in)->diffInHours(now()) < 24 && Carbon::parse($booking->check_in)->isFuture()) {
            return back()->with('error', 'Không thể huỷ phòng trong vòng 24h trước check-in.');
        }

        if (!in_array($booking->status, ['confirmed', 'pending'])) {
            return back()->with('error', 'Booking này không thể huỷ.');
        }

        $booking->update([
            'status'            => 'cancelled',
            'cancelled_at'      => now(),
            'cancel_reason'     => $request->reason,
        ]);

        return redirect()
            ->route('profile.trips')
            ->with('success', 'Đã huỷ đặt phòng ' . $booking->booking_code . ' thành công.');
    }
}
