<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'client_id', 'product_id', 'quantity', 'price',
        'discount', 'category', 'unit', 'payment_status', 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
