<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use Laratrust\Helper;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\RoleStoreRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Roles/Index');
    }
    public function getRolesList(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function createOrUpdate(Request $request, ?Role $role)
    {
        return Inertia::render('Admin/Roles/CreateOrUpdate', [
            'show_modal' => true,
            'role' => collect($role)->count() ? $role : (object) [],
        ]);
    }
    public function store(RoleStoreRequest $request)
    {
        Role::create($request->validated());
        return redirect()->route('roles.index');
    }
    public function update(RoleUpdateRequest $request, Role $role)
    {
        if (!Helper::roleIsEditable($role)) {
            return redirect()->route('roles.index');
            return response()->json(['message' => __('role_not_editable'), 'role' => $role], 401);
        }
        $role->update($request->all());
        return redirect()->route('roles.index');
    }
    
    public function changePermissions(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('roles.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        if(!$ids){
            return redirect()->route('roles.index');
        } else {
            $roles = Role::whereIn('id', $ids)->get();
            if($roles) {
                $role_not_deletable = collect();
                foreach($roles as $role) {
                    $error = 0;
                    if (!Helper::roleIsDeletable($role)) {
                        $role_not_deletable->push($role);
                        $error = 1;   
                    }
                }
                if($error == 1) {
                    return response()->json(['message' => __('role.can_not_delete'), 'roles' => $role_not_deletable], 422);
                }
                if(Role::whereIn('id', $ids)->delete()) {
                    return redirect()->route('roles.index');
                }
            } 
        }
    }

    public function permissions(Request $request, Role $role)
    {
        return Inertia::render('Admin/Roles/Permissions', [
            'show_modal' => true,
            'role' => fn() => $role,
            'permissions' => fn() => Permission::all(),
        ]);
    }
}
