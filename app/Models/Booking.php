<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'hotel_id',
        'check_in',
        'check_out',
        'nights',
        'rooms',
        'adults',
        'children',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_requests',
        'price_per_night',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
        'status',
        'cancel_reason',
        'confirmed_at',
        'cancelled_at',
        'travel_insurance',
        'phone_confirmation',
        'booking_code',
    ];

    protected function casts(): array
    {
        return [
            'check_in'       => 'date',
            'check_out'      => 'date',
            'paid_at'        => 'datetime',
            'confirmed_at'   => 'datetime',
            'cancelled_at'   => 'datetime',
            'travel_insurance'    => 'boolean',
            'phone_confirmation'  => 'boolean',
        ];
    }

    // Auto generate booking code
    protected static function booted(): void
    {
        static::creating(function ($booking) {
            if (!$booking->booking_code) {
                $booking->booking_code = 'TRP-' . strtoupper(Str::random(8));
            }
        });
    }

    // ── Relationships ─────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // ── Scopes ────────────────────────────────────────────
    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', 'pending');
    }
    public function scopeConfirmed(Builder $q): Builder
    {
        return $q->where('status', 'confirmed');
    }
    public function scopeCancelled(Builder $q): Builder
    {
        return $q->where('status', 'cancelled');
    }

    // ── Helpers ───────────────────────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }
    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => __('booking.status_pending'),
            'confirmed'  => __('booking.status_confirmed'),
            'checked_in' => __('booking.status_checked_in'),
            'checked_out' => __('booking.status_checked_out'),
            'cancelled'  => __('booking.status_cancelled'),
            default      => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'amber',
            'confirmed'  => 'blue',
            'checked_in' => 'green',
            'checked_out' => 'gray',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}
