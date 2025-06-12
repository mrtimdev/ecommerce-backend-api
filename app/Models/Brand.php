<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Brand extends EloquentModel
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'slug', 'image_path' ,'is_active'];
    public $timestamps = false;


    protected $appends = ['status', 'image_full_path'];


    public function getImageFullPathAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('assets/images/no-image.jpg');
    }
    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => (bool) $value,
            set: fn (bool $value) => $value ? 1 : 0
        );
    }
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    public function models(): HasMany
    {
        return $this->hasMany(Model::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
