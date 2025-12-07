<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model {
use HasFactory;
protected $fillable = ['name','unit','main_product','category'];


public function dailyProductions(){ return $this->hasMany(DailyProduction::class); }
public function inventory(){ return $this->hasOne(Inventory::class); }
}