<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Storage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return inertia('Admin/Users/Index');
    }
    public function getUsersList(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['roles:name'])->where('id', '!=', auth()->id())->where('type', '=', 'backend')->orderBy('id', 'desc')->get();
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
        return inertia('Admin/Users/Create', [
            'roles' => fn() => Role::all()
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $avatar = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('users', 'public');
        }
        $user = User::create([
            'name' => "$request->first_name $request->last_name",
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => "backend",
            'is_active' => $request->is_active,
            'avatar' => $avatar,
            'password' => Hash::make($request->password),
        ]);
        $user->addRole($request->input('role.id'));
        return to_route('users.index');
    }
    public function edit(User $user)
    {
        if(!auth()->user()->hasRole('owner')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $user->role = $user->roles->count() > 0 ? $user->roles[0] : false;
        return inertia('Admin/Users/Edit', [
            'user' => fn() => $user,
            'roles' => fn() => Role::all()
        ]);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
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
        
        $user->syncRoles([$request->input('role.id')]);
        return to_route('users.index');
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
            return redirect()->route('users.index', ['message' => 'No users found with the provided IDs']);
        }
        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->removeRole($user->roles[0]->id);
            $user->delete();
        }
        return redirect()->route('users.index');
    }

    public function update_avatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('users', 'public');
        }
        
        $user->update([]);
        return redirect()->back();
    }


}
