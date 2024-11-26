<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'country_id',
        'description',
        'youtube_link',
        'facebook_link',
        'image_path',
        'is_active',
        'view',
        'like',
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
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    
}
