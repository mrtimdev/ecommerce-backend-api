<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use Illuminate\Http\Request;
use App\Models\HomePageSlider;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\HomePage\SliderCollection;

class FrontendController extends Controller
{
    public function getHomePageSliders(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortField, ['id', 'title', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field, available fields: [id, title, is_active]'], 400);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $isActive = $request->input('is_active');
        $query = HomePageSlider::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $sliders = $query->orderBy($sortField, $sortOrder)->get();

        return new SliderCollection($sliders);
    }

}
