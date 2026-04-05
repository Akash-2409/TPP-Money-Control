<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'date', 'quantity', 'customer', 'party_id', 'note','village', 'vehicle_no', 'rate', 'total', 'labour_charge',
        'tax_type', 'gst_rate', 'taxable_amount', 'cgst_amount', 'sgst_amount', 'igst_amount'
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
