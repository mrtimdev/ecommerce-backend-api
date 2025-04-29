<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarsLikeCount extends Model
{
    use HasFactory;
    protected $table = 'cars_like_count';
    protected $fillable = ['car_id', 'user_id', 'count'];
    public $timestamps = false;

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
