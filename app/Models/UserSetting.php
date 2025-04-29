<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSetting extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'company',
        'address',
        'avatar',
        'cover',
        'theme',
        'privacy',
    ];
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
