<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Model;
use App\Models\Option;
use App\Models\HotMark;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\Steering;
use App\Models\Condition;
use App\Models\DriveType;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Models\TransmissionType;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\StoreCarRequest;
use App\Http\Resources\Frontend\CarResource;
use App\Http\Requests\Admin\UpdateCarRequest;
use App\Http\Resources\Frontend\CarDetailResource;
use App\Http\Resources\Frontend\CarGalleryResource;
use App\Http\Resources\Frontend\CarGalleryCollection;

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
            'transmission_types' => TransmissionType::all(),
            'colors' => Color::all(),
            'drive_types' => DriveType::all(),
            'passengers' => Passenger::all(),
            'steerings' => Steering::all(),
            'hot_marks' => HotMark::all(),
            'options' => Option::all(),
            'locations' => Location::all(),
        ]);
    }

    public function store(StoreCarRequest  $request)
    {
        $featured_image = null;
        if ($request->hasFile('featured_image')) {
            $featured_image = $request->file('featured_image')->store('cars/featured', 'public');
        }
        $car = Car::create([
            'sourced_link' => $request->sourced_link,
            'listing_date' => ($request->listing_date ? $request->listing_date : null),
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            // 'plate_number' => $request->plate_number,
            'total_price' => $request->total_price,
            'car_price' => $request->car_price,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,
            'is_featured' => $request->is_featured,
            'is_active' => $request->is_active,
            'featured_image' => $featured_image,
            'engine_volume' => $request->engine_volume,
            'door' => $request->door,
            'cylinder' => $request->cylinder,
            // 'engine_power' => $request->engine_power,
            // 'odometer_reading' => $request->odometer_reading,
            'water_flood_damaged' => $request->water_flood_damaged,
            'former_rental_car' => $request->former_rental_car,
            'former_taxi' => $request->former_taxi,
            'recovered_theft' => $request->recovered_theft,
            'police_car' => $request->police_car,
            'salvage_record' => $request->salvage_record,
            'fuel_conversion' => $request->fuel_conversion,
            'modified_seats' => $request->modified_seats,
            // 'first_registered_date' => $request->first_registered_date ? $request->first_registered_date : null,
            'size' => $request->size,

            'category_id' => $request->input('category.id'),
            'condition_id' => $request->input('condition.id'),
            'brand_id' => $request->input('brand.id'),
            'model_id' => $request->input('model.id'),
            'fuel_type_id' => $request->input('fuel_type.id'),
            'transmission_type_id' => $request->input('transmission_type.id'),
            'color_id' => $request->input('color.id'),
            'passenger_id' => $request->input('passenger.id'),
            'steering_id' => $request->input('steering.id'),
            'drive_type_id' => $request->input('drive_type.id'),
            'location_id' => $request->input('location.id'),
            'status' => $request->status,

            'youtube_link' => $request->youtube_link,
            'towing_export_document' => $request->towing_export_document,
            'shipping' => $request->shipping,
            'tax_import' => $request->tax_import,
            'clearance' => $request->clearance,
            'service' => $request->service,
            'first_payment' => $request->first_payment,
            'second_payment' => $request->second_payment,
            'third_payment' => $request->third_payment,
            'created_by' => Auth::id(),
        ]);

        if ($request->has('hot_marks')) {
            $car->hot_marks()->attach(collect($request->input('hot_marks'))->pluck('id'));
        }
        if ($request->has('options')) {
            $car->options()->attach(collect($request->input('options'))->pluck('id'));
        }
        
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

    public function update(UpdateCarRequest $request, Car $car)
    {
        if ($request->hasFile('featured_image')) {
            if ($car->featured_image) {
                Storage::disk('public')->delete($car->featured_image);
            }
            $car->featured_image = $request->file('featured_image')->store('cars/featured', 'public');
        }

        $car->update([
            'sourced_link' => $request->sourced_link,
            'listing_date' => ($request->listing_date ? $request->listing_date : null),
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            // 'plate_number' => $request->plate_number,
            'total_price' => $request->total_price,
            'car_price' => $request->car_price,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,
            'is_featured' => $request->is_featured,
            'is_active' => $request->is_active,
            'engine_volume' => $request->engine_volume,
            'door' => $request->door,
            'cylinder' => $request->cylinder,
            // 'engine_power' => $request->engine_power,
            // 'odometer_reading' => $request->odometer_reading,
            'water_flood_damaged' => $request->water_flood_damaged,
            'former_rental_car' => $request->former_rental_car,
            'former_taxi' => $request->former_taxi,
            'recovered_theft' => $request->recovered_theft,
            'police_car' => $request->police_car,
            'salvage_record' => $request->salvage_record,
            'fuel_conversion' => $request->fuel_conversion,
            'modified_seats' => $request->modified_seats,
            // 'first_registered_date' => $request->first_registered_date ? $request->first_registered_date : null,
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'brand_id' => $request->brand_id,
            'model_id' => $request->model_id,
            'fuel_type_id' => $request->fuel_type_id,
            'transmission_type_id' => $request->transmission_type_id,
            'color_id' => $request->color_id,
            'passenger_id' => $request->passenger_id,
            'steering_id' => $request->steering_id,
            'drive_type_id' => $request->drive_type_id,
            'size' => $request->size,

            'category_id' => $request->input('category.id'),
            'condition_id' => $request->input('condition.id'),
            'brand_id' => $request->input('brand.id'),
            'model_id' => $request->input('model.id'),
            'fuel_type_id' => $request->input('fuel_type.id'),
            'transmission_type_id' => $request->input('transmission_type.id'),
            'color_id' => $request->input('color.id'),
            'passenger_id' => $request->input('passenger.id'),
            'steering_id' => $request->input('steering.id'),
            'drive_type_id' => $request->input('drive_type.id'),
            'location_id' => $request->input('location.id'),
            'status' => $request->status,

            'youtube_link' => $request->youtube_link,
            'towing_export_document' => $request->towing_export_document,
            'shipping' => $request->shipping,
            'tax_import' => $request->tax_import,
            'clearance' => $request->clearance,
            'service' => $request->service,
            'first_payment' => $request->first_payment,
            'second_payment' => $request->second_payment,
            'third_payment' => $request->third_payment,
            'updated_by' => Auth::id(),
        ]);
        if ($request->has('hot_marks')) {
            $car->hot_marks()->sync(collect($request->input('hot_marks'))->pluck('id'));
        }
        if ($request->has('options')) {
            $car->options()->sync(collect($request->input('options'))->pluck('id'));
        }

        if ($request->hasFile('gallery_images')) {
            if ($car->images()->exists()) {
                // foreach ($car->images as $image) {
                //     Storage::disk('public')->delete($image->image_path);
                //     $image->delete(); 
                // }
            }
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('cars/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }
        return redirect()->route('cars.index');
    }

    public function edit(Request $request, Car $car)
    {
        return Inertia::render('Admin/Cars/Edit', [
            'car' => $car,
            'categories' => Category::all(),
            'conditions' => Condition::all(),
            'brands' => Brand::all(),
            'models' => Model::all(),
            'fuel_types' => FuelType::all(),
            'transmission_types' => TransmissionType::all(),
            'colors' => Color::all(),
            'drive_types' => DriveType::all(),
            'passengers' => Passenger::all(),
            'steerings' => Steering::all(),
            'hot_marks' => HotMark::all(),
            'options' => Option::all(),
            'locations' => Location::all(),

            'category' => $car->category,
            'condition' => $car->condition,
            'brand' => $car->brand,
            'model' => $car->model,
            'fuel_type' => $car->fuel_type,
            'transmission_type' => $car->transmission_type,
            'color' => $car->color,
            'drive_type' => $car->drive_type,
            'passenger' => $car->passenger,
            'steering' => $car->steering,
            'car_hot_marks' => $car->hot_marks,
            'car_options' => $car->options,
            'location' => $car->location,
        ]);
    }

    public function show(Request $request, Car $car)
    {
        if($car) {
            return new CarDetailResource($car);
        }
    }
    public function getGalleries(Request $request, Car $car)
    {
        if($car) {
            return new CarGalleryCollection($car->images);
        }
    }

    public function destroy(Request $request, Car $car)
    {
        if ($car->featured_image) {
            Storage::disk('public')->delete($car->featured_image);
        }
    
        return redirect()->route('cars.index');
    }
    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        $cars = Car::whereIn('id', $ids)->get();
        if ($cars->isEmpty()) {
            return redirect()->route('cars.index', ['message' => 'No cars found with the provided IDs']);
        }
        foreach ($cars as $car) {
            if ($car->featured_image) {
                Storage::disk('public')->delete($car->featured_image);
            }

            if ($car->images()->exists()) {
                foreach ($car->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete(); 
                }
            }

            $car->hot_marks()->detach();
            $car->options()->detach();
        }
        Car::whereIn('id', $ids)->delete();  
        return redirect()->route('cars.index');
    }

    public function getCars(Request $request)
    {
        if ($request->ajax()) {
            $data = Car::orderBy('id', 'desc')->get();
            return response()->json([
                'cars' => $data
            ]);
        }
    }
    public function getListCars(Request $request)
    {
        return DataTables::of(Car::with(['category', 'condition', 'brand', 'model', 'fuelType', 'images'])->select([
            'id',
            'listing_date',
            'code',
            'name',
            'plate_number',
            'total_price',
            'car_price',
            'year',
            'mileage',
            'is_featured',
            'featured_image',
            'is_active',
            'category_id',
            'condition_id',
            'brand_id',
            'model_id',
            'fuel_type_id',
            'status',
            'towing_export_document',
            'shipping',
            'tax_import',
            'clearance',
            'service'
        ]))
        ->addIndexColumn()
        ->addColumn('total_price', fn($row) => $row->total_price)
        ->addColumn('category_name', fn($row) => $row->category->name ?? 'N/A')
        ->addColumn('condition_name', fn($row) => $row->condition->name ?? 'N/A')
        ->addColumn('brand_name', fn($row) => $row->brand->name ?? 'N/A')
        ->addColumn('model_name', fn($row) => $row->model->name ?? 'N/A')
        ->addColumn('fuel_type_name', fn($row) => $row->fuelType->name ?? 'N/A')
        ->addColumn('image_path', fn($row) => $row->images[0]->image_path ?? null)
        ->make(true);
    }

    public function removeGallery(Request $request, CarImage $carImage)
    {
        if ($carImage) {
            Storage::disk('public')->delete($carImage->image_path);
            $carImage->delete(); 
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
    public function updateGallery(Request $request, Car $car)
    {
        $request->validate([
            'gallery_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('gallery_images')) {
            if ($car->images()->exists()) {
                // foreach ($car->images as $image) {
                //     Storage::disk('public')->delete($image->image_path);
                //     $image->delete(); 
                // }
            }
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('cars/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
    public function updateFeaturedImage(Request $request, Car $car)
    {
        $request->validate([
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('featured_image')) {
            if ($car->featured_image) {
                Storage::disk('public')->delete($car->featured_image);
            }
            $car->featured_image = $request->file('featured_image')->store('cars/featured', 'public');
            $car->save();
            return response()->json([
                'status' => 'success'
            ]);
        }
    }



}
