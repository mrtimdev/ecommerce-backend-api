<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'slug', 'image_path' ,'is_active'];
    public $timestamps = false;


    protected $appends = [];
}
