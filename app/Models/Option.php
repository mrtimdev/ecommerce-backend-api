<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends EloquentModel
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'option_group_id'];
    public $timestamps = false;

    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }
    public function group()
    {
        return $this->belongsTo(OptionGroup::class, 'option_group_id');
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_options', 'option_id', 'car_id');
    }

    // public function cars()
    // {
    //     return $this->hasManyThrough(Car::class, 'car_options', 'option_id', 'id', 'id', 'car_id');
    // }

    public function models()
    {
        return $this->belongsToMany(Model::class, 'model_options', 'option_id', 'model_id');
    }

    public static function getGroupedOptions()
    {
        return self::with('group')->get()
            ->groupBy(fn($option) => $option->group->id ?? 'no_group')
            ->map(fn($group) => [
                'group_id' => $group->first()->group->id ?? null,
                'group_name' => $group->first()->group->name ?? 'No Group',
                'items' => $group->map(fn($option) => [
                    'id' => $option->id,
                    'name' => $option->name,
                ])->values(),
            ])->values();
    }
}
