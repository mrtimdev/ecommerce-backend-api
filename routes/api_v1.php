<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\V1\Frontend\CarController;
use App\Http\Controllers\Api\V1\Frontend\UnitController;
use App\Http\Controllers\Api\V1\Frontend\UserController;
use App\Http\Controllers\Api\V1\Frontend\BrandController;
use App\Http\Controllers\Api\V1\Frontend\ColorController;
use App\Http\Controllers\Api\V1\Frontend\ModelController;
use App\Http\Controllers\Api\V1\Frontend\PackageController;
use App\Http\Controllers\Api\V1\Frontend\ProductController;
use App\Http\Controllers\Api\V1\Frontend\SettingController;
use App\Http\Controllers\Api\V1\Frontend\CategoryController;
use App\Http\Controllers\Api\V1\Frontend\CustomerController;
use App\Http\Controllers\Api\V1\Frontend\FrontendController;
use App\Http\Controllers\Api\V1\Frontend\FuelTypeController;
use App\Http\Controllers\Api\V1\Frontend\ClientCarController;
use App\Http\Controllers\Api\V1\Frontend\ConditionController;
use App\Http\Controllers\Api\V1\Frontend\CustomerOrderController;
use App\Http\Controllers\Api\V1\Frontend\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Frontend\CarSpecificationController;
use App\Http\Controllers\Api\V1\Frontend\TransmissionTypeController;
use App\Http\Controllers\Api\V1\Frontend\Auth\OtpVerificationController;
use App\Http\Controllers\Api\V1\Frontend\Auth\EmailVerificationNotificationController;


# testing email.verified
Route::middleware('auth:api')->prefix('auth')->group(function () {
    // Show the email verification notice
    Route::get('/email/verify', [VerifyEmailController::class, 'showVerificationPage'])->name('verification.notice');

    // Verify the email address
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend the email verification notification
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification link sent.']);
})->middleware(['auth:api']);

# end testing email.verified


# auth route
Route::group(['prefix' => 'user'], function () {
    Route::post('/sign-up', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    #email
    Route::post('/signup/verify-code', [OtpVerificationController::class, 'registerVerifyCode']); #  verify when register
    Route::post('/new-password/verify-code', [OtpVerificationController::class, 'newPasswordVerifyCode']);

    Route::post('/new-code/resend-code', [OtpVerificationController::class, 'resendCode']);
    Route::post('/new-email/resend-code', [OtpVerificationController::class, 'resendCodeForNewEmail']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/profile', [UserController::class, 'profile']);

        Route::post('/update-info', [UserController::class, 'updateInfo']);
        Route::post('/change-email', [UserController::class, 'updateEmail']);
        Route::post('/username-update', [UserController::class, 'updateUsername']);
        Route::post('/verify-code/new-email', [OtpVerificationController::class, 'newEmailVerifyCode']); # verify when new email update
        Route::post('/update-cover', [UserController::class, 'updateCover']);
        Route::post('/remove-cover', [UserController::class, 'removeCover']);
        Route::post('/update-avatar', [UserController::class, 'updateAvatar']);
        Route::post('/remove-avatar', [UserController::class, 'removeAvatar']);
        Route::post('/logout', [UserController::class, 'destroy']);
    });

});

# frontend routes
Route::middleware(['auth:api'])->prefix('categories')
    ->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/{category}/show', [CategoryController::class, 'show']);
        Route::post('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);

});
Route::middleware(['auth:api'])->prefix('units')
    ->group(function () {
        Route::get('', [UnitController::class, 'index']);
        Route::post('/store', [UnitController::class, 'store']);
        Route::get('/{unit}/show', [UnitController::class, 'show']);
        Route::post('/{unit}/update', [UnitController::class, 'update']);
        Route::delete('/{unit}/delete', [UnitController::class, 'destroy']);

});



Route::middleware(['auth:api'])->group(function () {
    // Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);


    Route::get('products', [ProductController::class, 'index'])->name('products.index');

    // POST /ftd/api/v1/products → create a product
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    // GET /ftd/api/v1/products/{product} → get one product
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // PUT or PATCH /ftd/api/v1/products/{product} → update product
    Route::post('products/{product}', [ProductController::class, 'update'])->name('products.update');

    // DELETE /ftd/api/v1/products/{product} → delete product
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // packages for clients
    Route::get('/packages', [PackageController::class, 'index']);             // List all packages for the authenticated client
    Route::post('/packages', [PackageController::class, 'store']);            // Create a new package
    Route::get('/packages/{package}', [PackageController::class, 'show']);    // Show single package
    Route::post('/packages/{package}', [PackageController::class, 'update']);  // Update existing package
    Route::delete('/packages/{package}', [PackageController::class, 'destroy']); // Delete package
});

