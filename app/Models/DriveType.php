<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriveType extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'is_active'];
    public $timestamps = false;


    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => (bool) $value, 
            set: fn (bool $value) => $value ? 1 : 0 
        );
    }
}
