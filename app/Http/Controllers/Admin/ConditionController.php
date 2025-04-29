<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ConditionController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Conditions/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Conditions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:conditions,code|regex:/^[^\s]+$/|max:100', 
            'name' => 'required|string|max:191|unique:conditions,name',
            'is_active' => ['required', 'boolean'],
        ]);

        Condition::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('conditions.create');
        }
        return redirect()->route('conditions.index')->with('success', 'Car Condition created successfully.');
    }

    public function show(Condition $Condition)
    {
        return Inertia::render('Admin/Conditions/Show', ['condition' => $Condition]);
    }

    public function edit(Condition $Condition)
    {
        return Inertia::render('Admin/Conditions/Edit', ['condition' => $Condition]);
    }

    public function update(Request $request, Condition $condition)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('conditions', 'code')->ignore($condition->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('conditions', 'name')->ignore($condition->id), 
            ],
            'is_active' => ['required', 'boolean'],
        ]);
        $condition->update($validated);
        return redirect()->route('conditions.index');
    }

    public function destroy(Request $request, Condition $condition)
    {
        $condition->delete();
        return redirect()->route('conditions.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        Condition::whereIn('id', $ids)->delete();  
        return redirect()->route('conditions.index');
    }
    public function getConditions(Request $request)
    {
        if ($request->ajax()) {
            $data = Condition::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
