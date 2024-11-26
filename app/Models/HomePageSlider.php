<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSlider extends Model
{
    use HasFactory;

    protected $table = 'home_page_sliders';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'is_active'
    ];

    protected $appends = ['status', 'image_full_path'];

    
    public function getImageFullPathAttribute()
    {
        $image_path = $this->attributes['image_path'];
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }

}
