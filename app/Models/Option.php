<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'option_group_id'];
    public $timestamps = false;

    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }

    // public function country(): BelongsTo
    // {
    //     return $this->belongsTo(Country::class);
    // }
}
