<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $table = "countries";
    protected $fillable = [
        'name',
        'flag',
        'code',
        'dial_code',
    ];
    public $timestamps = false;

    public $appends = [
        'flag_url',
        'local_flag_url'
    ];

    public function getFlagUrlAttribute(): string
    {
        $code = strtolower($this->flag_code);
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
