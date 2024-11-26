<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\V1\Frontend\CarController;
use App\Http\Controllers\Api\V1\Frontend\UserController;
use App\Http\Controllers\Api\V1\Frontend\BrandController;
use App\Http\Controllers\Api\V1\Frontend\ColorController;
use App\Http\Controllers\Api\V1\Frontend\ModelController;
use App\Http\Controllers\Api\V1\Frontend\CategoryController;
use App\Http\Controllers\Api\V1\Frontend\FrontendController;
use App\Http\Controllers\Api\V1\Frontend\FuelTypeController;
use App\Http\Controllers\Api\V1\Frontend\ConditionController;
use App\Http\Controllers\Api\V1\Frontend\CustomerOrderController;
use App\Http\Controllers\Api\V1\Frontend\TransmissionTypeController;
use App\Http\Controllers\Api\V1\Frontend\SettingController;





# auth route
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/profile', [UserController::class, 'profile']);
        // Route::post('/password', UpdatePasswordController::class);
        Route::post('/avatar', [UserController::class, 'update_avatar']);
        Route::post('/logout', [UserController::class, 'destroy']);
    });

});

# frontend routes

Route::prefix('')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}/get-id', [CategoryController::class, 'getCategoryById']);
    Route::get('/categories/{slug}/get-slug', [CategoryController::class, 'getCategoryBySlug']);

    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand}/get-id', [BrandController::class, 'getBrandById']);
    Route::get('/brands/{slug}/get-slug', [BrandController::class, 'getBrandBySlug']);

    Route::get('/conditions', [ConditionController::class, 'index']);
    Route::get('/fuel_types', [FuelTypeController::class, 'index']);
    Route::get('/transmission_types', [TransmissionTypeController::class, 'index']);
    Route::get('/models', [ModelController::class, 'index']);


    # Cars api
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{id}/get-id', [CarController::class, 'getCarById']);
    Route::get('/cars/{slug}/get-slug', [CarController::class, 'getCarBySlug']);
    Route::get('/cars/{id}/related', [CarController::class, 'getRelated']);

    Route::get('/colors', [ColorController::class, 'index']);
    Route::get('/colors/{id}/get-id', [ColorController::class, 'getColorById']);
});
# home page routes
Route::prefix('pages')->group(function () {
    Route::get('/menus', [FrontendController::class, 'getMenus']);
    
    Route::get('/sliders', [FrontendController::class, 'getHomePageSliders']);
    Route::get('/contact-us', [FrontendController::class, 'getContactUs']);

    Route::get('/stories', [FrontendController::class, 'getStories']);
    Route::get('/stories/{id}', [FrontendController::class, 'getStoryById']);
    Route::get('/videos', [FrontendController::class, 'getVideos']);
    Route::get('/videos/{id}', [FrontendController::class, 'getVideoById']);

    Route::get('/services', [FrontendController::class, 'getServices']);
    Route::get('/communities', [FrontendController::class, 'getCommunities']);
    Route::get('/guarantees', [FrontendController::class, 'getGuarantees']);
});
# filters menu
Route::get('/menu-filters', [FrontendController::class, 'getMenuFilters']);

# customer ordering 
// Route::apiResource('customer-orders', CustomerOrderController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/customer/orders/store', [CustomerOrderController::class, 'store']);
    Route::get('/customer-orders', [CustomerOrderController::class, 'index']);
    Route::get('/customer-orders/{customer_order}', [CustomerOrderController::class, 'show']);
    Route::get('/customer-orders/by-email/{email}', [CustomerOrderController::class, 'showByEmail']);
    Route::get('/customer-orders/by-phone/{phone}', [CustomerOrderController::class, 'showByPhone']);
    Route::put('/customer-orders/{customer_order}', [CustomerOrderController::class, 'update']);
    Route::delete('/customer-orders/{customer_order}', [CustomerOrderController::class, 'destroy']);
});
Route::delete('/customer-orders/truncate/data', [CustomerOrderController::class, 'truncateOrders']);

Route::get('/setting', [SettingController::class, 'index']);
Route::get('/countries', [SettingController::class, 'countries']);

# generate user demo
Route::get('/generate-user-demo', function() {
    $cmd = Artisan::call('user-demo');
    return response()->json([
        'status' => 'success',
        'cmd' => $cmd,
        'message' => 'You were successfully generated.!',
    ], 201);
});
