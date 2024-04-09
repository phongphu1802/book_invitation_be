<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingFormController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//authenticate
Route::group(['as' => 'user.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::group(['middleware' => 'auth:api'], function () {
    //user
    Route::group(['as' => 'user.'], function () {
        Route::post('user', [UserController::class, 'store']);
        Route::get('user/{id}', [UserController::class, 'show']);
        Route::get('users', [UserController::class, 'index']);
        Route::put('user/{id}', [UserController::class, 'edit']);
        Route::delete('user/{id}', [UserController::class, 'destroy']);
    });

    //order
    Route::group(['as' => 'order.'], function () {
        Route::post('order', [OrderController::class, 'store']);
        Route::get('order/{id}', [OrderController::class, 'show']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::delete('order/{id}', [OrderController::class, 'destroy']);
    });

    //order_details
    Route::group(['as' => 'order_detail.'], function () {
        Route::get('order_detail/{id}', [OrderDetailController::class, 'show']);
        Route::get('order_details', [OrderDetailController::class, 'index']);
    });

    //cart
    Route::group(['as' => 'cart.'], function () {
        Route::post('cart', [CartController::class, 'store']);
        Route::get('carts', [CartController::class, 'index']);
        Route::delete('cart/{id}', [CartController::class, 'destroyCart']);
        Route::delete('cart_item/{id}', [CartController::class, 'destroyCartItem']);
    });
});

//category-route
Route::group(['as' => 'category.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('category', [CategoryController::class, 'store']);
        Route::put('category/{id}', [CategoryController::class, 'edit']);
        Route::delete('category/{id}', [CategoryController::class, 'destroy']);
    });
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
});

//role
Route::group(['as' => 'role.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('role', [RoleController::class, 'store']);
        Route::put('role/{id}', [RoleController::class, 'edit']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
    });
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::get('roles', [RoleController::class, 'index']);
});

//product
Route::group(['as' => 'product.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('product', [ProductController::class, 'store']);
        Route::put('product/{id}', [ProductController::class, 'edit']);
        Route::delete('product/{id}', [ProductController::class, 'destroy']);
        Route::post('upload-image', [ProductController::class, 'upload']);
    });
    Route::get('product/{id}', [ProductController::class, 'show']);
    Route::get('products', [ProductController::class, 'index']);
});

//booking-form
Route::group(['as' => 'booking_form.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('booking_form', [BookingFormController::class, 'store']);
        Route::put('booking_form/{id}', [BookingFormController::class, 'edit']);
        Route::delete('booking_form/{id}', [BookingFormController::class, 'destroy']);
    });
    Route::get('booking_form/{id}', [BookingFormController::class, 'show']);
    Route::get('booking_forms', [BookingFormController::class, 'index']);
});

//config
Route::group(['as' => 'config.'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('config', [ConfigController::class, 'store']);
        Route::put('config/{id}', [ConfigController::class, 'edit']);
        Route::delete('config/{id}', [ConfigController::class, 'destroy']);
    });
    Route::get('configs', [ConfigController::class, 'index']);
});