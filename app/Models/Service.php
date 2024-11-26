<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'image_path'];
    public $timestamps = false;
    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }

}
