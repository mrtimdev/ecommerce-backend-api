<?php

namespace App\Http\Controllers;

use Storage;
use Inertia\Inertia;
use App\Models\Country;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::first();
        return Inertia::render('Admin/Settings/Index', [
            'setting' => $setting,
        ]);
    }
    public function shipping(Request $request)
    {
        $request->validate([
            'shipping' => 'required|numeric|min:0|max:999999.99' 
        ]);

        $setting = Setting::first();
        $setting->update([
            'shipping' => $request->shipping
        ]);

        return redirect()->route('settings.index');
    }
    public function login_logo(Request $request)
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

        return redirect()->route('settings.index');
    }
    public function site_configs(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'site_name' => 'required|string', 
            'site_link' => 'required|url', 
        ]);

        $setting = Setting::first();
        if ($request->hasFile('site_logo')) {
            Storage::disk('public')->delete($setting->site_logo); 
            $setting->site_logo = $request->file('site_logo')->store('site', 'public');
        }
        
        $setting->update([
            'site_name' => $request->site_name,
            'site_link' =>  $request->site_link,
        ]);

        return redirect()->route('settings.index');
    }

    public function getSettingConfigs()
    {
        $setting = Setting::first();
        return response()->json([
            'data' => $setting
        ]);
    }

    # countries 
    public function countryIndex()
    {
        $countries = Country::all();

        return Inertia::render('Admin/Settings/Countries/Index', [
            'countries' => $countries,
        ]);
    }
    public function countryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:countries,name',      
            'flag' => 'nullable|string|max:8',            
            'flag_code' => 'required|string|size:2',           
            'code' => 'required|string|max:50',           
            'dial_code' => 'required|string|max:10',  
            'is_active' => ['required', 'boolean'],    
        ]);

        Country::create([
            'name' => $request->name,
            'flag' => $request->flag,
            'flag_code' => $request->flag_code,
            'code' => $request->code,
            'dial_code' => $request->dial_code,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('settings.countries.index');
    }

    public function countryUpdate(Request $request, Country $country)
    {
        $request->validate([ 
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('countries', 'name')->ignore($country->id), 
            ],    
            'flag' => 'nullable|string|max:8',    
            'flag_code' => 'required|string|size:2',                  
            'code' => 'required|string|max:50',            
            'dial_code' => 'required|string|max:10',   
            'is_active' => ['required', 'boolean'],    
        ]);


        $country->update([
            'name' => $request->name,
            'flag' => $request->flag,
            'flag_code' => $request->flag_code,
            'code' => $request->code,
            'dial_code' => $request->dial_code,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('settings.countries.index');
    }

    public function deleteSelectedCountries(Request $request)
    {
        $ids = collect($request->input('ids', []));
        $countries = Country::whereIn('id', $ids)->with('stories')->get();
        $deletableCountries = $countries->filter(fn($country) => !$country->stories()->exists());
  
        Country::whereIn('id', $deletableCountries->pluck('id'))->delete();
        return redirect()->route('settings.countries.index');
        
    }

    public function getCountries(Request $request)
    {
        if ($request->ajax()) {
            $data = Country::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function getAllCountries(Request $request)
    {
        if ($request->ajax()) {
            $data = Country::orderBy('id', 'desc')->get();
            return response()->json([
                'countries' => $data
            ]);
        }
    }
}
