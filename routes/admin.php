<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\OrderReportController;


Route::get('/test', function() {
    return 'testing';
});
Route::group(['prefix' => 'auth'], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::group(['prefix' => 'reports'], function () {
    Route::get('/orders', [OrderReportController::class, 'index'])->name('orders.index');
    Route::get('/orders/{customerOrder}/detail', [OrderReportController::class, 'getOrderDetails'])->name('orders.detail');
    Route::get('/orders/list', [OrderReportController::class, 'getOrdersList'])->name('orders.list');
    Route::post('/orders/{order}/update-status', [OrderReportController::class, 'updateStatus'])->name('orders.update-status');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/list', [UserController::class, 'getUsersList'])->name('users.list');
});

Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/list', [RoleController::class, 'getRolesList'])->name('list');
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create-or-update/{role?}', [RoleController::class, 'createOrUpdate'])->name('create');
    Route::post('/store', [RoleController::class, 'store'])->name('store');
    Route::post('/{role}/update', [RoleController::class, 'update'])->name('update');
    Route::get('/{role}/permissions/', [RoleController::class, 'permissions'])->name('permissions');
    Route::post('/change-permissions', [RoleController::class, 'changePermissions'])->name('change-permissions');
    Route::post('/delete-selected', [RoleController::class, 'deleteSelected'])->name('destroy.selected');
});
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/list', [PermissionController::class, 'getPermissionsList'])->name('list');
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::get('/create-or-update/{permission?}', [PermissionController::class, 'createOrUpdate'])->name('create');
    Route::post('/store', [PermissionController::class, 'store'])->name('store');
    Route::post('/{permission}/update', [PermissionController::class, 'update'])->name('update');
    Route::get('/{permission}/permissions/', [PermissionController::class, 'permissions'])->name('permissions');
    Route::post('/change-permissions', [PermissionController::class, 'changePermissions'])->name('change-permissions');
    Route::post('/delete-selected', [PermissionController::class, 'deleteSelected'])->name('destroy.selected');
});
