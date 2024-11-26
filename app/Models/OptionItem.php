<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'option_id'];
    public $timestamps = false;
}
