<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGallery extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'image_path'];
    public $timestamps = false;

    protected $appends = ['image_full_path'];

    public function getImageFullPathAttribute()
    {
        $image_path = $this->attributes['image_path'];
        return $image_path ? asset('storage/' . $image_path) : null;
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
