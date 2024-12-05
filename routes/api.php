<?php

use App\Http\Controllers\Admin\APIController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\DealerController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
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




// Route::middleware(['auth.user'])->group(function () {

// });



Route::post('riderLogin', [APIController::class, 'riderLogin']);
Route::post('customerLogin', [APIController::class, 'customerLogin']);

Route::get('searchByQuery', [APIController::class, 'searchByQuery']);
Route::get('getConfig', [APIController::class, 'getConfig']);
Route::get('getProductsByCategory', [APIController::class, 'getProductsByCategory']);
Route::get('getProductById', [APIController::class, 'getProductById']);
Route::post('createOrder', [APIController::class, 'createOrder']);
Route::post('updateRiderAction', [APIController::class, 'updateRiderAction']);
Route::post('createCustomer', [APIController::class, 'createCustomer']);
Route::get('/payment/callback/{order_id}', [APIController::class, 'paymentCallback'])->name('payment.callback');
Route::post('sendOTP', [APIController::class, 'sendOTP']);

// // Authentication Routes...
// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);
// Route::get('getDealerStatus', [AuthController::class, 'getDealerStatus']);
// Route::get('/payment/webhook', [AuthController::class, 'paymentCallback']);
// Route::get('/getDealerSubscriptions', [AuthController::class, 'getDealerSubscriptions']);
// Route::post('/updateDealerProfile', [DealerController::class, 'updateProfile']);
// Route::get('/createPaymentIntent', [AuthController::class, 'createPaymentIntent']);



// Route::get('/search', [SearchController::class, 'list']);
// Route::get('getConfig', [SettingsController::class, 'getConfig']);
// Route::get('/down2', [SettingsController::class, 'down']);
// Route::post('listing/car/new', [CarController::class, 'addNew']);
// Route::get('listing/car/detail', [CarController::class, 'getCarDetail']);
// Route::get('getCarsByDealer', [CarController::class, 'getCarsByDealer']);
// Route::get('updateCarStatus', [CarController::class, 'updateStatus']);

// Route::post('uploadToBucket', [SettingsController::class, 'uploadToBucket']);




Route::get('/down', [RegisterController::class, 'down']);
