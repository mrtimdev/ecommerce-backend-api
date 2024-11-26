<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Brands/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Brands/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:brands,code|regex:/^[^\s]+$/|max:100', 
            'name' => 'required|string|max:191|unique:brands,name', 
            'slug' => [
                'required',
                'string',
                'max:191',
                'unique:brands,slug',
                'regex:/^[a-zA-Z0-9-_]+$/',
            ],
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active' => ['required', 'boolean'],
            'is_save_and_more' => ['required', 'boolean']
        ]);
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('brands', 'public');
        }
        Brand::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);
        if($request->is_save_and_more) {
            return redirect()->route('brands.create');
        }
        return redirect()->route('brands.index');
    }

    public function show(Brand $Brand)
    {
        return Inertia::render('Admin/Brands/Show', ['brand' => $Brand]);
    }

    public function edit(Brand $Brand)
    {
        return Inertia::render('Admin/Brands/Edit', ['brand' => $Brand]);
    }

    public function update(Request $request, Brand $brand)
    {
       $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('brands', 'code')->ignore($brand->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('brands', 'name')->ignore($brand->id), 
            ],
            'slug' => [
                'required',
                'string',
                'max:191',
                'regex:/^[a-zA-Z0-9-_]+$/',
                Rule::unique('brands', 'slug')->ignore($brand->id), 
            ],
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active' => ['required', 'boolean']
        ]);
        if ($request->hasFile('image_path')) {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
            }
            $imagePath = $request->file('image_path')->store('brands', 'public');
        } else {
            $imagePath = $brand->image_path;
        }
        $brand->update([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);
        return redirect()->route('brands.index');
    }

    public function destroy(Request $request, Brand $brand)
    {
        if ($brand->image_path) {
            Storage::disk('public')->delete($brand->image_path);
        }
        $brand->delete();
        return redirect()->route('brands.index');
    }
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');
        $brands = Brand::whereIn('id', $ids)->get();

        if ($brands->isEmpty()) {
            return redirect()->route('brands.index')->with('message', 'No brands found with the provided IDs');
        }

        // Check for associated cars
        $brandsWithCars = $brands->filter(function($brand) {
            return $brand->cars()->exists(); // Assuming 'cars' is the relationship method
        });

        if ($brandsWithCars->isNotEmpty()) {
            return response()->json([
                'error' => 'Cannot delete brands with associated cars: ' . $brandsWithCars->pluck('name')->implode(', ')
            ], 401);
            return redirect()->route('brands.index')->with('error', 'Cannot delete brands with associated cars: ' . $brandsWithCars->pluck('id')->implode(', '));
        }

        // Delete image files if they exist
        foreach ($brands as $brand) {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
            }
        }

        // Proceed to delete the brands
        Brand::whereIn('id', $ids)->delete();  
        return redirect()->route('brands.index')->with('success', 'Brands deleted successfully.');
    }

    public function getBrands(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::orderBy('id', 'desc')->get();
            return response()->json([
                'brands' => $data
            ]);
        }
    }
    public function getListBrands(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
