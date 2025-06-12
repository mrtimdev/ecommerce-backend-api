<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientStoreCarRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cars', 'code')->where('type', 'client'),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cars', 'slug')->where('type', 'client'),
            ],

            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|string',
            'year' => 'required|integer|digits:4',
            'mileage' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'engine_volume' => 'required|integer|min:0',
            'door' => 'required|integer|min:1',
            'cylinder' => 'required|integer|min:1',
            'engine_power' => 'nullable|integer|min:0',
            'odometer_reading' => 'nullable|string',

            'water_flood_damaged' => 'boolean',
            'former_rental_car' => 'boolean',
            'former_taxi' => 'boolean',
            'recovered_theft' => 'boolean',
            'police_car' => 'boolean',
            'salvage_record' => 'boolean',
            'fuel_conversion' => 'boolean',
            'modified_seats' => 'boolean',
            'size' => 'nullable|string|max:191',

            // Flattened foreign keys
            'category_id' => 'required|integer|exists:categories,id',
            'condition_id' => 'required|integer|exists:conditions,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'model_id' => 'required|integer|exists:models,id',
            'fuel_type_id' => 'required|integer|exists:fuel_types,id',
            'transmission_type_id' => 'required|integer|exists:transmission_types,id',
            'color_id' => 'required|integer|exists:colors,id',
            'drive_type_id' => 'required|integer|exists:drive_types,id',
            'steering_id' => 'required|integer|exists:steerings,id',
            'passenger_id' => 'required|integer|exists:passengers,id',
            'location_id' => 'required|integer|exists:countries,id',
            'youtube_link' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'The car code is required.',
            'code.unique' => 'This car code has already been used.',
            'slug.unique' => 'This slug is already taken.',
            'status.in' => 'The status must be one of available, booked, sold, or requesting.',
            'featured_image.image' => 'The featured image must be an image file.',
            'gallery_images.*.image' => 'Each gallery image must be a valid image file.',
            'year.digits' => 'The year must be exactly 4 digits.',
        ];
    }
}
