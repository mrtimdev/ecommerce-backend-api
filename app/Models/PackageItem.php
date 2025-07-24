<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'product_id',
        'unit_id',
        'quantity',
        'unit_price',
        'real_unit_price',
        'subtotal',
        'note',
        'status'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function receiveItems()
    {
        return $this->hasMany(ReceiveItem::class);
    }

    // Accessor: total quantity received so far
    public function getReceivedQuantityAttribute()
    {
        return $this->receiveItems()->sum('quantity');
    }

    // Accessor: balance quantity left to receive
    public function getBalanceQuantityAttribute()
    {
        return $this->quantity - $this->received_quantity;
    }

    // Accessor: status based on received quantities
    public function getStatusAttribute()
    {
        $received = $this->received_quantity;
        $total = $this->quantity;

        if ($received <= 0) {
            return 'pending';
        } elseif ($received < $total) {
            return 'partial';
        } else {
            return 'completed';
        }
    }

    public function scopePending($query)
    {
        return $query->whereRaw('quantity > (SELECT SUM(quantity) FROM receive_items WHERE package_item_id = package_items.id)');
    }
}
