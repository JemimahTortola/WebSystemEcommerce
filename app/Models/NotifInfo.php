<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'notif_new_order', 'notif_low_stock', 'notif_out_of_stock', 
        'notif_new_review', 'notif_new_customer', 'notif_weekly_report', 'notification_email'
    ];
    protected $casts = [
        'notif_new_order' => 'boolean',
        'notif_low_stock' => 'boolean',
        'notif_out_of_stock' => 'boolean',
        'notif_new_review' => 'boolean',
        'notif_new_customer' => 'boolean',
        'notif_weekly_report' => 'boolean',
    ];
}
