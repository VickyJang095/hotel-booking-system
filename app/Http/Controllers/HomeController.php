<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hotels = [
            [
                'name' => 'Phenikaa Luxury Hotel',
                'city' => 'Hà Nội',
                'price' => 900000,
                'rating' => 5,
                'image' => 'https://picsum.photos/400/300?1'
            ],
            [
                'name' => 'Ocean View Resort',
                'city' => 'Đà Nẵng',
                'price' => 750000,
                'rating' => 4.5,
                'image' => 'https://picsum.photos/400/300?2'
            ],
            [
                'name' => 'Saigon Central Hotel',
                'city' => 'TP.HCM',
                'price' => 680000,
                'rating' => 4,
                'image' => 'https://picsum.photos/400/300?3'
            ],
        ];

        return view('home.index', compact('hotels'));
    }
}
