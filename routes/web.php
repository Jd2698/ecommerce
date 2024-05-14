<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// public
Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
    Route::get('/', 'index')->name('users.index'); //admin
});

Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
    Route::get('/home', 'home')->name('categories.home'); //client
    Route::get('/{category}', 'show')->name('categories.show');  //client - products
    Route::get('/', 'index')->name('categories.index'); //client - admin
});

Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
    Route::get('/home/{category}', 'home')->name('products.home');  //client
    Route::get('/buscador', 'buscador')->name('products.buscador');  //client
    Route::get('/{product}', 'show')->name('products.show');  //client - shop
});

// private
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'products', 'middleware' => ['role:admin'], 'controller' => ProductController::class], function () {
        Route::get('/', 'index')->name('products.index');  //admin
        Route::post('/', 'store')->name('products.store');  //admin
    });
});


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
