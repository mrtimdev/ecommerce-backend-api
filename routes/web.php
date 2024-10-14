<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\ConditionController;


Route::post('/admin/locale', [LocaleController::class, 'setLocale']);
Route::get('/admin/locale', [LocaleController::class, 'getLocale']);


Route::get('/', function () {
    return Inertia::render('Admin/Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard.home');

Route::get('/dashboard', function () {
    // return Inertia::render('Dashboard');
    return Inertia::render('Admin/Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->prefix('admin')->group(function () {
    

    Route::get('/cars/all', [CarController::class, 'getCars'])->name('cars.list');
    Route::post('/cars/delete-selected', [CarController::class, 'deleteSelected'])->name('cars.destroy.selected');
    Route::get('cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('cars/store', [CarController::class, 'store'])->name('cars.store');
    Route::get('cars', [CarController::class, 'index'])->name('cars.index');
    Route::delete('cars/{car}/delete', [CarController::class, 'destroy'])->name('cars.destroy');
    Route::get('cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::post('cars/{car}/update', [CarController::class, 'update'])->name('cars.update');

    Route::get('/categories/all', [CategoryController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.destroy.selected');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::delete('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update');
    

    Route::get('/conditions/all', [ConditionController::class, 'getConditions'])->name('conditions.list');
    Route::post('/conditions/delete-selected', [ConditionController::class, 'deleteSelected'])->name('conditions.destroy.selected');
    Route::get('conditions/create', [ConditionController::class, 'create'])->name('conditions.create');
    Route::post('conditions/store', [ConditionController::class, 'store'])->name('conditions.store');
    Route::get('conditions', [ConditionController::class, 'index'])->name('conditions.index');
    Route::delete('condition/{condition}/delete', [ConditionController::class, 'destroy'])->name('conditions.destroy');
    Route::get('conditions/{condition}/edit', [ConditionController::class, 'edit'])->name('conditions.edit');
    Route::post('conditions/{condition}/update', [ConditionController::class, 'update'])->name('conditions.update');

    Route::get('/brands/all', [BrandController::class, 'getBrands'])->name('brands.all');
    Route::get('/brands/list', [BrandController::class, 'getListBrands'])->name('brands.list');
    Route::post('/brands/delete-selected', [BrandController::class, 'deleteSelected'])->name('brands.destroy.selected');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::delete('brands/{brand}/delete', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('brands/{brand}/update', [BrandController::class, 'update'])->name('brands.update');
    
    Route::get('/models/all', [ModelController::class, 'getModels'])->name('models.list');
    Route::post('/models/delete-selected', [ModelController::class, 'deleteSelected'])->name('models.destroy.selected');
    Route::get('models/create', [ModelController::class, 'create'])->name('models.create');
    Route::post('models/store', [ModelController::class, 'store'])->name('models.store');
    Route::get('models', [ModelController::class, 'index'])->name('models.index');
    Route::delete('models/{model}/delete', [ModelController::class, 'destroy'])->name('models.destroy');
    Route::get('models/{model}/edit', [ModelController::class, 'edit'])->name('models.edit');
    Route::post('models/{model}/update', [ModelController::class, 'update'])->name('models.update');

    Route::get('/fuel_types/all', [FuelTypeController::class, 'getFuelTypes'])->name('fuelTypes.list');
    Route::post('/fuel_types/delete-selected', [FuelTypeController::class, 'deleteSelected'])->name('fuelTypes.destroy.selected');
    Route::get('fuel_types/create', [FuelTypeController::class, 'create'])->name('fuelTypes.create');
    Route::post('fuel_types/store', [FuelTypeController::class, 'store'])->name('fuelTypes.store');
    Route::get('fuel_types', [FuelTypeController::class, 'index'])->name('fuelTypes.index');
    Route::delete('fuel_types/{fuelType}/delete', [FuelTypeController::class, 'destroy'])->name('fuelTypes.destroy');
    Route::get('fuel_types/{fuelType}/edit', [FuelTypeController::class, 'edit'])->name('fuelTypes.edit');
    Route::post('fuel_types/{fuelType}/update', [FuelTypeController::class, 'update'])->name('fuelTypes.update');


    Route::get('/transmission-types', function () {
        return Inertia::render('Admin/Cars/Transmissiontypes');
    })->name('transmissiontypes');
});

# settings routes
Route::middleware('auth')->prefix('admin')->name('settings.')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('index');
    Route::post('/settings/upload', [SettingController::class, 'upload'])->name('upload');
});

# frontend routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware('auth')->prefix('frontend')->name('frontend.')->group(function () {
        Route::middleware('auth')->prefix('homepage')->name('homepage.')->group(function () {
            Route::get('/sliders/all', [FrontendController::class, 'getHomePageSliders'])->name('sliders.list');
            Route::get('/sliders', [FrontendController::class, 'index'])->name('sliders.index');
            Route::post('/sliders', [FrontendController::class, 'store'])->name('sliders.store');
            Route::post('/sliders/{slider}', [FrontendController::class, 'update'])->name('sliders.update');
            Route::delete('/sliders/{slider}', [FrontendController::class, 'destroy'])->name('sliders.destroy');
            Route::get('/sliders/d', [FrontendController::class, 'deleteSelected'])->name('sliders.destroy.selected');

            
        });
    });
});

Route::middleware('auth')->prefix('admin/auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
