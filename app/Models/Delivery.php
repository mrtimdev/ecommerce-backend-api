<?php

namespace App\Models;

use App\Models\User as Client;
use App\Models\User as Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_no',
        'client_id',
        'package_id',
        'driver_id',
        'customer_id',
        'created_by',
        'updated_by',
        'address',
        'note',
        'status',
        'payment_status',
        'delivery_status',
        'image_path',
    ];

    public function package()
    {
        return $this->hasMany(Package::class, 'package_id');
    }
    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')
                    ->where('type', 'client');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id')
                    ->where('type', 'driver');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')
                    ->where('type', 'backend');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by')
                    ->where('type', 'backend');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
