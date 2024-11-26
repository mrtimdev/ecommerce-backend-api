<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
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
            'name'     => 'required|string|unique:permissions,name,'.$this->id,
            'display_name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => 'Permission name has already been taken.',
            'name.required' => 'Permission name is required',
            'display_name.required'  => 'Display name is required',
        ];
    }
}
