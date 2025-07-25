<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['hex', 'code', 'name', 'description'];

    public $timestamps = false;

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
