<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'product_id',
        'stock_id',
        'client_id',
        'user_id',
        'quantity',
        'unit_id',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the stock transaction that initiated this move.
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get the unit associated with the stock move.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class); // Assuming a Unit model
    }
}
