<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Car;
use App\Models\User as Driver;
use Illuminate\Http\Request;
use App\Models\CarsLikeCount;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Drivers/Index');
    }
    public function getDriversList(Request $request)
    {
        if ($request->ajax()) {
            $data = Driver::where('type', '=', 'driver')->whereNotNull('email_verified_at')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function create()
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Drivers/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where('type', 'driver'),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->where('type', 'driver'),
                'regex:/^[a-z0-9_]+$/'
            ],
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username may not be greater than 100 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.regex' => 'Username must only contain lowercase letters, numbers, and underscores.',
        ]);
        $avatar = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('users', 'public');
        }
        Driver::create([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => "driver",
            'is_active' => $request->is_active,
            'password' => Hash::make($request->password),
            'is_created_by_owner' => true,
            'email_verified_at' => Carbon::now(),
            'avatar' => $avatar,
        ]);
        return to_route('drivers.index');
    }
    public function edit(Driver $driver)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Drivers/Edit', [
            'driver' => fn() => $driver,
        ]);
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where('type', 'driver')->ignore($driver->id),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->where('type', 'driver')->ignore($driver->id),
                'regex:/^[a-z0-9_]+$/'
            ],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username may not be greater than 100 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.regex' => 'Username must only contain lowercase letters, numbers, and underscores.',
        ]);
        if ($request->hasFile('avatar')) {
            if ($driver->avatar) {
                Storage::disk('public')->delete($driver->avatar);
            }
            $driver->avatar = $request->file('avatar')->store('users', 'public');
        }

        if($request->password) {
            $driver->password = Hash::make($request->password);
        }

        $driver->update([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);
        return to_route('drivers.index');
    }
    public function deleteSelected(Request $request)
     {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $ids = $request->input('ids');
        $drivers = Driver::whereIn('id', $ids)->get();
        if ($drivers->isEmpty()) {
            return redirect()->route('drivers.index', ['message' => 'No drivers found with the provided IDs']);
        }
        foreach ($drivers as $driver) {
            if ($driver->avatar) {
                Storage::disk('public')->delete($driver->avatar);
            }
            if ($driver->cover) {
                Storage::disk('public')->delete($driver->cover);
            }
            $driver->delete();
        }
        return redirect()->route('drivers.index');
    }


}
