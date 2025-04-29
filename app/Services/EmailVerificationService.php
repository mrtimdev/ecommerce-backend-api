<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmailVerificationService
{
    protected $apiKey;

    public function __construct()
    {
        // Set your NeverBounce API Key
        $this->apiKey = env('NEVERBOUNCE_API_KEY');
    }

    public function verifyEmail($email)
    {
        $response = Http::get('https://api.neverbounce.com/v4/single/check', [
            'key' => $this->apiKey,
            'email' => $email,
        ]);

        $data = $response->json();

        if (isset($data['result']) && $data['result'] == 'valid') {
            return true;
        }

        return false;
    }
}
