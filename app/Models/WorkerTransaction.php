<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WorkerTransaction extends Model {
use HasFactory;
protected $fillable = ['worker_id','type','amount','date','description','created_by'];


public function worker(){ return $this->belongsTo(Worker::class); }
}