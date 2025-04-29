<?php

namespace App\Http\Controllers\Api\V1\Frontend\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Display the verification notice for the authenticated user.
     */
    public function showVerificationPage()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json(['message' => 'Please verify your email address.']);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        // Validate the verification link (hash comparison)
        if ($user->getEmailForVerification() != $request->email || !hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        // Trigger verified event
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified.']);
    }
}

