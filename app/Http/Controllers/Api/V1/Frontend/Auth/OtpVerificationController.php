<?php

namespace App\Http\Controllers\Api\V1\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailVerificationService;

class OtpVerificationController extends Controller
{

    protected $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    public function registerVerifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email,type,client',
            'otp' => 'required|integer',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->where('type', 'client')->first();

        if (!$user) {
            return response()->json(['status' => 'fail', 'message' => 'User not found.'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['status' => 'fail', 'message' => 'Invalid Code.'], 403);
        }

        if (Carbon::now()->greaterThan($user->otp_expired)) {
            return response()->json(['status' => 'fail', 'message' => 'Verification Code has expired.'], 403);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
            'otp' => null, // Clear OTP
            'otp_expired' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Email verified successfully.']);
    }
    public function newPasswordVerifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email,type,client',
            'otp' => 'required|integer',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->where('type', 'client')->first();

        if (!$user) {
            return response()->json(['status' => 'fail', 'message' => 'User not found.'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['status' => 'fail', 'message' => 'Invalid Code.'], 403);
        }

        if (Carbon::now()->greaterThan($user->otp_expired)) {
            return response()->json(['message' => 'Verification Code has expired.'], 403);
        }

        // Mark email as verified
        $user->update([
            'password_verified_at' => Carbon::now(),
            'otp' => null,
            'otp_expired' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Email verified successfully.']);
    }
    public function newEmailVerifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,new_email,type,client',
            'otp' => 'required|integer',
        ]);

        // Find the user by email
        $user = User::where('new_email', $request->email)->where('type', 'client')->first();

        if (!$user) {
            return response()->json(['status' => 'fail', 'message' => 'User not found.'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['status' => 'fail', 'message' => 'Invalid Code.'], 403);
        }

        if (Carbon::now()->greaterThan($user->otp_expired)) {
            return response()->json(['status' => 'fail', 'message' => 'Verification Code has expired.'], 403);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
            'email' => $user->new_email,
            'new_email' => null,
            'is_new_email' => false,
            'otp' => null,
            'otp_expired' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Email verified successfully.']);
    }


    public function resendCode(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email,type,client',
        ]);

        $user = User::where('email', $request->email)->where('type', 'client')->first();
        sendOtpEmail($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Verification code was sent to your email address.',
        ], 200);
    }

    public function resendCodeForNewEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,new_email,type,client',
        ]);

        $user = User::where('new_email', $request->email)->where('type', 'client')->first();
        sendOtpEmail($user, true);
        return response()->json([
            'status' => 'success',
            'message' => 'Verification code was sent to your email address.',
        ], 200);
    }

}
