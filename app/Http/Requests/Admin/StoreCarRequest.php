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
            'code' => 'required|string|max:100|unique:cars,code',
            'name' => 'required|string|max:255',
            'slug' => 'string|max:255|unique:cars,slug',
            'plate_number' => 'required|string|max:50',
            'current_price' => 'required|numeric|min:0',
            'previous_price' => 'nullable|numeric|min:0',
            'year' => 'required|integer|digits:4',
            'mileage' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:models,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
        ];
    }
}
