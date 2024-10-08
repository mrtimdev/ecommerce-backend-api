<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\V1\Frontend\UserController;
use App\Http\Controllers\Api\V1\Frontend\FrontendController;
use App\Http\Controllers\Api\V1\Frontend\CarCategoryController;




# auth route
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});
Route::middleware(['auth:sanctum', 'auth.check'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/profile', [UserController::class, 'profile']);
        // Route::post('/password', UpdatePasswordController::class);
        Route::post('/avatar', [UserController::class, 'update_avatar']);
        Route::post('/logout', [UserController::class, 'destroy']);
    });

});

# frontend routes

Route::prefix('car')->group(function () {
    Route::get('/categories', [CarCategoryController::class, 'index']);
});
# home page routes
Route::prefix('home')->group(function () {
    Route::get('/sliders', [FrontendController::class, 'getHomePageSliders']);
});


# generate user demo
Route::get('/generate-user-demo', function() {
    $cmd = Artisan::call('user-demo');
    return response()->json([
        'status' => 'success',
        'cmd' => $cmd,
        'message' => 'You were successfully generated.!',
    ], 201);
});
