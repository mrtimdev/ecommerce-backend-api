<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Models/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Models/Create', [
            'brands' => Brand::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|regex:/^[^\s]+$/|unique:models,code',
            'name' => 'required|string|max:191',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => ['required', 'boolean'],
        ]);

        Model::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('models.create');
        }
        return redirect()->route('models.index')->with('success', 'Car Category created successfully.');
    }

    public function edit(Model $model)
    {
        return Inertia::render('Admin/Models/Edit', [
            'brands' => Brand::all(),
            'model' => $model,
        ]);
    }

    public function update(Request $request, Model $model)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('models', 'code')->ignore($model->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'brand_id' => 'required',
            'is_active' => ['required', 'boolean'],
        ]);

        $model->update($validated);
       
        return redirect()->route('models.index')->with('success', 'Car Model created successfully.');
    }

    public function destroy(Request $request, Model $model)
    {
        $model->delete();
        return redirect()->route('models.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        Model::whereIn('id', $ids)->delete();  
        return redirect()->route('models.index');
    }
    public function getModels(Request $request)
    {
        if ($request->ajax()) {
            $data = Model::select('models.id', 'models.code', 'models.name', 'brand_id', 'brands.name as brand_name', 'models.is_active')
                ->join('brands', 'models.brand_id', '=', 'brands.id') 
                ->orderBy('models.id', 'desc')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getModelsByBrand(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:brands,id',
        ]);
        $models = Model::whereIn('brand_id', $request->input('ids'))->get();  
        return response()->json([
            'models' => $models
        ]);
    }
}
