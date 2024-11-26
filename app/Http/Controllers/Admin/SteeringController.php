<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Steering;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SteeringController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Steerings/Index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:steerings,code|regex:/^[^\s]+$/|max:100', 
            'name' => 'required|string|max:191|unique:steerings,name',
            'description' => 'nullable|string|max:191',
        ]);

        Steering::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('steerings.create');
        }
        return redirect()->route('steerings.index');
    }

    public function update(Request $request, Steering $Steering)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('steerings', 'code')->ignore($Steering->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('steerings', 'name')->ignore($Steering->id), 
            ],
            'description' => 'nullable|string|max:191',
        ]);
        $Steering->update($validated);
        return redirect()->route('steerings.index');
    }

    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        Steering::whereIn('id', $ids)->delete();  
        return redirect()->route('steerings.index');
    }
    public function getSteerings(Request $request)
    {
        if ($request->ajax()) {
            $data = Steering::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
