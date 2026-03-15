<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    private function myHotel(): ?Hotel
    {
        /** @var User $user */
        $user = Auth::user();
        return Hotel::where('owner_id', $user->id)->first();
    }

    // ── DASHBOARD ────────────────────────────────────────────
    public function dashboard()
    {
        $hotel = $this->myHotel();
        $stats = [
            'total_rooms'    => $hotel?->total_rooms ?? 0,
            'total_bookings' => 0,
            'this_month'     => 0,
            'rating'         => $hotel?->rating ?? 0,
        ];
        return view('owner.dashboard', compact('hotel', 'stats'));
    }

    // ── HOTEL ────────────────────────────────────────────────
    public function editHotel()
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();
        return view('owner.hotel.edit', compact('hotel'));
    }

    public function updateHotel(Request $request)
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();

        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'address'              => 'nullable|string',
            'description'          => 'nullable|string',
            'price_per_night'      => 'required|numeric|min:0',
            'star_rating'          => 'required|integer|between:1,5',
            'total_rooms'          => 'required|integer|min:1',
            'max_guests_per_room'  => 'required|integer|min:1',
            'amenities'            => 'nullable|array',
            'free_cancellation'    => 'boolean',
            'instant_booking'      => 'boolean',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'image_url'            => 'nullable|url',
            'image_file'           => 'nullable|image|max:2048',
        ]);

        $data['amenities']         = $request->amenities ?? [];
        $data['free_cancellation'] = $request->boolean('free_cancellation');
        $data['instant_booking']   = $request->boolean('instant_booking');

        if ($request->hasFile('image_file') || $request->filled('image_url')) {
            $data['image'] = $this->handleImage($request);
        }

        $hotel->update($data);
        return back()->with('success', 'Hotel updated successfully.');
    }

    // ── BOOKINGS ─────────────────────────────────────────────
    public function bookings()
    {
        /** @var User $user */
        $user     = Auth::user();
        $hotel    = Hotel::where('owner_id', $user->id)->firstOrFail();
        $bookings = collect(); // placeholder
        return view('owner.bookings', compact('hotel', 'bookings'));
    }

    // ── ROOMS ────────────────────────────────────────────────
    public function rooms()
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();
        $rooms = collect(); // placeholder
        return view('owner.rooms.index', compact('hotel', 'rooms'));
    }

    public function createRoom()
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();
        return view('owner.rooms.form', ['hotel' => $hotel, 'room' => null]);
    }

    public function storeRoom(Request $request)
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();
        // $hotel->rooms()->create($request->validated());
        return redirect()->route('owner.rooms')->with('success', 'Room created.');
    }

    public function editRoom($id)
    {
        /** @var User $user */
        $user  = Auth::user();
        $hotel = Hotel::where('owner_id', $user->id)->firstOrFail();
        // $room = $hotel->rooms()->findOrFail($id);
        return view('owner.rooms.form', ['hotel' => $hotel, 'room' => null]);
    }

    public function updateRoom(Request $request, $id)
    {
        return redirect()->route('owner.rooms')->with('success', 'Room updated.');
    }

    public function deleteRoom($id)
    {
        return back()->with('success', 'Room deleted.');
    }

    // ── HELPERS ──────────────────────────────────────────────
    private function handleImage(Request $request): ?string
    {
        if ($request->hasFile('image_file')) {
            return $request->file('image_file')->store('hotels', 'public');
        }
        if ($request->filled('image_url')) {
            return $request->image_url;
        }
        return null;
    }
}
