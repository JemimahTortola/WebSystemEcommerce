<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name', 'store_email', 'store_phone', 'store_address', 'logo',
        'hero_image', 'hero_title', 'hero_subtitle', 'store_description',
        'notif_new_order', 'notif_low_stock', 'notif_out_of_stock', 'notif_new_review',
        'notif_new_customer', 'notif_weekly_report', 'notification_email'
    ];
}
