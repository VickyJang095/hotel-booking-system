<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'role',
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'city',
        'date_of_birth',
        'gender',
        'card_holder_name',
        'card_number_masked',
        'card_expiry',
        'card_type',
        'billing_address',
        'billing_city',
        'billing_postal_code',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'email_verified_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isHotelOwner(): bool
    {
        return $this->role === 'hotel_owner';
    }
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Lấy avatar URL
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=dbeafe&color=2563eb&bold=true";
    }

    // Lấy họ tên đầy đủ
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        }
        return $this->name ?? '';
    }
}
