<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'button_label', 'button_url', 'description'];
    public $timestamps = false;
    public function communityItems()
    {
        return $this->hasMany(CommunityItem::class);
    }
}
