<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_active'];
    public $timestamps = false;

    protected $appends = ['status'];

    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }
}
