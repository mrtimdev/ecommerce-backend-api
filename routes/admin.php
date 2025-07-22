<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ReportController;
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

    Route::get('/orders/{user}/show', [OrderReportController::class, 'order_by_user'])->name('orders.user.index');
    Route::get('/orders/{user}/list', [OrderReportController::class, 'getOrdersListByUser'])->name('orders.user.list');

    Route::get('/orders/monthly-report', [OrderReportController::class, 'monthlyOrderReport'])->name('order.monthly.report');

    // users
    Route::get('/users/cars', [ReportController::class, 'index'])->name('reports.users.cars');
});
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/list', [UserController::class, 'getUsersList'])->name('list');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::post('/{user}/update', [UserController::class, 'update'])->name('update');
    Route::post('/avatar/{user}/update', [UserController::class, 'update_avatar'])->name('update-avatar');
    Route::post('/delete-selected', [UserController::class, 'deleteSelected'])->name('destroy.selected');
});

# user register from frontend
Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/list', [ClientController::class, 'getClientsList'])->name('list');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/store', [ClientController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [ClientController::class, 'edit'])->name('edit');
    Route::post('/{user}/update', [ClientController::class, 'update'])->name('update');
    Route::post('/avatar/{user}/update', [ClientController::class, 'update_avatar'])->name('update-avatar');
    Route::post('/delete-selected', [ClientController::class, 'deleteSelected'])->name('destroy.selected');

    Route::get('/likes/{user?}', [ClientController::class, 'itemsLiked'])->name('items.like');
    Route::get('/liked/list/{user?}', [ClientController::class, 'getItemsLikedList'])->name('items.like.list');
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
