<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;
    protected $fillable = ['car_id', 'image_path'];
    public $timestamps = false;

    protected $appends = ['image_full_path'];

    
    // public function getImageFullPathAttribute()
    // {
    //     return asset('storage/' . $this->image_path);
    // }
    public function getImageFullPathAttribute()
    {
        $image_path = $this->attributes['image_path'];
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
