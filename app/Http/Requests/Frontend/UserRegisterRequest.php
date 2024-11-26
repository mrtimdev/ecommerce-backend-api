<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'boolean', 
            'type' => 'nullable|in:frontend',
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'email.required' => 'An email address is required.',
            'email.unique' => 'This email is already registered.',
            'username.required' => 'A username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'The password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms.accepted' => 'You must agree to the Terms of Use.',
            'type.in' => 'The user type must be frontend.',
        ];
    }
}
