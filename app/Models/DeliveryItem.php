<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'product_id',
        'unit_id',
        'quantity',
        'unit_price',
        'real_unit_price',
        'subtotal',
        'note',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
