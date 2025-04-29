<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityItem extends Model
{
    use HasFactory;


    protected $fillable = ['title', 'description', 'pdf_path', 'community_id', 'image_path'];
    public $timestamps = false;

    protected $appends = ['image_full_path', 'pdf_full_path'];

    
    public function getImageFullPathAttribute()
    {
        $image_path = $this->image_path;
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function getPdfFullPathAttribute()
    {
        $pdf_path = $this->pdf_path;
        return $pdf_path ? asset('storage/' . $pdf_path) : null;
    }
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
