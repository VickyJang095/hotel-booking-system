<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'hotel_id', 'user_id', 'check_in', 'check_out',
        'rooms', 'adults', 'children', 'total_price', 'status'
    ];

    public function hotel() { return $this->belongsTo(Hotel::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
