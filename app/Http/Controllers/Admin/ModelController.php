<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Model;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('cars-models')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return Inertia::render('Admin/Models/Index');
    }

    public function create()
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('models-add')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
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
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('models-edit')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
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

    public function deleteSelected(Request $request)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('models-delete')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
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

    public function getModelOptions(Request $request)
    {
        // Find the model with options and their groups
        $model = Model::with(['options.group'])->find($request->model_id);

        // If the model exists, group its options by group ID
        $modelOptionsWithGroups = $model ? $model->options->groupBy(fn($option) => $option->group->id ?? 'no_group')
            ->map(fn($group) => [
                'group_id' => $group->first()->group->id ?? null,
                'group_name' => $group->first()->group->name ?? 'No Group',
                'items' => $group->map(fn($option) => [
                    'id' => $option->id,
                    'name' => $option->name,
                ])->values(),
            ])->values() : collect();

        // Get all available groups and their items
        $allGroupsWithItems = OptionGroup::with('options')->get()->map(fn($group) => [
            'group_id' => $group->id,
            'group_name' => $group->name,
            'items' => $group->options->map(fn($option) => [
                'id' => $option->id,
                'name' => $option->name,
            ])->values(),
        ]);

        // Merge the model's options with all groups, ensuring no duplicates
        $optionsWithGroups = $allGroupsWithItems->map(function ($group) use ($modelOptionsWithGroups) {
            $matchingGroup = $modelOptionsWithGroups->firstWhere('group_id', $group['group_id']);
            return $matchingGroup ?? $group; // Use model's group if exists, otherwise all group
        })->sortBy('group_id')->values(); // Sort by group_id ascending

        // Prepare message
        $message = $optionsWithGroups->map(fn($group) => 
            "Group: {$group['group_name']} (ID: {$group['group_id']}) has " . $group['items']->count() . " items."
        )->values();

        return response()->json([
            'message' => $message,
            'group_options' => $optionsWithGroups,
        ]);
    }


}
