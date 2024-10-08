<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Setting;
use Illuminate\Http\Request;
use Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::first();
        return Inertia::render('Admin/Settings/Index', [
            'login_logo' => $setting->login_logo,
        ]);
    }
    public function upload(Request $request)
    {
        $request->validate([
            'login_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $setting = Setting::first();

        if ($setting && $setting->login_logo) {
            Storage::disk('public')->delete($setting->login_logo); 
        }

        $path = $request->file('login_logo')->store('logos', 'public');
        $setting = Setting::updateOrCreate([], [
            'login_logo' => $path,
        ]);

        return redirect()->back()->with('success', 'Logo uploaded successfully');
    }
}
