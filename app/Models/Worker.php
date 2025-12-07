<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Worker extends Model {
use HasFactory;
protected $fillable = ['name','monthly_salary','contact','created_by'];


public function transactions(){ return $this->hasMany(WorkerTransaction::class); }
}