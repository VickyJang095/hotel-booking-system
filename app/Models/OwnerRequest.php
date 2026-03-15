<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerRequest extends Model
{
    protected $fillable = [
        'user_id',
        'hotel_name',
        'phone',
        'city',
        'address',
        'description',
        'status',
        'reject_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helpers ──────────────────────────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
