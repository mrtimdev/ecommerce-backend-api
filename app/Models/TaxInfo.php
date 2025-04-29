<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxInfo extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'title'];

    public function items()
    {
        return $this->hasMany(TaxInfoItem::class);
    }
}
