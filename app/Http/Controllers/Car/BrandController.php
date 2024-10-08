<?php

namespace App\Http\Controllers\Car;

use Inertia\Inertia;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Cars/Brands/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Cars/Brands/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:car_brands,name',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        CarBrand::create($validated);

        return redirect()->route('cars.brands.index')->with('success', 'Car Category created successfully.');
    }

    public function show(CarBrand $carBrand)
    {
        return Inertia::render('Admin/Cars/Brands/Show', ['category' => $carBrand]);
    }

    public function edit(CarBrand $carBrand)
    {
        return Inertia::render('Admin/Cars/Brands/Edit', ['category' => $carBrand]);
    }

    public function update(Request $request, CarBrand $brand)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_brands', 'name')->ignore($brand->id), 
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
        $brand->update($validated);
        return redirect()->route('cars.brands.index');
    }

    public function destroy(Request $request, CarBrand $brand)
    {
        $brand->delete();
        return redirect()->route('cars.brands.index');
    }
    public function deleteSelected(Request $request)
    {  
        $ids = $request->input('ids');
        CarBrand::whereIn('id', $ids)->delete();  
        return redirect()->route('cars.brands.index');
    }
    public function getBrands(Request $request)
    {
        if ($request->ajax()) {
            $data = CarBrand::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
