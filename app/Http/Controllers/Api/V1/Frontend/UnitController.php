<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('sort_order')->get();
        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'type'         => ['required', Rule::in(['weight', 'volume', 'count'])], // adjust as needed
            'is_active'    => 'boolean',
        ]);

        $unit = Unit::create($validated);

        return response()->json([
            'message' => 'Unit created successfully.',
            'unit'    => $unit
        ], 201);
    }

    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name'         => 'sometimes|required|string|max:255',
            'abbreviation' => 'sometimes|required|string|max:50',
            'type'         => ['sometimes', 'required', Rule::in(['weight', 'volume', 'count'])],
            'is_active'    => 'boolean',
        ]);

        $unit->update($validated);

        return response()->json([
            'message' => 'Unit updated successfully.',
            'unit'    => $unit
        ]);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response()->json([
            'message' => 'Unit deleted (soft deleted) successfully.'
        ]);
    }
}
