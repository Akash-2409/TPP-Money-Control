<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'date',
        'quantity',
        'rate',
        'amount',
        'supplier',
        'bill_no',
        'note'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
