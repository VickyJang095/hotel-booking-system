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

    // ── Accessor ─────────────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
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
