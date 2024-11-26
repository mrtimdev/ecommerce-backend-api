<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ColorResource;
use App\Http\Resources\Frontend\ColorCollection;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return new ColorCollection($colors);
    }
    public function getColorById(Request $request, $id)
    {
        $color = Color::where('id', $id)->first();
        if($color) {
            return new ColorResource($color);
        }
        return response()->json([
            'data' => null,
            'message' => 'Item not found.',
        ], 404);
    }
}
