<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['name', 'unit', 'current_stock','material_type',];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