Route::prefix('')->group(function () {


    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand}/get-id', [BrandController::class, 'getBrandById']);
    Route::get('/brands/{slug}/get-slug', [BrandController::class, 'getBrandBySlug']);

    Route::get('/conditions', [ConditionController::class, 'index']);
    Route::get('/fuel_types', [FuelTypeController::class, 'index']);
    Route::get('/transmission_types', [TransmissionTypeController::class, 'index']);
    Route::get('/models', [ModelController::class, 'index']);
    Route::get('/drive_types', [CarSpecificationController::class, 'getDriveTypes']);
    Route::get('/steerings', [CarSpecificationController::class, 'getSteerings']);
    Route::get('/passengers', [CarSpecificationController::class, 'getPassengers']);
    Route::get('/models/by-brands', [CarSpecificationController::class, 'getModelsByBrand']);


    # Cars api
    Route::get('/cars', [CarController::class, 'index']);

    Route::get('/cars/{id}/get-id', [CarController::class, 'getCarById']);
    Route::get('/cars/{slug}/get-slug', [CarController::class, 'getCarBySlug']);
    Route::get('/cars/{id}/related', [CarController::class, 'getRelated']);

    Route::get('/cars/get-view', [CarController::class, 'getView']);
    Route::post('/cars/update-view', [CarController::class, 'updateView']);
    Route::middleware(['auth:api'])->get('/cars/get-like', [CarController::class, 'getLike']);
    Route::middleware(['auth:api'])->post('/cars/update-like', [CarController::class, 'updateLike']);

    Route::get('/cars/liked-cars', [CarController::class, 'getLikedCars'])->middleware('auth:api');
    Route::get('/cars/featured', [CarController::class, 'getCarsFeatured']);
    # new route for search only
    Route::get('/search/cars', [CarController::class, 'search']);

    Route::get('/colors', [ColorController::class, 'index']);
    Route::get('/colors/{id}/get-id', [ColorController::class, 'getColorById']);
});
# home page routes
Route::get('/agency', [FrontendController::class, 'getAgencyContact']);
Route::prefix('pages')->group(function () {
    Route::get('/menus', [FrontendController::class, 'getMenus']);

    Route::get('/sliders', [FrontendController::class, 'getHomePageSliders']);
    Route::get('/contact-us', [FrontendController::class, 'getContactUs']);
    Route::get('/agency-contact', [FrontendController::class, 'getAgencyContact']);

    Route::get('/stories', [FrontendController::class, 'getStories']);
    Route::get('/stories/{id}', [FrontendController::class, 'getStoryById']);
    Route::get('/videos', [FrontendController::class, 'getVideos']);
    Route::get('/videos/{id}', [FrontendController::class, 'getVideoById']);

    Route::get('/services', [FrontendController::class, 'getServices']);
    Route::get('/communities', [FrontendController::class, 'getCommunities']);
    Route::get('/guarantees', [FrontendController::class, 'getGuarantees']);
});
Route::get('/menu-feature', [FrontendController::class, 'getTaxWithCarGallery']);
Route::get('/tax-info', [FrontendController::class, 'getTaxInfo']);
Route::get('/tax-info-items', [FrontendController::class, 'getTaxInfoItems']);
Route::get('/tax-info-items/{item}', [FrontendController::class, 'getTaxInfoItemByID']);
Route::get('/car-gallery', [FrontendController::class, 'getCarGallery']);
Route::get('/car-gallery-items', [FrontendController::class, 'getMenuCarGalleryItems']);
Route::get('/car-gallery-items/{item}', [FrontendController::class, 'getMenuCarGalleryItemByID']);
# filters menu
Route::get('/menu-filters', [FrontendController::class, 'getMenuFilters']);

# customer ordering
// Route::apiResource('customer-orders', CustomerOrderController::class);

Route::middleware('auth:api')->group(function () {
    Route::post('/customer/orders/store', [CustomerOrderController::class, 'store']);
    Route::get('/customer-orders/khmer', [CustomerOrderController::class, 'khmerOrder']);
    Route::get('/customer-orders/korea', [CustomerOrderController::class, 'koreaOrder']);
    Route::get('/customer-orders', [CustomerOrderController::class, 'index']);
    Route::get('/customer-orders/{customer_order}', [CustomerOrderController::class, 'show']);
    Route::get('/customer-orders/by-email/{email}', [CustomerOrderController::class, 'showByEmail']);
    Route::get('/customer-orders/by-phone/{phone}', [CustomerOrderController::class, 'showByPhone']);
    Route::put('/customer-orders/{customer_order}', [CustomerOrderController::class, 'update']);
    Route::delete('/customer-orders/{customer_order}', [CustomerOrderController::class, 'destroy']);



    # client api for post items
    Route::prefix('client/car')->group(function(){
        Route::post('/create', [ClientCarController::class, 'createCar']);
        Route::post('/{car}/update', [ClientCarController::class, 'updateCar']);

        Route::get('/me', [ClientCarController::class, 'myCars']);

        Route::get('/{car}/galleries', [ClientCarController::class, 'getGalleries']);
        Route::post('/{car}/update-gallery', [ClientCarController::class, 'updateGallery']);
        Route::post('/{carImage}/remove-gallery', [ClientCarController::class, 'removeGallery']);
    });
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
