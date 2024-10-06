<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        if (!in_array($locale, ['en', 'kh'])) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        // Set the locale in the session
        Session::put('locale', $locale);
        App::setLocale($locale);

        return response()->json(['message' => 'Locale changed successfully']);
    }

    // Get the current locale
    public function getLocale()
    {
        $locale = Session::get('locale', config('app.locale'));
        return response()->json(['locale' => $locale]);
    }
}
