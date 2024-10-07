<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\CarCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\Car\CategoryCollection;

class CarCategoryController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'id'); 
        $sortOrder = $request->input('sort_order', 'asc');

        // Validate sort parameters
        if (!in_array($sortField, ['id', 'name', 'status'])) {
            return response()->json(['error' => 'Invalid sort field, available fields: [id, name, status]'], 400);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }   
        $categories = CarCategory::orderBy($sortField, $sortOrder)->paginate($request->input('per_page', 0));
        return response()->json(new CategoryCollection($categories), 200);
    }
}
