<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FuelTypeController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/FuelTypes/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/FuelTypes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:fuel_types,code|regex:/^[^\s]+$/|max:100',
            'name' => 'required|string|max:191|unique:fuel_types,name',
            'is_active' => ['required', 'boolean'],
        ]);

        FuelType::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('fueltypes.create');
        }
        return redirect()->route('fuelTypes.index')->with('success', 'Car Category created successfully.');
    }

    public function show(FuelType $FuelType)
    {
        return Inertia::render('Admin/FuelTypes/Show', ['category' => $FuelType]);
    }

    public function edit(FuelType $fuelType)
    {
        return Inertia::render('Admin/FuelTypes/Edit', ['fuelType' => $fuelType]);
    }

    public function update(Request $request, FuelType $fuelType)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('fuel_types', 'code')->ignore($fuelType->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('fuel_types', 'name')->ignore($fuelType->id), 
            ],
            'is_active' => ['required', 'boolean'],
        ]);
        $fuelType->update($validated);
        return redirect()->route('fuelTypes.index');
    }

    public function destroy(Request $request, FuelType $fuelType)
    {
        $fuelType->delete();
        return redirect()->route('fuelTypes.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        FuelType::whereIn('id', $ids)->delete();  
        return redirect()->route('fuelTypes.index');
    }
    public function getFuelTypes(Request $request)
    {
        if ($request->ajax()) {
            $data = FuelType::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
