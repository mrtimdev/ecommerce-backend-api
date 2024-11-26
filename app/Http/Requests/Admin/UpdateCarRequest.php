<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sourced_link' => 'nullable|url',
            'listing_date' => 'nullable|date',
            'code' => 'required|string|max:100|unique:cars,code,' . $this->car->id,
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cars,slug,' . $this->car->id,
            'plate_number' => 'nullable|string|max:50',
            'total_price' => 'required|numeric|min:0',
            'car_price' => 'required|numeric|min:0',
            'year' => 'required|integer|digits:4',
            'mileage' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
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
            'first_registered_date' => 'nullable|date',
            'size' => 'nullable|string|max:191',

            'category' => 'required|array',
            'category.id' => 'required|integer|exists:categories,id',
            'condition' => 'required|array',
            'condition.id' => 'required|integer|exists:conditions,id',
            'brand' => 'required|array',
            'brand.id' => 'required|integer|exists:brands,id',
            'model' => 'required|array',
            'model.id' => 'required|integer|exists:models,id',
            'fuel_type' => 'required|array',
            'fuel_type.id' => 'required|integer|exists:fuel_types,id',
            'transmission_type' => 'required|array',
            'transmission_type.id' => 'required|integer|exists:transmission_types,id',
            'color' => 'required|array',
            'color.id' => 'required|integer|exists:colors,id',
            'passenger' => 'required|array',
            'passenger.id' => 'required|integer|exists:passengers,id',
            'steering' => 'required|array',
            'steering.id' => 'required|integer|exists:steerings,id',
            'drive_type' => 'required|array',
            'drive_type.id' => 'required|integer|exists:drive_types,id',
            'hot_marks' => 'array',
            'hot_marks.*.id' => 'integer|exists:hot_marks,id',
            'options' => 'array',
            'options.*.id' => 'integer|exists:options,id',
            'location' => 'required|array',
            'location.id' => 'required|integer|exists:countries,id',
            'status' => 'required|string|in:available,booked,sold',

            'youtube_link' => 'nullable|string|url',
            'towing_export_document' => 'required|numeric|min:0',
            'shipping' => 'required|numeric|min:0',
            'tax_import' => 'required|numeric|min:0',
            'clearance' => 'required|numeric|min:0',
            'service' => 'required|numeric|min:0',
            'first_payment' => 'required|numeric|min:0',
            'second_payment' => 'required|numeric|min:0',
            'third_payment' => 'required|numeric|min:0',
        ];

    }

    public function messages()
    {
        return [
            'code.unique' => 'The code has already been taken by another car.',
            'slug.unique' => 'The slug has already been taken by another car.',
            'featured_image.image' => 'The featured image must be an image file.',
            'gallery_images.*.image' => 'Each gallery image must be an image file.',

            'status.in' => 'The selected status is invalid. Please choose either "available," "booked," or "sold."',
            'status.required' => 'The status field is required.',
            'status.string' => 'The status must be a valid string.',
        ];
    }
}
