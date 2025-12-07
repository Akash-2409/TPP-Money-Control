<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DailyProduction extends Model {
use HasFactory;
protected $fillable = ['product_id','production_qty','date','user_id','note'];


public function product(){ return $this->belongsTo(Product::class); }
public function user(){ return $this->belongsTo(User::class); }
}