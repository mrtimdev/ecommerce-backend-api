<?php

namespace App\Models;

use App\Models\User as Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_no',
        'client_id',
        'customer_id',
        'address',
        'note',
        'status',
        'package_status',
        'image_path',
    ];

    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
