<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DriveTypeController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/DriveTypes/Index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:drive_types,code|regex:/^[^\s]+$/|max:100', 
            'name' => 'required|string|max:191|unique:drive_types,name',
            'is_active' => ['required', 'boolean'],
        ]);

        DriveType::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('driveTypes.create');
        }
        return redirect()->route('driveTypes.index')->with('success', 'Car Category created successfully.');
    }

    public function update(Request $request, DriveType $driveType)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('drive_types', 'code')->ignore($driveType->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('drive_types', 'name')->ignore($driveType->id), 
            ],
            'is_active' => ['required', 'boolean'],
        ]);
        $driveType->update($validated);
        return redirect()->route('driveTypes.index');
    }

    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        DriveType::whereIn('id', $ids)->delete();  
        return redirect()->route('driveTypes.index');
    }
    public function getDriveTypes(Request $request)
    {
        if ($request->ajax()) {
            $data = DriveType::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
