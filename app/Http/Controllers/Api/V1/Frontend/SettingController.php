<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Country;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CountryResource;

class SettingController extends Controller
{
    public function index() 
    {
        $setting = Setting::first();
        return response()->json([
            'shipping' => $setting->shipping,
        ]);
    }
    public function countries() 
    {
        $countries = Country::all();
        return CountryResource::collection($countries);
    }
}
