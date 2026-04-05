<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'billing_address',
        'shipping_address',
        'gst_number',
        'opening_balance'
    ];
}
