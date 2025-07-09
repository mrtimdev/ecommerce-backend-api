<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'price',
        'category_id',
        'unit_id',
        'description',
        'stock_alert',
        'is_active',
    ];
    protected $casts = [
        'price' => 'float', // or 'decimal:2' if you need specific precision
    ];


    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stockMoves()
    {
        return $this->hasMany(StockMove::class);
    }
}
