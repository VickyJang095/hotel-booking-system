<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Hotel extends Model
{
    protected $fillable = [
        'owner_id',
        'status',
        'reject_reason',
        'name',
        'city',
        'address',
        'description',
        'price_per_night',
        'star_rating',
        'rating',
        'review_count',
        'total_rooms',
        'max_guests_per_room',
        'type',
        'distance_from_centre',
        'free_cancellation',
        'instant_booking',
        'pay_at_property',
        'pay_later',
        'wheelchair_accessible',
        'amenities',
        'payment_methods',
        'latitude',
        'longitude',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'amenities'             => 'array',
            'payment_methods'       => 'array',
            'free_cancellation'     => 'boolean',
            'instant_booking'       => 'boolean',
            'pay_at_property'       => 'boolean',
            'pay_later'             => 'boolean',
            'wheelchair_accessible' => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────────
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ── Scopes ───────────────────────────────────────────────
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePendingReview(Builder $query): Builder
    {
        return $query->where('status', 'pending_review');
    }

    // ── Image accessor ───────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        // Fallback Unsplash images theo tên thành phố
        $fallbacks = [
            'hanoi'          => 'https://images.unsplash.com/photo-1528360983277-13d401cdc186?w=800&q=80',
            'ho chi minh'    => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=800&q=80',
            'da nang'        => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=800&q=80',
            'hoi an'         => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=800&q=80',
            'phu quoc'       => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&q=80',
            'nha trang'      => 'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&q=80',
            'sapa'           => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=80',
            'hue'            => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&q=80',
            'ha long'        => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
            'da lat'         => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
        ];

        $default = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80';

        if (!$this->image) {
            $city = strtolower($this->city ?? '');
            foreach ($fallbacks as $key => $url) {
                if (str_contains($city, $key)) return $url;
            }
            return $default;
        }

        // URL đầy đủ → dùng thẳng
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // Path storage (vd: hotels/abc.jpg)
        if (str_starts_with($this->image, 'hotels/')) {
            return asset('storage/' . $this->image);
        }

        // Path local (vd: images/hotels/grand-hanoi.jpg) → check file tồn tại
        $publicPath = public_path($this->image);
        if (file_exists($publicPath)) {
            return asset($this->image);
        }

        // File không tồn tại → fallback theo thành phố
        $city = strtolower($this->city ?? '');
        foreach ($fallbacks as $key => $url) {
            if (str_contains($city, $key)) return $url;
        }

        return $default;
    }

    // ── Status helpers ───────────────────────────────────────
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
