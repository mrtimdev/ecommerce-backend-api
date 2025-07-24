<?php

namespace App\Models;

use App\Models\User as Client;
use App\Models\User as ReceivedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receive extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'package_id',
        'received_by',   // admin user ID
        'date',
        'note',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(ReceivedBy::class, 'received_by');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function items()
    {
        return $this->hasMany(ReceiveItem::class);
    }
}
