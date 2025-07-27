<?php

namespace App\Models;

use App\Models\User;
use App\Models\User as Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMove extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'product_id',
        'package_id',
        'delivery_id',
        'stock_id',
        'client_id',
        'user_id',
        'quantity',
        'unit_id',
        'price',
        'type', // 'in' or 'out'
        'move_type',
        'owner_type',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
