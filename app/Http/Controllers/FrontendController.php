<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\HomePageSlider;
use Yajra\DataTables\Facades\DataTables;
use Storage;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = HomePageSlider::all();

        return Inertia::render('Admin/Frontend/HomePage/Sliders/Index', [
            'sliders' => $sliders,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|image',
            'is_active' => 'required|boolean'
        ]);

        // Handle file upload
        $imagePath = $request->file('image_path')->store('homepagesliders', 'public');

        HomePageSlider::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.homepage.sliders.index');
    }

    public function update(Request $request, HomePageSlider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image', 
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            $imagePath = $request->file('image_path')->store('homepagesliders', 'public');
        } else {
            $imagePath = $slider->image_path;
        }

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.homepage.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    public function destroy(Request $request, HomePageSlider $slider)
    {
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }
        $slider->delete();
        return redirect()->route('frontend.homepage.sliders.index');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->ids;
        $sliders = HomePageSlider::whereIn('id', $ids)->get();
        if ($sliders->isEmpty()) {
            return response()->json(['message' => 'No sliders found with the provided IDs'], 404);
        }
        foreach ($sliders as $slider) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
        }
        HomePageSlider::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.homepage.sliders.index');
    }

    public function getHomePageSliders(Request $request)
    {
        if ($request->ajax()) {
            $data = HomePageSlider::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
