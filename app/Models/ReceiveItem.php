<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceiveItem extends Model
{

    use HasFactory;

    protected $fillable = [
        'receive_id',
        'package_item_id',
        'quantity',
        'unit_price',
        'note',
    ];
    public function receive()
    {
        return $this->belongsTo(Receive::class);
    }

    public function packageItem()
    {
        return $this->belongsTo(PackageItem::class);
    }
}
