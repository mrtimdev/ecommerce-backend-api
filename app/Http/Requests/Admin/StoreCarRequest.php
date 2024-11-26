<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
    public function rules()
    {
        return [
            'sourced_link' => 'nullable|url',
            'listing_date' => 'nullable|date',
            'code' => 'required|string|max:100|unique:cars,code',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cars,slug',
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
        
            // Foreign key validations
            
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
            'code.required' => 'The car code is required.',
            'code.string' => 'The car code must be a string.',
            'code.max' => 'The car code may not be greater than 100 characters.',
            'code.unique' => 'The car code has already been taken by another car.',
            
            'name.required' => 'The car name is required.',
            'name.string' => 'The car name must be a string.',
            'name.max' => 'The car name may not be greater than 255 characters.',
            
            'slug.string' => 'The slug must be a string.',
            'slug.max' => 'The slug may not be greater than 255 characters.',
            'slug.unique' => 'The slug has already been taken by another car.',
            
            'plate_number.required' => 'The plate number is required.',
            'plate_number.string' => 'The plate number must be a string.',
            'plate_number.max' => 'The plate number may not be greater than 50 characters.',
            
            'total_price.required' => 'The current price is required.',
            'total_price.numeric' => 'The current price must be a number.',
            'total_price.min' => 'The current price must be at least 0.',
            
            'previous_price.numeric' => 'The previous price must be a number.',
            'previous_price.min' => 'The previous price must be at least 0.',
            
            'year.required' => 'The year is required.',
            'year.integer' => 'The year must be an integer.',
            'year.digits' => 'The year must be 4 digits.',
            
            'mileage.required' => 'The mileage is required.',
            'mileage.integer' => 'The mileage must be an integer.',
            'mileage.min' => 'The mileage must be at least 0.',
            
            'featured_image.image' => 'The featured image must be an image file.',
            'featured_image.mimes' => 'The featured image must be a file of type: jpg, png, jpeg.',
            'featured_image.max' => 'The featured image may not be greater than 2MB.',
            
            'gallery_images.*.image' => 'Each gallery image must be an image file.',
            'gallery_images.*.mimes' => 'Each gallery image must be a file of type: jpeg, png, jpg, gif.',
            'gallery_images.*.max' => 'Each gallery image may not be greater than 2MB.',
            
            'is_featured.boolean' => 'The featured status must be true or false.',
            'is_active.boolean' => 'The active status must be true or false.',
            
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            
            'condition_id.required' => 'The condition is required.',
            'condition_id.exists' => 'The selected condition does not exist.',
            
            'brand_id.required' => 'The brand is required.',
            'brand_id.exists' => 'The selected brand does not exist.',
            
            'model_id.required' => 'The model is required.',
            'model_id.exists' => 'The selected model does not exist.',
            
            'fuel_type_id.required' => 'The fuel type is required.',
            'fuel_type_id.exists' => 'The selected fuel type does not exist.',
            
            'transmission_type_id.required' => 'The transmission type is required.',
            'transmission_type_id.exists' => 'The selected transmission type does not exist.',
            
            'color_id.required' => 'The color is required.',
            'color_id.exists' => 'The selected color does not exist.',

            'status.in' => 'The selected status is invalid. Please choose either "available," "booked," or "sold."',
            'status.required' => 'The status field is required.',
            'status.string' => 'The status must be a valid string.',
        ];
    }

}
