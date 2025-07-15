<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use Hash;
use Storage;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\Frontend\UserResource;
use App\Http\Requests\Frontend\UserLoginRequest;
use App\Http\Requests\Frontend\UserRegisterRequest;
use App\Http\Requests\Frontend\UserUpdateAvatarRequest;

class UserController extends Controller
{
    public function register(Request $request)
    {
        User::whereNull('email_verified_at')
            ->where('type', 'client')
            ->delete();
        $request->validate([
            'full_name' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where('type', 'client'),
            ],
            // 'username' => [
            //     'required',
            //     'string',
            //     'max:100',
            //     Rule::unique('users', 'username')->where('type', 'client'),
            //     'regex:/^[a-z0-9_]+$/'
            // ],
            'phone' => 'required|string|max:20',
            'terms' => 'required|boolean',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username may not be greater than 100 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.regex' => 'Username must only contain lowercase letters, numbers, and underscores.',
            'phone.required' => 'Phone number is required.',
            'terms.required' => 'You must agree to the terms.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => "$request->full_name",
            // 'first_name' => $request->first_name,
            // 'last_name' => $request->last_name,
            'email' => $request->email,
            // 'username' => $request->username,
            'phone' => $request->phone,
            'type' => "client",
            'terms' => $request->terms,
            'password' => Hash::make($request->password),
            'is_new_email' => false,
        ]);

        sendOtpEmail($user);

        return response()->json([
            'message' => 'Registered successfully. Please verify your email using the OTP sent to your email address.',
            'status' => 'success',
        ], 201);
    }
    public function login(UserLoginRequest $request)
    {
        $request->authenticate();
        $device    = substr($request->userAgent() ?? '', 0, 255);
        $expiresAt1Day = Carbon::now()->addDays(7);
        $expiresAt = $request->remember ? null : $expiresAt1Day;
        return response()->json([
            'status' => 'success',
            'access_token' => $request->user()->createToken($device, expiresAt: $expiresAt)->plainTextToken,
        ], 200);
    }

    public function profile(Request $request)
    {
        if ($request->user()) {
            return new UserResource($request->user());
        } else {
            return response()->json(['error' => 'unauthenticated'], 401);
        }
    }
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function avatar(string $filename) {
        $user = auth()->user();
        dd($user);
        $filePath = "/avatars/{$user->id}/$filename";
        if (! Storage::exists($filePath)) {
            throw new ModelNotFoundException();
        }
        return Storage::download($filePath, $filename);
    }
    public function updateAvatar(
        UserUpdateAvatarRequest $request
    ): JsonResponse {

        $user = Auth::guard('api')->user();
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('users', 'public');
        }

        $user->update([]);
        $successMessage = [
            'status'    => 'success',
            'message' => 'The avatar has been updated successfully.',
            'avatar' => $user->avatar_full_path,
        ];
        return response()->json($successMessage);
    }

    public function removeAvatar(
        Request $request
    ): JsonResponse {
        $user = Auth::guard('api')->user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->update(['avatar' => null]);
        $successMessage = [
            'status'    => 'success',
            'message' => 'Avatar has been removed.',
            'avatar' => $user->avatar_full_path,
        ];
        return response()->json($successMessage);
    }



    public function updateCover(
        Request $request
    ): JsonResponse {
        $request->validate([
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = Auth::guard('api')->user();
        if ($request->hasFile('cover')) {
            if ($user->cover && Storage::disk('public')->exists($user->cover)) {
                Storage::disk('public')->delete($user->cover);
            }
            $user->cover = $request->file('cover')->store('users', 'public');
        }

        $user->update([]);
        $successMessage = [
            'status'    => 'success',
            'message' => 'The cover has been updated successfully.',
            'cover' => $user->cover_full_path,
        ];
        return response()->json($successMessage);
    }
    public function removeCover(
        Request $request
    ): JsonResponse {
        $user = Auth::guard('api')->user();
        if ($user->cover && Storage::disk('public')->exists($user->cover)) {
            Storage::disk('public')->delete($user->cover);
        }
        $user->update(['cover' => null]);
        $successMessage = [
            'status'    => 'success',
            'message' => 'Cover has been removed.',
            'cover' => $user->cover_full_path,
        ];
        return response()->json($successMessage);
    }


    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::guard('api')->user();
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'email_verified_at' => null,
            'new_email' => $request->email,
            'is_new_email' => true,
        ]);
        sendOtpEmail($user, true);
        return response()->json([
            'status'    => 'success',
            'message' => 'Your email successfully updated, Please check your email to verify code.',
        ]);
    }


    public function updateUsername(Request $request)
    {
        $user = Auth::guard('api')->user();
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username,'.$user->id,
                'regex:/^[a-z0-9_]+$/'
            ],
        ], [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username may not be greater than 100 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.regex' => 'Username must only contain lowercase letters, numbers, and underscores.',
        ]);

        $user->update([
            'username' => $request->username,
        ]);
        return response()->json([
            'status'    => 'success',
            'message' => 'The username has been updated successfully.',
        ]);
    }



    public function updateInfo(Request $request)
    {
        $user = Auth::guard('api')->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email_2,'.$user->id,
            'phone' => 'required|string|max:20',
            'dob' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female,other',
            'company' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'first_name.max' => 'First name may not be greater than 255 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.max' => 'Last name may not be greater than 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email has already been taken.',

            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a string.',
            'phone.max' => 'Phone number may not be greater than 20 characters.',

            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Date of birth must be a valid date.',

            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be one of the following: male, female, or other.',

            'company.required' => 'Company name is required.',
            'company.string' => 'Company name must be a string.',
            'company.max' => 'Company name may not be greater than 255 characters.',

            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address may not be greater than 255 characters.',
        ]);

        $user->update([
            'name' => "{$request->first_name} {$request->last_name}",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_2' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'company' => $request->company,
            'address' => $request->address,
        ]);
        // $user->setting()->updateOrCreate(
        //     ['user_id' => $user->id],
        //     [
        //         'first_name' => $request->first_name,
        //         'last_name' => $request->last_name,
        //         'phone' => $request->phone,
        //         'email' => $request->email,
        //         'dob' => $request->dob,
        //         'gender' => $request->gender,
        //         'company' => $request->company,
        //         'address' => $request->address,
        //     ]
        // );
        return response()->json([
            'status'    => 'success',
            'message' => 'Your information successfully updated.',
        ]);
    }


    public function resetPassword(Request $request)
    {
        $token = $request->bearerToken();
        $tokenModel = PersonalAccessToken::findToken($token);
        $user = false;
        if ($tokenModel) {
            $user = $tokenModel->tokenable;
        } else {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::exists('users', 'email')->where('type', 'frontend'),
                ],
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user = User::whereEmail($request->email)->first();
            if($user && $user->id) {
                if(!$user->password_verified_at) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Please verify code first.',
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'We can not access to your account.',
                ], 403);
            }
        }


        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        if($user && $user->id) {
            $user->update([
                'password_verified_at' => null,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Your password has been updated.',
                'user' => $user->name,
            ], 200);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'We can not access to your account.',
        ], 403);
    }
}
