<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use Hash;
use Storage;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\UserResource;
use App\Http\Requests\Frontend\UserLoginRequest;
use App\Http\Requests\Frontend\UserRegisterRequest;
use App\Http\Requests\Frontend\UserUpdateAvatarRequest;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'type' => "frontend",
            'terms' => $request->terms,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status' => 'success',
        ], 201);
    }
    public function login(UserLoginRequest $request)
    {
        $request->authenticate();
        $device    = substr($request->userAgent() ?? '', 0, 255);
        $expiresAt = $request->remember ? null : Carbon::now()->addDays(1);
        return response()->json([
            'status' => 'success',
            'access_token' => auth()->user()->createToken($device, expiresAt: $expiresAt)->plainTextToken,
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
        $request->user()->currentAccessToken()->delete();
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
    public function update_avatar(
        UserUpdateAvatarRequest $request
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'The avatar was successfully updated.',
        ];
        $user = auth()->user();
        if($user->settings->avatar) {
            Storage::delete("avatars/{$user->id}/{$user->settings->avatar}");
        }
        $request
            ->user()
            ->settings()
            ->update([
                'avatar' => store_avatar($request, 'avatar'),
            ]);
        $user = $request->user()->settings->user;
        $successMessage['avatar'] = $user->settings->avatar_url;
        return response()->json($successMessage);
    }
}
