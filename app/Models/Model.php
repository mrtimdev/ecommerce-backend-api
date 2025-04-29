<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'brand_id', 'is_active'];
    public $timestamps = false;


    protected $appends = ['status'];

    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'model_options', 'model_id', 'option_id');
    }
}
