<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\PermissionStoreRequest;
use App\Http\Requests\Admin\PermissionUpdateRequest;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Admin/Permissions/Index');
    }
    public function getPermissionsList(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function createOrUpdate(Request $request, ?Permission $permission)
    {
        return inertia('Admin/Permissions/CreateOrUpdate', [
            'show_modal' => true,
            'permission' => fn() => collect($permission)->count() ? $permission : (object) [],
        ]);
    }
    public function store(PermissionStoreRequest $request)
    {
        Permission::create($request->validated());
        return redirect()->route('permissions.index');
    }
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $permission->update($request->all());
        return redirect()->route('permissions.index');
    }
   
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        if(!$ids){
            return redirect()->route('permissions.index');
        } else {
            if(Permission::whereIn('id', $ids)->delete()) {
                return redirect()->route('permissions.index');
            } 
        }
    }
}
