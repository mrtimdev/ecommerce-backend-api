<?php

namespace App\Http\Controllers\Car;

use Inertia\Inertia;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Cars/Categories/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Cars/Categories/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        CarCategory::create($validated);

        return redirect()->route('cars.categories.index')->with('success', 'Car Category created successfully.');
    }

    public function show(CarCategory $carCategory)
    {
        return Inertia::render('Admin/Cars/Categories/Show', ['category' => $carCategory]);
    }

    public function edit(CarCategory $carCategory)
    {
        return Inertia::render('Admin/Cars/Categories/Edit', ['category' => $carCategory]);
    }

    public function update(Request $request, CarCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        $category->update($validated);
        return redirect()->route('cars.categories.index');
    }

    public function destroy(Request $request, CarCategory $category)
    {
        $category->delete();
        return redirect()->route('cars.categories.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        CarCategory::whereIn('id', $ids)->delete();  
        return redirect()->route('cars.categories.index');
    }
    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = CarCategory::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
