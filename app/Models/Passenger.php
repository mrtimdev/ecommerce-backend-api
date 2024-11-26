<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = ['no'];
    public $timestamps = false;


    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return $this->no;
    }
   
}
