<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCar extends Model
{
    


    protected $fillable = [
        'code',
        'name',
        'slug',
        'plate_number',
        'price',
        'discount',
        'year',
        'mileage',
        'description',
        'featured_image',
        'is_featured',
        'is_active',
        'engine_volume',
        'door',
        'cylinder',
        'engine_power',
        'odometer_reading',
        'water_flood_damaged',
        'former_rental_car',
        'former_taxi',
        'recovered_theft',
        'police_car',
        'salvage_record',
        'fuel_conversion',
        'modified_seats',
        'first_registered_date',
        'category_id',
        'condition_id',
        'brand_id',
        'model_id',
        'fuel_type_id',
        'transmission_type_id',
        'color_id',
        'drive_type_id',
        'steering_id',
        'passenger_id',
        'location_id',
        'size',
        'status',
        'towing_export_document',
        'shipping',
        'tax_import',
        'clearance',
        'service',
        'first_payment',
        'second_payment',
        'third_payment',
        'youtube_link',
        'created_by',
        'updated_by',
        'view_count',
        'like_count',
    ];
}
