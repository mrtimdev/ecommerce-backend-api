<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Model;
use App\Models\Category;
use App\Models\FuelType;
use App\Models\Condition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarRequest;

class CarController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Cars/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Cars/Create', [
            'categories' => Category::all(),
            'conditions' => Condition::all(),
            'brands' => Brand::all(),
            'models' => Model::all(),
            'fuel_types' => FuelType::all(),
        ]);
    }

    public function store(StoreCarRequest  $request)
    {
        $featured_image = null;
        if ($request->hasFile('featured_image')) {
            $featured_image = $request->file('featured_image')->store('cars/featured', 'public');
        }
   
        $car = Car::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'plate_number' => $request->plate_number,
            'current_price' => $request->current_price,
            'previous_price' => $request->previous_price,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'featured_image' => $featured_image, 
            'is_featured' => $request->is_featured ?? 1,
            'is_active' => $request->is_active ?? 1, 
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'brand_id' => $request->brand_id,
            'model_id' => $request->model_id,
            'fuel_type_id' => $request->fuel_type_id,
        ]);
        
        
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('cars/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }
        if($request->is_save_and_more) {
            return redirect()->route('cars.create');
        }
        return redirect()->route('cars.index');
    }

}
