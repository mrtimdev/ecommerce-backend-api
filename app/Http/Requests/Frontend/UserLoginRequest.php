<?php

namespace App\Http\Requests\Frontend;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'identify' => ['required', 'string'], // Can be either email or username
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $identify = $this->input('identify');
        $password = $this->input('password');
        $fieldType = filter_var($identify, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $identify)->where("type", "=", "client")->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            $errorMessage = $fieldType === 'email' ? 'We couldn’t find an account with that email. Please check and try again.' : 'We couldn’t find an account with that username. Please check and try again.';
            throw ValidationException::withMessages(['identify' => $errorMessage]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages(['identify' => 'Your account is not active. Please contact support for assistance.']);
        }
        if (!$user->email_verified_at) {
            throw ValidationException::withMessages(['identify' => 'Email not verified.']);
        }
        if (!$user->isClient()) {
            throw ValidationException::withMessages(['identify' => "We couldn’t find an account with that $fieldType."]);
        }
        // Check password
        if (!Auth::attempt([$fieldType => $identify, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages(['password' => 'The password you entered is incorrect. Please try again.']);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identify' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
