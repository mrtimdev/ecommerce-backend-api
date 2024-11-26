<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable implements LaratrustUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRolesAndPermissions;
    const TYPE_BACKEND = 'backend';
    const TYPE_FRONTEND = 'frontend';
    
    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'username', 'phone', 'type', 'terms',
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

    public function isBackend()
    {
        return $this->type === self::TYPE_BACKEND;
    }
    public function isFrontend()
    {
        return $this->type === self::TYPE_FRONTEND;
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function car()
    {
        return $this->hasOne(Car::class, 'created_by', 'id');
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            // Generate the username from full name
            $user->username = static::generateUsername($user->name);
        });
    }

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
