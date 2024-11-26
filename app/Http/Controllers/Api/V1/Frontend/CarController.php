<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CarResource;
use App\Http\Resources\Frontend\CarCollection;
use App\Http\Resources\Frontend\CarDetailResource;

class CarController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);
        $filter = $request->input('filter'); // Get the filter input
    
        // Validate sort field
        if (!in_array($sortField, ['id', 'code', 'name', 'plate_number', 'current_price', 'previous_price', 'year', 'mileage', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field. Available fields: [id, code, name, plate_number, current_price, previous_price, year, mileage, is_active]'], 400);
        }
    
        // Validate sort order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
    
        // Query the cars
        $query = Car::query();
    
        // Apply filter if provided
        if ($filter) {
            $query->where(function($q) use ($filter) {
                $q->where('code', 'like', "%{$filter}%")
                  ->orWhere('slug', 'like', "%{$filter}%")
                  ->orWhere('name', 'like', "%{$filter}%")
                  ->orWhere('year', 'like', "%{$filter}%")
                  ->orWhere('plate_number', 'like', "%{$filter}%")
                  ->orWhere('description', 'like', "%{$filter}%")
                  ->orWhereHas('brand', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  })
                  ->orWhereHas('model', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  })
                  ->orWhereHas('category', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  })
                  ->orWhereHas('condition', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  })
                  ->orWhereHas('fuelType', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  })
                  ->orWhereHas('color', function($q) use ($filter) {
                      $q->where('name', 'like', "%{$filter}%");
                  });
            });
        }
    
        // Order and paginate the results
        $cars = $query->orderBy($sortField, $sortOrder)->paginate($perPage);
    
        return CarDetailResource::collection($cars);
    }

    public function getRelated(Request $request, $id) {
        $car = Car::find($id);
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);
        $model = $request->query('model');
        $brand = $request->query('brand');
        $category = $request->query('category');
        if (!$car) {
            return response()->json([
                'data' => null,
                'message' => 'Item not found.',
            ], 404);
        }
        
        $query = Car::where('id', '!=', $car->id);
        
        if ($model) {
            $query->whereHas('model', function ($q) use ($model) {
                $q->where('name', 'like', "%{$model}%");
            });
        }
        if ($brand) {
            $query->whereHas('brand', function ($q) use ($brand) {
                $q->where('name', 'like', "%{$brand}%");
            });
        }
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }
        $cars = $query->orderBy($sortField, $sortOrder)->paginate($perPage);
    
        return CarDetailResource::collection($cars);
    }

    public function getCarById(Request $request, $id)
    {
        $car = Car::where('id', $id)->first();
        if($car) {
            return new CarDetailResource($car);
        }
        return response()->json([
            'data' => null,
            'message' => 'Item not found.',
        ], 404);
    }

    public function getCarBySlug($slug)
    {
        $car = Car::where('slug', $slug)->first();
        if($car) {
            return new CarDetailResource($car);
        }
        return response()->json([
            'data' => null,
            'message' => 'Item not found.',
        ], 404);
        
    }
}
