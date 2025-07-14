<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'client_id', 'name', 'email', 'phone', 'address',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
