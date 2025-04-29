<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CarsLikeCount;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Clients/Users/Index');
    }
    public function getClientsList(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('id', '!=', auth()->id())->where('type', '=', 'frontend')->whereNotNull('email_verified_at')->orderBy('id', 'desc')->get();
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
        return inertia('Admin/Clients/Users/Create');
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
                Rule::unique('users', 'email')->where('type', 'frontend'),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->where('type', 'frontend'),
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
        User::create([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => "frontend",
            'is_active' => $request->is_active,
            'password' => Hash::make($request->password),
            'is_created_by_owner' => true,
            'email_verified_at' => Carbon::now(),
            'avatar' => $avatar,
        ]);
        return to_route('clients.index');
    }
    public function edit(User $user)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Clients/Users/Edit', [
            'user' => fn() => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where('type', 'frontend')->ignore($user->id),
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->where('type', 'frontend')->ignore($user->id),
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
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('users', 'public');
        }

        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->update([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);
        return to_route('clients.index');
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
        $users = User::whereIn('id', $ids)->get();
        if ($users->isEmpty()) {
            return redirect()->route('clients.index', ['message' => 'No clients found with the provided IDs']);
        }
        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            if ($user->cover) {
                Storage::disk('public')->delete($user->cover);
            }
            CarsLikeCount::where('user_id', $user->id)->delete();
            $user->delete();
        }
        return redirect()->route('clients.index');
    }

    public function itemsLiked(Request $request, ?User $user)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $users = User::where('type', 'frontend')->get();
        return inertia('Admin/Clients/Cars/Liked', [
            'user' => $user?->id ? $user : null,
            'users' => fn () => $users,
        ]);
    }
    public function getItemsLikedList(Request $request, ?User $user)
    {
        $query = $user?->id
        ? $user->likedCars()
        : Car::with(['category', 'condition', 'brand', 'model', 'fuelType', 'images']);
        // $query->select([
        //         'id',
        //         'code',
        //         'name',
        //         'total_price',
        //         'car_price',
        //         'year',
        //         'featured_image',
        //         'is_active',
        //         'brand_id',
        //         'status',
        //         'like_count',
        //         'view_count',
        //     ]);
        $query->where('like_count', '>', 0);
        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('total_price', fn($row) => $row->total_price)
        ->addColumn('category_name', fn($row) => $row->category->name ?? 'N/A')
        ->addColumn('condition_name', fn($row) => $row->condition->name ?? 'N/A')
        ->addColumn('brand_name', fn($row) => $row->brand->name ?? 'N/A')
        ->addColumn('model_name', fn($row) => $row->model->name ?? 'N/A')
        ->addColumn('fuel_type_name', fn($row) => $row->fuelType->name ?? 'N/A')
        ->addColumn('image_path', fn($row) => $row->images[0]->image_path ?? null)
        ->make(true);
    }
}
