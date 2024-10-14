<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Car extends Model
{
    use HasFactory;
    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    protected $fillable = [
        'code',
        'name',
        'slug',
        'plate_number',
        'current_price',
        'previous_price',
        'year',
        'mileage',
        'featured_image',
        'is_featured',
        'is_active',
        'category_id',
        'condition_id',
        'brand_id',
        'model_id',
        'fuel_type_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($car) {
            $car->slug = static::generateUniqueSlug($car->name);
        });

        static::updating(function ($car) {
            if ($car->isDirty('name')) {
                $car->slug = static::generateUniqueSlug($car->name);
            }
        });
    }

    // Generate a unique slug
    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "$slug%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
