<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Steering extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description'];
    public $timestamps = false;

    public function car()
    {
        return $this->belongsTo(car::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
