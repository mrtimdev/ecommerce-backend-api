<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{




    public function items()
    {
        return $this->hasMany(PackageItem::class);
    }
}
