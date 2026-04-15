<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'type',
        'expires_at',
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];
}
