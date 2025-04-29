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
        'image_path_2',
        'link',
        'is_active'
    ];

    protected $appends = ['status', 'image_full_path', 'image_full_path_2'];

    
    public function getImageFullPathAttribute()
    {
        $image_path = $this->attributes['image_path'];
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function getImageFullPath2Attribute()
    {
        $image_path = $this->attributes['image_path_2'];
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
