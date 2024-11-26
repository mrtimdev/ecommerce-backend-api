<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id',
        'item_code',
        'link',
        'link_korea',
    ];
    public $timestamps = false;
    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }
}
