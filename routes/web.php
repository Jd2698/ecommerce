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

//categorías
Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
    Route::get('/home', 'home')->name('categories.home'); //client
    Route::get('/home/{category}', 'homeShowProducts');  //products
    Route::get('/', 'index')->name('categories.index'); //client - admin
});

Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
    Route::get('/home/{category}', 'home')->name('products.home');  //client
    Route::get('/buscador', 'buscador')->name('products.buscador');  //client
    Route::get('/{product}', 'show')->name('products.show');  //client - shop -admin
});

// private
Route::group(['middleware' => ['auth']], function () {

    Route::get('/cart', [ProductController::class, 'cart'])->name('products.cart');

    Route::group(['prefix' => 'users', 'middleware' => ['role:admin'], 'controller' => UserController::class], function () {
        Route::get('/get-all', 'getAll')->name('users.getAll'); //admin
        Route::get('/getRoles', 'getRoles'); //admin
        Route::get('/', 'index')->name('users.index')->middleware('can:users.index');; //admin
        Route::post('/', 'store')->name('users.store');  //admin
        Route::get('/{user}', 'show')->name('users.show'); //admin
        Route::post('/update/{user}', 'update')->name('users.update'); //admin
        Route::delete('/{user}', 'destroy')->name('users.destroy'); //admin
    });

    Route::group(['prefix' => 'products', 'middleware' => ['role:admin'], 'controller' => ProductController::class], function () {

        Route::get('/', 'index')->name('products.index');  //admin
        Route::post('/', 'store')->name('products.store');  //admin
        Route::post('/update/{product}', 'update')->name('products.update');  //admin
        Route::delete('/{product}', 'destroy')->name('products.destroy');  //admin
    });

    Route::group(['prefix' => 'categories', 'middleware' => ['role:admin'], 'controller' => CategoryController::class], function () {
        Route::get('/get-all-dt', 'getAllDt')->name('categories.getAllDt'); //admin
        Route::get('/get-all', 'getAll')->name('categories.getAll'); //admin
        Route::get('/', 'index')->name('categories.index'); //admin
        Route::post('/', 'store')->name('categories.store');  //admin
        Route::get('/{category}', 'show')->name('categories.show');  //admin
        Route::put('/{category}', 'update')->name('categories.update');  //admin
        Route::delete('/{category}', 'destroy')->name('categories.destroy');  //admin
    });
});


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
