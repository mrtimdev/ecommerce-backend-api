<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'order_no',
        'full_name',
        'email',
        'phone',
        'telegram_or_phone',
        'detail',
        'price',
        'location',
        'item_code',
        'link',
        'link_korea',
        'status',
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    protected static function booted()
    {
        static::creating(function ($order) {
            // Generate the order number
            $order->order_no = self::generateOrderNo();
        });
    }

    private static function generateOrderNo()
    {
        do {
            $latestOrder = self::orderBy('created_at', 'desc')->first();

            if ($latestOrder) {
                // Extract the number from the latest order number
                $latestOrderNo = substr($latestOrder->order_no, 3); // Remove "ORD"
                $nextOrderNo = intval($latestOrderNo) + 1;
            } else {
                $nextOrderNo = 1; // Start from 1 if no orders exist
            }

            // Format the new order number
            $newOrderNo = 'ORD' . str_pad($nextOrderNo, 3, '0', STR_PAD_LEFT);
        } while (self::where('order_no', $newOrderNo)->exists()); // Check if the order number already exists

        return $newOrderNo;
    }
}
