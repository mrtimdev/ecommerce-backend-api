<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuCarGalleryItem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'pdf_path', 'is_active', 'menu_car_gallery_id'];
    protected $appends = ['pdf_full_path'];


    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }

    public function menuCarGallery()
    {
        return $this->belongsTo(MenuCarGallery::class);
    }

    public function getPdfFullPathAttribute()
    {
        $pdf_path = $this->pdf_path;
        return $pdf_path ? asset('storage/' . $pdf_path) : null;
    }
}
