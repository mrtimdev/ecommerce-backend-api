<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\CarsViewCount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        // $query->whereNull('client_id')->where('type', 'owner');

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

        // $brand = $request->input('brand');
        // $model = $request->input('model');
        // $category = $request->input('category');
        // $condition = $request->input('condition');
        // $fuel_type = $request->input('fuel_type');
        // $transmission_type = $request->input('transmission_type');
        // $drive_type = $request->input('drive_type');
        // $steering = $request->input('steering');
        // $color = $request->input('color');
        // $passenger = $request->input('passenger');
        // $hot_mark = $request->input('hot_mark');
        // $option = $request->input('option');

        // $modelYearFrom = $request->input('model_year_from');
        // $modelYearTo = $request->input('model_year_to');
        // $engineVolumeFrom = $request->input('engine_volume_from');
        // $engineVolumeTo = $request->input('engine_volume_to');
        // $minPrice = $request->input('min_price');
        // $maxPrice = $request->input('max_price');


        # follow autowini
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

        return CarDetailResource::collection($cars);
    }

    public function search(Request $request) {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);
        $filter = $request->input('filter');

        // Validate sort field
        if (!in_array($sortField, ['id', 'code', 'name', 'plate_number', 'current_price', 'previous_price', 'year', 'mileage', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field. Available fields: [id, code, name, plate_number, current_price, previous_price, year, mileage, is_active]'], 400);
        }

        // Validate sort order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }

        $query = Car::query();
        // $query->whereNull('client_id')->where('type', 'owner');
        if ($filter) {
            $query->where(function($q) use ($filter) {
                $q->where('code', 'like', "%{$filter}%")
                    ->orWhere('slug', 'like', "%{$filter}%")
                    ->orWhere('name', 'like', "%{$filter}%")
                    ->orWhere('year', 'like', "%{$filter}%")
                    ->orWhere('plate_number', 'like', "%{$filter}%")
                    ->orWhere('description', 'like', "%{$filter}%")

                    ->orWhereHas('location', function($q) use ($filter) {
                        $q->where('name', 'like', "%{$filter}%");
                    })
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

    public function updateView(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $car = Car::where('id', $request->id)->first();
        if($car) {
            $car->increment('view_count', 1);
            return response()->json([
                'count' => $car->view_count,
                'message' => 'View count updated.',
            ], 200);
        }
        return response()->json([
            'message' => 'Item not found.',
        ], 404);
    }

    public function getView(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $car = Car::where('id', $request->id)->first();
        if($car) {
            return response()->json([
                'count' => $car->view_count,
            ], 200);
        }
        return response()->json([
            'message' => 'Item not found.',
        ], 404);
    }

    public function updateLike(Request $request)
    {
        // Validate the incoming request to ensure 'car_id' is provided
        $request->validate([
            'car_id' => 'required|numeric',
        ]);

        // Get the authenticated user
        $user = Auth::guard('api')->user();

        // Find the car by ID
        $car = Car::where('id', $request->car_id)->first();

        if ($car) {
            // Check if the user has already liked the car
            $likeCount = $car->likeCounts()->where('user_id', $user->id)->first();

            if (!$likeCount) {
                // If the user has not liked the car yet, create a like record with count = 1
                $car->likeCounts()->create([
                    'car_id' => $car->id,
                    'user_id' => $user->id,
                    'count' => 1, // This means the user likes the car
                ]);
            } else {
                // If the user has already liked the car, delete the like (unlike the car)
                $likeCount->delete();
            }

            // Update the car's total like count (total number of users who liked the car)
            $car->like_count = $car->likeCounts()->count();
            $car->save();

            return response()->json([
                'like_count' => $car->like_count,
                'message' => 'Like count updated.',
            ], 200);
        }

        // Return error if the car is not found
        return response()->json([
            'message' => 'Item not found.',
        ], 404);
    }
    public function getLike(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $user = Auth::guard('api')->user();
        $car = Car::where('id', $request->id)->first();
        $likeCount = $car->likeCounts()->where('user_id', $user->id)->get();
        if($car) {
            return response()->json([
                'count' => $likeCount->count(),
            ], 200);
        }
        return response()->json([
            'message' => 'Item not found.',
        ], 404);
    }

    public function getLikedCars(Request $request)
    {
        $user = Auth::guard('api')->user();
        $likedCars = $user->likedCars()->paginate($request->get('per_page', 10));
        return CarDetailResource::collection($likedCars);
    }


    public function getCarsFeatured(Request $request)
    {
        $cars = Car::where('is_featured', 1)->orderBy('featured_at', $request->input('order_by') ?? "asc")->get();
        return CarDetailResource::collection($cars);
    }
}
