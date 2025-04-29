<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->where('type', 'backend'),
                'regex:/^[a-z0-9_]+$/'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where('type', 'backend'),
            ],
            'phone' => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'role' => 'required|array',
            'role.id' => 'required|integer|exists:roles,id',
        ];
    }
    public function messages()
    {
        return [
            'role.required' => 'The role field is required.',
            'role.array' => 'The role field is required.',
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username may not be greater than 100 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.regex' => 'Username must only contain lowercase letters, numbers, and underscores.',
        ];
    }
}
