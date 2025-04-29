<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Menu extends EloquentModel
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'image_path', 'category_id'];
    protected $appends = ['slug', 'image_full_path'];
    public $timestamps = false;

    public function getSlugAttribute(): string
    {
        return static::generateUniqueSlug($this->code, $this->name);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'menu_brand');
    }

    public function models()
    {
        return $this->belongsToMany(Model::class, 'menu_model');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fuel_types()
    {
        return $this->belongsToMany(FuelType::class, 'menu_fuel_type');
    }

    public function steerings()
    {
        return $this->belongsToMany(Steering::class, 'menu_steering');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'menu_location');
    }

    public function drive_types()
    {
        return $this->belongsToMany(DriveType::class, 'menu_drive_type');
    }
    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'menu_passenger');
    }

    public function getImageFullPathAttribute()
    {
        $image_path = $this->attributes['image_path'];
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }

    
    public static function generateUniqueSlug($code, $name)
    {
        $baseSlug = Str::slug("{$code} {$name}");
        $count = static::where('code', 'like', "$baseSlug%")->count();

        return $count ? "{$baseSlug}-{$count}" : $baseSlug;
    }
}
