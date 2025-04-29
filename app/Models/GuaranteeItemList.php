<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuaranteeItemList extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'guarantee_item_id'];
    public $timestamps = false;
    public function guaranteeItem()
    {
        return $this->belongsTo(GuaranteeItem::class);
    }
}
