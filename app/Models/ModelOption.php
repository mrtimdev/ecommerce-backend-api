<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelOption extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'option_id'];
    public $timestamps = false;
}
