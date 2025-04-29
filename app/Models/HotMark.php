<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotMark extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];
    public $timestamps = false;

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_hot_marks', 'hot_mark_id', 'car_id');
    }
}
