<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuaranteeItem extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'port_check' , 'guarantee_id'];
    public $timestamps = false;
    public function guarantee()
    {
        return $this->belongsTo(Guarantee::class);
    }
}
