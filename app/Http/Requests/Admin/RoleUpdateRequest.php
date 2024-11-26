<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
            'name'     => 'required|string|unique:roles,name,'.$this->id,
            'display_name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => 'Role name has already been taken.',
            'name.required' => 'Role name is required',
            'display_name.required'  => 'Display name is required',
        ];
    }
}
