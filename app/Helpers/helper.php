<?php

use Carbon\Carbon;
use App\Models\User;
use Twilio\Rest\Client;
use App\Mail\Frontend\OtpMail;
use Illuminate\Support\Facades\Mail;

if (!function_exists('statusFormat')) {
    function statusFormat($status)
    {
        if ($status === 'active') {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-success text-white">'
                . $status .
                '</div>';
        } elseif ($status === 'inactive') {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-red-600 text-white">'
                . $status .
                '</div>';
        } else {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded-full bg-gray-600 text-white">'
                . $status .
                '</div>';
        }
    }
}


if (!function_exists('decimal')) {
    function decimal($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
}

if (!function_exists('formatMoney')) {
    function formatMoney($amount)
    {
        // Format the amount as currency
        return '<span class="text-xs font-semibold">' . number_format($amount, 2) . '</span>';
    }
}

if (!function_exists('imageFormat')) {
    function imageFormat($src)
    {
        $image_path = $src ? "/storage/{$src}" : "/assets/images/no-image.jpg";
        return '
            <div class="image flex items-center justify-center">
                <img src="' . $image_path . '" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
            </div>';
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($value)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value), '-'));
    }
}



if (!function_exists('extractYouTubeVideoId')) {
    function extractYouTubeVideoId($url, $is_full_url = false)
    {
        if(!$url) {
            return "";
        }
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.*?[?&]v=)|youtu\.be\/)([^&?\/\s]{11})/';
        preg_match($regex, $url, $match);
        if($is_full_url) {
            return '<a class="text-blue-400" href="'.($url ?? "").'" target="_blank">'.($url ?? "").'</a>';
        }
        return $match ? $match[1] : null;
    }
}
if (!function_exists('extractFacebookVideoId')) {
    function extractFacebookVideoId($url, $is_full_url = false)
    {
        if(!$url) {
            return "";
        }
        $shareRegex = '/https?:\/\/(?:www\.)?web\.facebook\.com\/share\/v\/([^\/?&]+)/';
        $watchRegex = '/https?:\/\/(?:www\.)?fb\.watch\/([^\/?&]+)/';
        if($is_full_url) {
            return '<a class="text-blue-400" href="'.($url ?? "").'" target="_blank">'.($url ?? "").'</a>';
        }
        if (preg_match($shareRegex, $url, $match) || preg_match($watchRegex, $url, $match)) {
            return $match[1];
        }
        return null;
    }
}


if (!function_exists('sendOtpEmail')) {
    /**
     * Send OTP via Email to the user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    function sendOtpEmail(User $user, $is_new_email = false)
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        if($user) {
            $user->update([
                'otp' => $otp,
                'otp_expired' => Carbon::now()->addMinutes(10),
            ]);
            if($is_new_email) {
                $user->email = $user->new_email;
            }
            Mail::to($user->email)->send(new OtpMail($otp));
        }
    }
}

if (!function_exists('sendOtpPhone')) {
    /**
     * Send OTP via Phone using Firebase.
     *
     * @param \App\Models\User $user
     * @param string $phoneNumber
     * @return void
     */
    function sendOtpPhone(User $user)
    {
        // Initialize Firebase Auth
        // $auth = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS_PATH'))->createAuth();
        $appAuth = Firebase::project('OTP Testing')->auth();

        // Generate a new 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update the user record with OTP and expiration time
        $user->update([
            'otp' => $otp,
            'otp_expired' => Carbon::now()->addMinutes(5), // OTP valid for 5 minutes
        ]);

        // Send OTP via Firebase (SMS)
        try {
            // Here Firebase Authentication is used to send OTP via phone number
            $auth->sendPhoneNumberVerification($user->phone);  // This will trigger OTP via SMS
        } catch (\Throwable $e) {
            // Handle the exception
            \Log::error("Error sending OTP via Firebase: " . $e->getMessage());
        }
    }
}