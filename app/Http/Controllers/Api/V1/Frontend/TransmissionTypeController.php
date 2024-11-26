<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use Illuminate\Http\Request;
use App\Models\TransmissionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\TransmissionTypeCollection;

class TransmissionTypeController extends Controller
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
        $query = TransmissionType::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $categories = $query->orderBy($sortField, $sortOrder)->get();

        return new TransmissionTypeCollection($categories);
    }
}
