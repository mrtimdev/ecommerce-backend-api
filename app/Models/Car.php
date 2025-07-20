<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use OwenIt\Auditing\Contracts\Auditable;

class Car extends EloquentModel implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;


    protected $fillable = ['sourced_link', 'listing_date' ,'code', 'name', 'slug', 'total_price', 'car_price', 'price', 'discount', 'client_id', 'type', 'year', 'mileage', 'description', 'featured_image', 'is_featured', 'is_active', 'steering_id', 'engine_volume', 'size' ,'door', 'passenger_id', 'cylinder', 'water_flood_damaged', 'former_rental_car', 'former_taxi', 'recovered_theft', 'police_car', 'salvage_record', 'fuel_conversion', 'modified_seats', 'category_id', 'condition_id', 'brand_id', 'model_id', 'fuel_type_id', 'transmission_type_id', 'color_id', 'drive_type_id', 'location_id', 'status', 'youtube_link', 'towing_export_document', 'shipping', 'tax_import', 'clearance', 'service', 'first_payment', 'second_payment', 'third_payment', 'youtube_link', 'created_by', 'updated_by', 'view_count', 'like_count', 'featured_at'];
    // ['plate_number', 'first_registered_date', 'engine_power', 'odometer_reading', ]
    protected $appends = ['featured_image_full_path'];

    public function getFeaturedImageFullPathAttribute()
    {
        $image_path = $this->attributes['featured_image'];
        return $image_path ? asset('storage/' . $image_path) : asset('assets/images/no-image.jpg');
    }
    public function getIsFeaturedAttribute()
    {
        return $this->attributes['is_featured'] ? true : false;
    }
    public function getIsActiveAttribute(): bool
    {
        return $this->attributes['is_active'] ? true : false;
    }

    public function getWaterFloodDamagedAttribute()
    {
        return $this->attributes['water_flood_damaged'] ? true : false;
    }

    public function getFormerRentalCarAttribute()
    {
        return $this->attributes['former_rental_car'] ? true : false;
    }

    public function getFormerTaxiAttribute()
    {
        return $this->attributes['former_taxi'] ? true : false;
    }

    public function getRecoveredTheftAttribute()
    {
        return $this->attributes['recovered_theft'] ? true : false;
    }

    public function getPoliceCarAttribute()
    {
        return $this->attributes['police_car'] ? true : false;
    }

    public function getSalvageRecordAttribute()
    {
        return $this->attributes['salvage_record'] ? true : false;
    }

    public function getFuelConversionAttribute()
    {
        return $this->attributes['fuel_conversion'] ? true : false;
    }

    public function getModifiedSeatsAttribute()
    {
        return $this->attributes['modified_seats'] ? true : false;
    }


    public function images()
    {
        return $this->hasMany(CarImage::class);
    }
    public function gallery()
    {
        return $this->hasMany(CarImage::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }
    public function fuel_type()
    {
        return $this->belongsTo(FuelType::class);
    }
    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }
    public function transmission_type()
    {
        return $this->belongsTo(TransmissionType::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }
    public function drive_type()
    {
        return $this->belongsTo(DriveType::class);
    }
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
    public function steering()
    {
        return $this->belongsTo(Steering::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public function hotMarks()
    {
        return $this->belongsToMany(HotMark::class, 'car_hot_marks');
    }
    public function hot_marks()
    {
        return $this->belongsToMany(HotMark::class, 'car_hot_marks');
    }
    public function options()
    {
        return $this->belongsToMany(Option::class, 'car_options');
    }
    // public function options()
    // {
    //     return $this->hasManyThrough(Option::class, 'car_options', 'car_id', 'id', 'id', 'option_id');
    // }

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class);
    }
    public function orders()
    {
        return $this->hasMany(CustomerOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id')->where('type', 'client');
    }

    public function getCarOptionsByGroup()
    {
        return $this->options()
            ->with('optionGroup')
            ->get()
            ->groupBy('optionGroup.id')
            ->map(function ($options, $groupId) {
                return [
                    'title' => $options->first()->optionGroup->name ?? null,
                    'data' => $options->map(function ($option) {
                        return $option->name;
                    }),
                ];
            })
            ->values();
    }

    public function getCarOptionsByGroupWithDetail()
    {
        return $this->options()
            ->with('group')
            ->get()
            ->groupBy('group.id')
            ->map(function ($options, $groupId) {
                return [
                    'group_id' => $groupId,
                    'group_name' => $options->first()->group->name ?? 'No Group',
                    'items' => $options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                        ];
                    })->values(),
                ];
            })
            ->values();
    }

    public function likeCounts()
    {
        return $this->hasMany(CarsLikeCount::class, 'car_id');
    }

    public function likes()
    {
        return $this->hasMany(CarsLikeCount::class, 'car_id');
    }



    public static function boot()
    {
        parent::boot();

        static::creating(function ($car) {
            $car->slug = static::generateUniqueSlug($car->code, $car->name);
        });

        static::updating(function ($car) {
            if ($car->isDirty(['name', 'code'])) {
                $car->slug = static::generateUniqueSlug($car->code, $car->name);
            }
        });
    }
    public static function generateUniqueSlug($code, $name)
    {
        $baseSlug = Str::slug("{$code} {$name}");
        $count = static::where('slug', 'like', "$baseSlug%")->count();

        return $count ? "{$baseSlug}-{$count}" : $baseSlug;
    }

    public function paymentDetails(): array
    {
        $payments = [
            'car_price' => 'Car Price',
            'towing_export_document' => 'Export License Fee',
            'shipping' => 'Shipping Fee',
            'tax_import' => 'Import Tax',
            'clearance' => 'Clearance Fee',
            'service' => 'Import Service Fee',
            'total_price' => 'Total Price',
        ];

        return collect($payments)->filter(function ($title, $key) {
            return $this->{$key} !== null;
        })->map(function ($title, $key) {
            return [
                'title' => $title,
                'value' => $this->{$key},
            ];
        })->values()->toArray();
    }

    public function paymentTerms(): array
    {
        $payments = [
            'first_payment',
            'second_payment',
            'third_payment',
        ];
        return collect($payments)->filter(function ($term) {
            return $this->{$term} !== null;
        })->map(function ($term) {
            return [
                'title' => __($term),
                'value' => $this->{$term},
            ];
        })->values()->toArray();
    }

    public function featureInformation(): array
    {
        $booleanFeatures = [
            'water_flood_damaged',
            'former_rental_car',
            'former_taxi',
            'recovered_theft',
            'police_car',
            'salvage_record',
            'fuel_conversion',
            'modified_seats',
        ];
        return collect($booleanFeatures)->filter(function ($feature) {
            return $this->{$feature} !== null;
        })->map(function ($feature) {
            return [
                'title' => __($feature),
                'value' => $this->{$feature} ? 'yes' : 'no',
            ];
        })->values()->toArray();
    }
}
