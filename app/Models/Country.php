<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'flag',
        'code',
        'dial_code',
        'is_active'
    ];
    public $timestamps = false;

    public $appends = [
        'flag_url',
        'local_flag_url',
    ];

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => (bool) $value, 
            set: fn (bool $value) => $value ? true : false 
        );
    }

    public function getFlagUrlAttribute(): string
    {
        $code = strtolower($this->code);
      return "https://flagcdn.com/w40/{$code}.png";
    }
    public function getLocalFlagUrlAttribute()
    {
        $code = strtoupper($this->code);
      return route('country.flag.code', ['code' => $code]);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }
}
