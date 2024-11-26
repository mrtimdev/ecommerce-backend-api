<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['shipping', 'login_logo', 'site_logo', 'site_name', 'site_link'];
    public $timestamps = false;
}
