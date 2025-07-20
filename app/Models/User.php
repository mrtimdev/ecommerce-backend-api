<?php

namespace App\Models;

use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\HasRolesAndPermissions;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\Frontend\CustomVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable implements LaratrustUser #, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRolesAndPermissions;

    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'username', 'phone', 'type', 'terms', 'telegram_link', 'facebook_link', 'whatapp_link', 'is_active', 'phone_verified_at', 'email_verified_at', 'password_verified_at', 'avatar', 'otp', 'otp_expired', 'is_new_email', 'new_email', 'is_created_by_owner', 'dob', 'gender', 'company', 'address', 'cover', 'email_2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['avatar_full_path', 'cover_full_path'];

    public function requiresEmailVerification()
    {
        return $this->type === 'client';
    }
    public function sendEmailVerificationNotification()
    {
        if ($this->type === 'client') {
            $this->notify(new CustomVerifyEmail());  // Send the custom notification
        }
    }


    public function getAvatarFullPathAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('assets/images/user/no-avatar.png');
    }
    public function getCoverFullPathAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : asset('assets/images/user/no-avatar.png');
    }

    public function isBackend()
    {
        return $this->type === 'backend';
    }

    public function isClient()
    {
        return $this->type === 'client';
    }

    public function isDriver()
    {
        return $this->type === 'driver';
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function car()
    {
        return $this->hasOne(Car::class, 'created_by', 'id');
    }
    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }

    public function getIsNewEmailAttribute(): bool
    {
        return $this->attributes['is_new_email'] ? true : false;
    }

    public function likedCars()
    {
        return $this->belongsToMany(Car::class, 'cars_like_count', 'user_id', 'car_id');
    }

    public function orders()
    {
        return $this->hasMany(CustomerOrder::class);
    }
    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($user) {
    //         $user->username = static::generateUsername($user->name);
    //     });
    // }

    protected static function generateUsername($name)
    {
        // Remove spaces and lowercase the full name
        $baseUsername = Str::of($name)->lower()->replace(' ', '');

        // Check if username is unique and append a number if necessary
        $username = $baseUsername;
        $counter = 1;

        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
