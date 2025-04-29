<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'no'];
    public $timestamps = false;


    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return (int)$this->no;
    }
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
   
}
