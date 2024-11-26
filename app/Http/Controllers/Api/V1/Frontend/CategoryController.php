<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CategoryResource;
use App\Http\Resources\Frontend\CategoryCollection;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortField, ['id', 'name', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field, available fields: [id, name, is_active]'], 400);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $isActive = $request->input('is_active');
        $query = Category::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $categories = $query->orderBy($sortField, $sortOrder)->get();

        return new CategoryCollection($categories);
    }

    public function getCategoryById(Request $request, Category $category)
    {
        return new CategoryResource($category);
    }

    public function getCategoryBySlug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return new CategoryResource($category);
    }

}
