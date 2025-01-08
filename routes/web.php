<?php

use App\Http\Controllers\AdminAdminController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminQualityController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\AdminTenderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/** Login routes */
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('show_login');
    Route::post('/login', [LoginController::class, 'handleLogin'])->name('login');
});
Route::group(['middleware'=>'auth:web'], function() {
    Route::post('/logout', [LoginController::class, 'handleLogout'])->name('logout');
});

Route::group(['middleware'=>'auth:web'], function() {
    //Home routes
    Route::get('/', [HomeController::class, 'home'])->name('home');
});


/** Admin login routes */
Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('show_login');
    Route::post('login', [AdminLoginController::class, 'handleLogin'])->name('login');
});
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    //Home route
    Route::get('/', [AdminHomeController::class, 'home'])->name('home');
    //Logout
    Route::post('logout', [AdminLoginController::class, 'handleLogout'])->name('logout');
    //User routes
    Route::post('users/bulkDelete', [AdminUserController::class, 'bulkDelete']);
    Route::resource('users', AdminUserController::class);
    //Admin routes
    Route::post('admins/bulkDelete', [AdminAdminController::class, 'bulkDelete']);
    Route::resource('admins', AdminAdminController::class);
    //Supplier routes
    Route::post('suppliers/bulkDelete', [AdminSupplierController::class, 'bulkDelete']);
    Route::resource('suppliers', AdminSupplierController::class);
    //Material routes
    Route::post('materials/bulkDelete', [AdminMaterialController::class, 'bulkDelete']);
    Route::resource('materials', AdminMaterialController::class);
    //Quality routes
    Route::post('qualities/bulkDelete', [AdminQualityController::class, 'bulkDelete']);
    Route::resource('qualities', AdminQualityController::class);
    //Tender routes
    Route::resource('tenders', AdminTenderController::class);
});

