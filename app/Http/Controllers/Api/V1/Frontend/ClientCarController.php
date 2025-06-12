<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Frontend\ClientStoreCarRequest;
use App\Http\Resources\Frontend\CarGalleryCollection;
use App\Http\Requests\Frontend\ClientCarUpdateRequest;
use App\Http\Resources\Frontend\ClientCarDetailResource;

class ClientCarController extends Controller
{



    public function createCar(ClientStoreCarRequest $request)
    {

        $client = Auth::guard('api')->user();
        $discount = $request->discount;
        $request->total_price = apply_discount($request->price, $discount);


        $car = Car::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'car_price' => $request->total_price,
            'discount' => $request->discount,
            'total_price' => $request->total_price,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,

            'is_active' => 1,
            'engine_volume' => $request->engine_volume,
            'door' => $request->door,
            'cylinder' => $request->cylinder,
            'water_flood_damaged' => $request->water_flood_damaged,
            'former_rental_car' => $request->former_rental_car,
            'former_taxi' => $request->former_taxi,
            'recovered_theft' => $request->recovered_theft,
            'police_car' => $request->police_car,
            'salvage_record' => $request->salvage_record,
            'fuel_conversion' => $request->fuel_conversion,
            'modified_seats' => $request->modified_seats,
            'size' => $request->size,

            'category_id' => $request->input('category_id'),
            'condition_id' => $request->input('condition_id'),
            'brand_id' => $request->input('brand_id'),
            'model_id' => $request->input('model_id'),
            'fuel_type_id' => $request->input('fuel_type_id'),
            'transmission_type_id' => $request->input('transmission_type_id'),
            'color_id' => $request->input('color_id'),
            'passenger_id' => $request->input('passenger_id'),
            'steering_id' => $request->input('steering_id'),
            'drive_type_id' => $request->input('drive_type_id'),
            'location_id' => $request->input('location_id'),
            'status' => 'available',

            'youtube_link' => $request->youtube_link,
            'created_by' => $client->id,
            'client_id' => $client->id,
            'type' => "client",
            'featured_image' => null,
            'is_featured' => 0,
        ]);

        // if ($request->has('hot_marks')) {
        //     $car->hot_marks()->attach(collect($request->input('hot_marks'))->pluck('id'));
        // }

        // if ($request->has('options')) {
        //     $car->options()->attach(collect($request->input('options'))->pluck('id'));
        // }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('cars/client/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Car created successfully',
        ], 201);
    }

    public function updateCar(ClientCarUpdateRequest $request, Car $car)
    {

        $client = Auth::guard('api')->user();
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cars', 'code')->ignore($car->id),
            ],
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cars', 'slug')->ignore($car->id),
            ],
            'price' => 'required|numeric',
            'discount' => 'nullable|string',
        ]);
        $discount = $request->discount;
        $request->total_price = apply_discount($request->price, $discount);


        $car->update([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'car_price' => $request->total_price,
            'discount' => $request->discount,
            'total_price' => $request->total_price,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,

            'is_active' => 1,
            'engine_volume' => $request->engine_volume,
            'door' => $request->door,
            'cylinder' => $request->cylinder,
            'water_flood_damaged' => $request->water_flood_damaged,
            'former_rental_car' => $request->former_rental_car,
            'former_taxi' => $request->former_taxi,
            'recovered_theft' => $request->recovered_theft,
            'police_car' => $request->police_car,
            'salvage_record' => $request->salvage_record,
            'fuel_conversion' => $request->fuel_conversion,
            'modified_seats' => $request->modified_seats,
            'size' => $request->size,

            'category_id' => $request->input('category_id'),
            'condition_id' => $request->input('condition_id'),
            'brand_id' => $request->input('brand_id'),
            'model_id' => $request->input('model_id'),
            'fuel_type_id' => $request->input('fuel_type_id'),
            'transmission_type_id' => $request->input('transmission_type_id'),
            'color_id' => $request->input('color_id'),
            'passenger_id' => $request->input('passenger_id'),
            'steering_id' => $request->input('steering_id'),
            'drive_type_id' => $request->input('drive_type_id'),
            'location_id' => $request->input('location_id'),
            'status' => 'available',

            'youtube_link' => $request->youtube_link,
            'created_by' => $client->id,
            'client_id' => $client->id,
            'type' => "client",
            'featured_image' => null,
            'is_featured' => 0,
        ]);

        // if ($request->has('hot_marks')) {
        //     $car->hot_marks()->attach(collect($request->input('hot_marks'))->pluck('id'));
        // }

        // if ($request->has('options')) {
        //     $car->options()->attach(collect($request->input('options'))->pluck('id'));
        // }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('cars/client/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
        ], 201);
    }



    public function myCars(Request $request) {
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
        $query->whereNotNull('client_id')->where('type', 'client');

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
                    ->orWhereHas('transmissionType', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
                    ->orWhereHas('driveType', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
                    ->orWhereHas('steering', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
                    ->orWhereHas('passenger', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
                    ->orWhereHas('hot_marks', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
                    ->orWhereHas('options', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })

                    ->orWhereHas('color', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    });
            });
        }

        $brand = $request->input('make');
        $model = $request->input('subModel');
        $category = $request->input('category');
        $condition = $request->input('condition');
        $fuel_type = $request->input('fuelType');
        $transmission_type = $request->input('transmission');
        $drive_type = $request->input('driveType');
        $steering = $request->input('steering');
        $color = $request->input('color');
        $passenger = $request->input('numberOfPassenger');
        $hot_mark = $request->input('hotmark');
        $option = $request->input('carOption');

        $modelYearFrom = $request->input('modelYearFrom');
        $modelYearTo = $request->input('modelYearTo');
        $engineVolumeFrom = $request->input('engineVolumeFrom');
        $engineVolumeTo = $request->input('engineVolumeTo');
        $minPrice = $request->input('priceFrom');
        $maxPrice = $request->input('priceTo');

        $location = $request->input('location');

        $perPage = $request->input('pageSize', 10);

        if ($location) {
            $query->whereHas('location', function($q) use ($location) {
                $q->where('code', '=', $location);
            });
        }

        if ($brand) {
            $query->whereHas('brand', function($q) use ($brand) {
                $q->where('code', '=', $brand);
            });
        }

        if ($model) {
            $query->whereHas('model', function($q) use ($model) {
                $q->where('code', '=', $model);
            });
        }

        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('code', '=', $category);
            });
        }
        if ($condition) {
            $query->whereHas('condition', function($q) use ($condition) {
                $q->where('code', '=', $condition);
            });
        }
        if ($fuel_type) {
            $query->whereHas('fuel_type', function($q) use ($fuel_type) {
                $q->where('code', '=', $fuel_type);
            });
        }
        if ($fuel_type) {
            $query->whereHas('fuel_type', function($q) use ($fuel_type) {
                $q->where('code', '=', $fuel_type);
            });
        }
        if ($transmission_type) {
            $query->whereHas('transmission_type', function($q) use ($transmission_type) {
                $q->where('code', '=', $transmission_type);
            });
        }
        if ($drive_type) {
            $query->whereHas('drive_type', function($q) use ($drive_type) {
                $q->where('code', '=', $drive_type);
            });
        }
        if ($steering) {
            $query->whereHas('steering', function($q) use ($steering) {
                $q->where('code', '=', $steering);
            });
        }
        if ($color) {
            $query->whereHas('color', function($q) use ($color) {
                $q->where('code', '=', $color);
            });
        }
        if ($passenger) {
            $query->whereHas('passenger', function($q) use ($passenger) {
                $q->where('no', '=', $passenger);
            });
        }
        if ($hot_mark) {
            $query->orWhereHas('hot_marks', function($q) use ($hot_mark) {
                $q->where('code', '=', $hot_mark);
            });
        }
        if ($option) {
            $query->orWhereHas('options', function($q) use ($option) {
                $q->where('code', '=', $option);
            });
        }



        if ($modelYearFrom) {
            $query->where('year', '>=', $modelYearFrom);
        }
        if ($modelYearTo) {
            $query->where('year', '<=', $modelYearTo);
        }

        if ($engineVolumeFrom) {
            $query->where('engine_volume', '>=', $engineVolumeFrom);
        }
        if ($engineVolumeTo) {
            $query->where('engine_volume', '<=', $engineVolumeTo);
        }

        if ($minPrice) {
            $query->where('total_price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('total_price', '<=', $maxPrice);
        }
        // Order and paginate the results
        $cars = $query->orderBy($sortField, $sortOrder)->paginate($perPage);

        return ClientCarDetailResource::collection($cars);
    }



    public function getGalleries(Request $request, Car $car)
    {
        if($car) {
            return new CarGalleryCollection($car->images);
        }
    }

    public function removeGallery(Request $request, CarImage $carImage)
    {
        if ($carImage) {
            Storage::disk('public')->delete($carImage->image_path);
            $carImage->delete();
            return response()->json([
                'success' => true
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
                $galleryPath = $image->store('cars/client/gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
            return response()->json([
                'success' => true
            ]);
        }
    }
}
