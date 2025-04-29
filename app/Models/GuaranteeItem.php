<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuaranteeItem extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'port_check', 'image_path', 'guarantee_id'];
    public $timestamps = false;

    protected $appends = ['image_full_path'];

    
    public function getImageFullPathAttribute()
    {
        $image_path = $this->image_path;
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function guarantee()
    {
        return $this->belongsTo(Guarantee::class);
    }

    public function items()
    {
        return $this->hasMany(GuaranteeItemList::class);
    }
}
