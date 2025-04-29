<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('cars-categories')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return Inertia::render('Admin/Categories/Index');
    }

    public function create()
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('categories-add')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return Inertia::render('Admin/Categories/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:categories,code|regex:/^[^\s]+$/|max:100', 
            'name' => 'required|string|max:191|unique:categories,name', 
            'slug' => [
                'required',
                'string',
                'max:191',
                'unique:categories,slug',
                'regex:/^[a-zA-Z0-9-_]+$/',
            ],
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active' => ['required', 'boolean'],
            'is_save_and_more' => ['required', 'boolean']
        ]);
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('categories', 'public');
        }
        Category::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);
        if($request->is_save_and_more) {
            return redirect()->route('categories.create');
        }
        return redirect()->route('categories.index');
    }

    public function show(Category $Category)
    {
        return Inertia::render('Admin/Cars/Categories/Show', ['category' => $Category]);
    }

    public function edit(Category $Category)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('categories-edit')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        return Inertia::render('Admin/Categories/Edit', ['category' => $Category]);
    }

    public function update(Request $request, Category $category)
    {
       $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('categories', 'code')->ignore($category->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('categories', 'name')->ignore($category->id), 
            ],
            'slug' => [
                'required',
                'string',
                'max:191',
                'regex:/^[a-zA-Z0-9-_]+$/',
                Rule::unique('categories', 'slug')->ignore($category->id), 
            ],
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active' => ['required', 'boolean']
        ]);
        if ($request->hasFile('image_path')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $imagePath = $request->file('image_path')->store('categories', 'public');
        } else {
            $imagePath = $category->image_path;
        }
        $category->update([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);
        return redirect()->route('categories.index');
    }

    public function deleteSelected(Request $request)
     {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('categories-delete')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $ids = $request->input('ids');
        $categories = Category::whereIn('id', $ids)->get();
        if ($categories->isEmpty()) {
            return redirect()->route('categories.index', ['message' => 'No categories found with the provided IDs']);
        }
        foreach ($categories as $category) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
        }
        Category::whereIn('id', $ids)->delete();  
        return redirect()->route('categories.index');
    }
    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
