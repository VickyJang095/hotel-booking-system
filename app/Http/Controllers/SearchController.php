<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'location'  => 'required|string|min:1',
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'rooms'     => 'required|integer|min:1',
            'adults'    => 'required|integer|min:1',
            'children'  => 'nullable|integer|min:0',
        ]);

        $location = $this->normalizeVi($request->location);
        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;
        $rooms    = (int) $request->rooms;
        $adults   = (int) $request->adults;
        $children = (int) ($request->children ?? 0);
        $guests   = $adults + $children;

        $query = Hotel::query();

        $query->where('status', 'approved');

        $query->where(function ($q) use ($location) {
            $q->whereRaw("LOWER(CONVERT(city    USING utf8mb4)) LIKE ?", ["%{$location}%"])
              ->orWhereRaw("LOWER(CONVERT(address USING utf8mb4)) LIKE ?", ["%{$location}%"])
              ->orWhereRaw("LOWER(CONVERT(name    USING utf8mb4)) LIKE ?", ["%{$location}%"]);
        });

        $query->where('max_guests_per_room', '>=', ceil($guests / $rooms))
              ->where('total_rooms', '>=', $rooms);

        if ($request->filled('price_min')) {
            $query->where('price_per_night', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max') && (float) $request->price_max < 1500) {
            $query->where('price_per_night', '<=', (float) $request->price_max);
        }

        if ($request->filled('stars')) {
            $query->whereIn('star_rating', (array) $request->stars);
        }

        if ($request->filled('review_score')) {
            $query->where('rating', '>=', (float) $request->review_score);
        }

        if ($request->filled('property_type')) {
            $query->whereIn('type', (array) $request->property_type);
        }

        if ($request->filled('distance_max') && (int) $request->distance_max < 10) {
            $query->where('distance_from_centre', '<=', (float) $request->distance_max);
        }

        if ($request->boolean('free_cancellation'))    $query->where('free_cancellation',    true);
        if ($request->boolean('instant_booking'))      $query->where('instant_booking',      true);
        if ($request->boolean('pay_at_property'))      $query->where('pay_at_property',      true);
        if ($request->boolean('pay_later'))            $query->where('pay_later',            true);
        if ($request->boolean('wheelchair_accessible'))$query->where('wheelchair_accessible', true);

        if ($request->filled('amenities')) {
            foreach ((array) $request->amenities as $amenity) {
                $query->whereRaw('JSON_CONTAINS(amenities, ?)', [json_encode($amenity)]);
            }
        }

        if ($request->filled('payment_methods')) {
            foreach ((array) $request->payment_methods as $method) {
                $query->whereRaw('JSON_CONTAINS(payment_methods, ?)', [json_encode($method)]);
            }
        }

        match ($request->input('sort', 'recommended')) {
            'price_asc'  => $query->orderBy('price_per_night'),
            'price_desc' => $query->orderByDesc('price_per_night'),
            'rating'     => $query->orderByDesc('rating'),
            default      => $query->orderByDesc('rating'),
        };

        $hotels = $query->paginate(12)->withQueryString();
        $nights = max(1, (int) Carbon::parse($checkIn)->diffInDays($checkOut));

        $mapHotels = $hotels->map(fn($h) => [
            'id'     => $h->id,
            'name'   => $h->name,
            'lat'    => $h->latitude,
            'lng'    => $h->longitude,
            'price'  => $h->price_per_night,
            'rating' => $h->rating,
            'image'  => $h->image_url,
            'city'   => $h->city,
            'url'    => route('hotels.show', $h->id),
        ]);

        return view('hotels.search-results', compact(
            'hotels',
            'location',
            'checkIn',
            'checkOut',
            'rooms',
            'adults',
            'children',
            'nights',
            'mapHotels'
        ));
    }

    private function normalizeVi(string $str): string
    {
        $str  = mb_strtolower(trim($str), 'UTF-8');
        $from = [
            'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ',
        ];
        $to   = [
            'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
        ];
        return str_replace($from, $to, $str);
    }
}