<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'brand_id', 'status'];
    public $timestamps = false;


    protected $appends = [];
}
