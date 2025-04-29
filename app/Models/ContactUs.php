<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'telegram',
        'telegram_channel',
        'facebook_page',
        'youtube',
        'tiktok',
        'contact_label',
        'address',
        'image_path'
    ];
    public $timestamps = false;
    protected $appends = ['image_full_path'];

    
    public function getImageFullPathAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('assets/images/no-image.jpg');
    }
}
