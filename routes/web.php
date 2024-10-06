<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Car\CategoryController;


Route::post('/admin/locale', [LocaleController::class, 'setLocale']);
Route::get('/admin/locale', [LocaleController::class, 'getLocale']);


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('admin')->name('cars.')->group(function () {
    Route::get('/cars', function () {
        return Inertia::render('Admin/Cars/Index');
    })->name('list');

    Route::get('/cars/test', function () {
        return Inertia::render('Admin/Cars/Categories/Edit');
    })->name('test');

    Route::prefix('car')->group(function () {
        Route::get('/categories/all', [CategoryController::class, 'getCategories'])->name('categories.list');
        Route::post('/categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.destroy.selected');
        Route::resource('categories', CategoryController::class);
        // Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        
        // Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        // Route::get('/category/create', [CategoryController::class, 'create'])->name('categories.create');
        // Route::get('/category/{carCategory}/show', [CategoryController::class, 'show'])->name('categories.show');
        // Route::patch('/category/{carCategory}/show', [CategoryController::class, 'update'])->name('categories.update');
        // Route::delete('/category/{carCategory}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        Route::get('/conditions', function () {
            return Inertia::render('Admin/Cars/Conditions');
        })->name('conditions');
        Route::get('/brands', function () {
            return Inertia::render('Admin/Cars/Brands');
        })->name('brands');
        Route::get('/models', function () {
            return Inertia::render('Admin/Cars/Models');
        })->name('models');
        Route::get('/fuel-types', function () {
            return Inertia::render('Admin/Cars/Fueltypes');
        })->name('fueltypes');
        Route::get('/transmission-types', function () {
            return Inertia::render('Admin/Cars/Transmissiontypes');
        })->name('transmissiontypes');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
