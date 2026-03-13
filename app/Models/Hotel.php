<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
        'image',
        'price_per_night',
        'total_rooms',
        'max_guests_per_room',
        'rating',
        'description'
    ];
    protected $casts = [
        'amenities'            => 'array',
        'payment_methods'      => 'array',
        'free_cancellation'    => 'boolean',
        'instant_booking'      => 'boolean',
        'pay_at_property'      => 'boolean',
        'pay_later'            => 'boolean',
        'wheelchair_accessible' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scope tìm phòng còn trống trong khoảng ngày
    public function scopeAvailable($query, $checkIn, $checkOut, $rooms)
    {
        return $query->where('total_rooms', '>=', function ($sub) use ($checkIn, $checkOut, $rooms) {
            $sub->selectRaw('COALESCE(SUM(b.rooms), 0) + ?', [$rooms])
                ->from('bookings as b')
                ->whereColumn('b.hotel_id', 'hotels.id')
                ->where('b.status', '!=', 'cancelled')
                ->where('b.check_in', '<', $checkOut)
                ->where('b.check_out', '>', $checkIn);
        });
    }
    public function getImageUrlAttribute(): string
    {
        $fallback = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80';

        if (!$this->image) return $fallback;

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
