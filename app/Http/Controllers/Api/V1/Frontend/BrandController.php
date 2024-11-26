<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\BrandResource;
use App\Http\Resources\Frontend\BrandCollection;

class BrandController extends Controller
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
        $query = Brand::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $categories = $query->orderBy($sortField, $sortOrder)->get();

        return new BrandCollection($categories);
    }

    public function getBrandById(Request $request, Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function getBrandBySlug($slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        return new BrandResource($brand);
    }
}
