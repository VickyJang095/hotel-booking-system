<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}