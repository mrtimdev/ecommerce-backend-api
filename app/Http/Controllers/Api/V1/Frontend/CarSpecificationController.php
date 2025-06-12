<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Model;
use App\Models\Steering;
use App\Models\DriveType;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarSpecificationController extends Controller
{


    public function getDriveTypes(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortField, ['id', 'name', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field, available fields: [id, name, is_active]'], 400);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $isActive = $request->input('is_active');
        $query = DriveType::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $data = $query->orderBy($sortField, $sortOrder)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function getSteerings(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $query = Steering::query();

        $data = $query->orderBy($sortField, $sortOrder)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function getPassengers(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');


        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $query = Passenger::query();

        $data = $query->orderBy($sortField, $sortOrder)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getModelsByBrand(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|integer|exists:brands,id',
        ]);
        $models = Model::where('brand_id', $request->input('brand_id'))->get();
        return response()->json([
            'models' => $models
        ]);
    }
}
