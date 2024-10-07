<?php

namespace App\Http\Controllers\Car;

use Inertia\Inertia;
use App\Models\CarCondition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ConditionController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Cars/Conditions/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Cars/Conditions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:car_conditions,name',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        CarCondition::create($validated);

        return redirect()->route('cars.conditions.index')->with('success', 'Car Category created successfully.');
    }

    public function show(CarCondition $carCondition)
    {
        return Inertia::render('Admin/Cars/Conditions/Show', ['category' => $carCondition]);
    }

    public function edit(CarCondition $carCondition)
    {
        return Inertia::render('Admin/Cars/Conditions/Edit', ['category' => $carCondition]);
    }

    public function update(Request $request, CarCondition $condition)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_conditions', 'name')->ignore($condition->id), 
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
        $condition->update($validated);
        return redirect()->route('cars.conditions.index');
    }

    public function destroy(Request $request, CarCondition $condition)
    {
        $condition->delete();
        return redirect()->route('cars.conditions.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        CarCondition::whereIn('id', $ids)->delete();  
        return redirect()->route('cars.conditions.index');
    }
    public function getConditions(Request $request)
    {
        if ($request->ajax()) {
            $data = CarCondition::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
