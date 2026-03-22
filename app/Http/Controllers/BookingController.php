<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;

class BookingController extends Controller
{
    public function showDetails($id, Request $request)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.hotels-payment', compact('hotel'));
    }

    public function showPayment($id, Request $request)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.hotels-payment-finishing', compact('hotel'));
    }
    public function store(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        $pm = $request->payment_method;
        if (in_array($pm, ['visa', 'mastercard', 'diners'])) {
            $pm = 'credit_card';
        }

        $nights = \Carbon\Carbon::parse($request->check_in)
            ->diffInDays(\Carbon\Carbon::parse($request->check_out));

        Booking::create([
            'hotel_id' => $hotel->id,
            'user_id' => Auth::id(),

            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,

            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nights' => $nights,

            'rooms' => $request->rooms,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,

            'price_per_night' => $hotel->price_per_night,
            'total_amount' => $request->total ?? 0,

            'payment_method' => $pm,
            'phone_confirmation' => $request->phone_confirmed ?? 0,
            'travel_insurance' => $request->travel_insurance ?? 0,

            'booking_code' => 'TRP-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('home')->with('success', 'Đặt phòng thành công!');
        Mail::to($booking->guest_email)->send(new BookingSuccessMail($booking));
    }
}
