<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'client_id', 'quantity', 'total_price',
        'discount', 'payment_status', 'date', 'note', 'total_amount',
        'paid_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2', // Cast paid_amount to decimal
        'date' => 'date', // Ensure date is cast properly
        // Removed 'ftd_payments' cast
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(StockItem::class); // Or StockProduct::class, etc.
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate the remaining due amount for this stock.
     */
    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
