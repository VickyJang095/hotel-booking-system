<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

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
}
