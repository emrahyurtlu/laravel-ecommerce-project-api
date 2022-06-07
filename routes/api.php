<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
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

Route::group(["prefix" => "v1"], function () {

    // Public Routes
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/kategori/{category:slug}', [\App\Http\Controllers\Frontend\CategoryController::class, 'index']);

    Route::post("/uye-ol", [AuthController::class, 'signUp']);
    Route::post("/giris", [AuthController::class, 'signIn']);
    Route::get("/cikis", [AuthController::class, 'logout']);

    // Private Routes
    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::get("/sepetim", [CartController::class, 'index']);
        Route::post("/sepetim/ekle/{product}", [CartController::class, 'add']);
        Route::delete("/sepetim/sil/{cartDetails}", [CartController::class, 'remove']);

        Route::get("/satin-al", [CheckoutController::class, 'showCheckoutForm']);
        Route::post("/satin-al", [CheckoutController::class, 'checkout']);
    });


    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::resource("/users", UserController::class);
        Route::get("/users/{user}/change-password", [UserController::class, 'passwordForm']);
        Route::post("/users/{user}/change-password", [UserController::class, 'changePassword']);
        Route::resource("/users/{user}/addresses", AddressController::class);
        Route::resource("/categories", CategoryController::class);
        Route::resource("/products", ProductController::class);
        Route::resource("/products/{product}/images", ProductImageController::class);
    });

});
