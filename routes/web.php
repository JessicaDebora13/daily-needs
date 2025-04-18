<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AdminSignUpController;
use App\Http\Controllers\CourierLoginController;
use App\Http\Controllers\AdminProductsController;
use App\Http\Controllers\CourierSignUpController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\CustomerTopUpController;
use App\Http\Controllers\CustomerSignUpController;
use App\Http\Controllers\AdminCategoriesController;
use App\Http\Controllers\CustomerViewCartController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AdminAddNewProductController;
use App\Http\Controllers\AdminUpdateProductController;
use App\Http\Controllers\AdminUpdateProfileController;
use App\Http\Controllers\CourierUpdateProfileController;
use App\Http\Controllers\CustomerUpdateProfileController;
use App\Http\Controllers\CustomerPurchaseHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductsController::class, 'index'])->middleware('customer.access');

Route::get('/login', [CustomerLoginController::class, 'index'])->middleware('guest.access')->name('login');
Route::post('/login', [CustomerLoginController::class, 'authenticate']);
Route::post('/logout', [CustomerLoginController::class, 'logout'])->middleware('auth:customer');

Route::get('/signup', [CustomerSignUpController::class, 'index'])->middleware('guest.access');
Route::post('/signup', [CustomerSignUpController::class, 'store']);

Route::get('/cart', [CustomerViewCartController::class, 'index'])->middleware('auth:customer', 'customer.access');
Route::post('/cart', [CustomerViewCartController::class, 'store'])->middleware('auth:customer', 'customer.access');
Route::post('/cart/subtotal', [CustomerViewCartController::class, 'getSubtotal'])->middleware('auth:customer', 'customer.access');
Route::patch('/cart/{brand_slug}/{product_slug}/update', [CustomerViewCartController::class, 'update'])->middleware('auth:customer', 'customer.access')->name('cart.update');
Route::delete('/cart/{brand_slug}/{product_slug}/delete', [CustomerViewCartController::class, 'destroy'])->middleware('auth:customer', 'customer.access')->name('cart.delete');

Route::get('/dashboard/myprofile', [CustomerUpdateProfileController::class, 'show'])->middleware('auth:customer', 'customer.access');
Route::put('dashboard/myprofile/update', [CustomerUpdateProfileController::class, 'update'])->middleware('auth:customer', 'customer.access');
    
Route::get('/dashboard/topup', [CustomerTopUpController::class, 'show'])->middleware('auth:customer', 'customer.access');
Route::put('/dashboard/topup/update', [CustomerTopUpController::class, 'update'])->middleware('auth:customer', 'customer.access');

Route::get('/dashboard/purchasehistory', [CustomerPurchaseHistoryController::class, 'index'])->middleware('auth:customer', 'customer.access');

Route::prefix('admin')->group(function(){
    Route::get('/signup', [AdminSignUpController::class, 'index'])->middleware('guest.access');
    Route::post('/signup', [AdminSignUpController::class, 'store']);

    Route::get('/login', [AdminLoginController::class, 'index'])->middleware('guest.access')->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'authenticate']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin');

    Route::middleware(['auth:admin', 'admin.access'])->group(function () {
        Route::get('/myprofile', [AdminUpdateProfileController::class, 'show']);
        Route::get('/', [AdminUpdateProfileController::class, 'show']);
        Route::put('/myprofile/update', [AdminUpdateProfileController::class, 'update']);

        Route::get('/productlist', [AdminProductsController::class, 'index']);

        Route::get('productlist/addnewproduct', [AdminAddNewProductController::class, 'index']);
        Route::post('productlist/addnewproduct', [AdminAddNewProductController::class, 'store']);

        Route::get('/productlist/categories/{category_slug}', [AdminCategoriesController::class, 'filterByCategory']);
        Route::get('/productlist/{product_slug}', [AdminUpdateProductController::class, 'show']);
        Route::put('/productlist/{product_slug}/update', [AdminUpdateProductController::class, 'update']); 
    });
});

Route::prefix('courier')->group(function(){
    Route::get('/signup', [CourierSignUpController::class, 'index'])->middleware('guest.access');
    Route::post('/signup', [CourierSignUpController::class, 'store']);

    Route::get('/login', [CourierLoginController::class, 'index'])->middleware('guest.access')->name('courier.login');
    Route::post('/login', [CourierLoginController::class, 'authenticate']);
    Route::post('/logout', [CourierLoginController::class, 'logout'])->middleware('auth:courier');

    Route::middleware(['auth:courier', 'courier.access'])->group(function () {
        Route::get('/myprofile', [CourierUpdateProfileController::class, 'show']);
        Route::get('/', [CourierUpdateProfileController::class, 'show']);
        Route::put('/myprofile/update', [CourierUpdateProfileController::class, 'update']);
    });

});

Route::get('{brand_slug}/{product_slug}', [ProductsController::class, 'show'])->middleware('customer.access');
Route::get('/{category_slug}', [CategoriesController::class, 'filterByCategory'])->middleware('customer.access');