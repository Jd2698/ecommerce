<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
    Route::get('/', 'index')->name('users.index');
});

Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
    Route::get('/home', 'home')->name('categories.home');
    Route::get('/{category}', 'show')->name('categories.show');
});

Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
    Route::get('/home/{category}', 'home')->name('products.home');
});


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
