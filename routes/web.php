<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserDetailController;
use Illuminate\Support\Facades\Route;

//Admin

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CarBrandController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CarTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

   Route::get('/', function () {
        return redirect()->route('cpanelShowLogin');
    });

// Route::middleware(['locale'])->group(function () {

// // Authentication Routes...
// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login'])->name('login.post');
// Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// // Registration Routes...
// Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [RegisterController::class, 'register']);
// Route::get('/payment/callback/{userID}', [RegisterController::class, 'paymentCallback'])->name('payment.callback');
// Route::get('/featured-payment/callback/{userID}', [RegisterController::class, 'featuredPaymentCallback'])->name('featured-payment.callback');

// // Password Reset Routes...
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm']);
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm']);
// Route::post('password/reset', [ResetPasswordController::class, 'reset']);


// Route::get('/search', [SearchController::class, 'list'])->name('searchpage');
// Route::get('/autocomplete', [SearchController::class, 'autocomplete'])->name('autocomplete');
// Route::get('/book-call/click', [SearchController::class, 'incrementBookCallCount'])->name('book-call-click');

// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/about-us', [HomeController::class, 'aboutus'])->name('aboutus');
// Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
// Route::get('/copyright-policy', [HomeController::class, 'copyright'])->name('copyright');
// Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
// Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact');
// Route::post('/contact-us', [HomeController::class, 'contactSubmit'])->name('contact.post');
// Route::get('/getMoreCarTypes', [HomeController::class, 'getMoreCarTypes'])->name('getMoreCarTypes');
// Route::get('/changeLocale', [HomeController::class, 'changeLocale'])->name('changeLocale');

// Route::get('/detail/{slug}', [HomeController::class, 'getCarDetail'])->name('detailpage');

// Route::get('/packages', [HomeController::class, 'showPackagesPage'])->name('packages');


// });

// Route::middleware(['auth', 'dealer'])->group(function () {
//     // Route::get('/profile', function () {
//     //     return view('dashboard.profile');
//     // })->name('profile');

//     Route::get('/dashboard', [CarController::class, 'list'])->name('cars.list');
//     Route::get('/listing/new', [CarController::class, 'form'])->name('create-car');
//     Route::post('/listing/new/post', [CarController::class, 'addNew'])->name('create-car.post');
//     Route::get('/changeStatus', [CarController::class, 'changeStatus']);
//     Route::get('/featured/toggles', [CarController::class, 'toggleFeatured'])->name('cars.toggleFeatured');


//     Route::get('/dealer/profile', [UserDetailController::class, 'getProfile'])->name('profile');
//     Route::post('/dealer/profile', [UserDetailController::class, 'updateDetail']);
//     Route::get('/dealer/subscriptions', [UserDetailController::class, 'getSubscriptions'])->name('getSubscriptions');
//     Route::get('/dealer/package/renew', [UserDetailController::class, 'renewPackage'])->name('renewPackage');
//     Route::get('/dealer/package/featured/get', [UserDetailController::class, 'renewFeatured'])->name('renewFeatured');
// });

// Admin Routes


Route::get('cpanel', [AdminLoginController::class, 'cpanelShowLogin'])->name('cpanelShowLogin');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {

    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // // Registration Routes...
    // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes...
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);


    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');



    //Category Routes
    Route::get('categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('categoriescreate', [CategoryController::class, 'create'])->name('category.create');
    Route::post('categoriesstore', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/categories{category}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::get('categories{brand}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::delete('categories{brand}/delete', [CategoryController::class, 'destroy'])->name('category.destroy');

    //Banners Routes
    Route::get('banners/create', [BannersController::class, 'create'])->name('banner.create');
    Route::get('banners/list', [BannersController::class, 'index'])->name('banner.index');
    Route::post('banners/store', [BannersController::class, 'store'])->name('banner.store');
    Route::get('banners/{banner}/edit', [BannersController::class, 'edit'])->name('banner.edit');
    Route::put('/banners/{banner}/update', [BannersController::class, 'update'])->name('banner.update');
    Route::delete('banners/{banner}/delete', [BannersController::class, 'destroy'])->name('banner.destroy');

    //Products Routes
    Route::get('products/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('products/list', [ProductController::class, 'index'])->name('product.index');
    Route::post('products/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{product}/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('products/{product}/delete', [ProductController::class, 'destroy'])->name('product.destroy');


























    // Route::get('cars/create', [AdminCarController::class, 'create'])->name('cars.create');
    Route::get('cars/list', [AdminCarController::class, 'index'])->name('cars.index');
    Route::get('cars/{car_id}/show', [AdminCarController::class, 'show'])->name('cars.show');
    // Route::post('cars/store', [AdminCarController::class, 'store'])->name('cars.store');
    // Route::get('cars/{car}/edit', [AdminCarController::class, 'edit'])->name('cars.edit');
    // Route::put('/cars/{car}/update', [AdminCarController::class, 'update'])->name('cars.update');
    Route::get('cars/{id}/block', [AdminCarController::class, 'block'])->name('cars.block');
    Route::get('cars/{id}/unblock', [AdminCarController::class, 'unblock'])->name('cars.unblock');
    Route::delete('cars/{car}/delete', [AdminCarController::class, 'destroy'])->name('cars.destroy');

    Route::get('dealer/create', [DealerController::class, 'create'])->name('dealer.create');
    Route::get('dealer/list', [DealerController::class, 'index'])->name('dealer.index');
    Route::post('dealer/store', [DealerController::class, 'store'])->name('dealer.store');
    Route::get('dealer/{dealer_id}/show', [DealerController::class, 'show'])->name('dealer.show');
    Route::get('dealer/{dealer_id}/edit', [DealerController::class, 'edit'])->name('dealer.edit');
    Route::put('/dealer/{dealer_id}/update', [DealerController::class, 'update'])->name('dealer.update');
    Route::delete('dealer/{dealer_id}/delete', [DealerController::class, 'destroy'])->name('dealer.destroy');
    Route::get('/dealer/{dealer_id}/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/dealer/generate-payment', [SubscriptionController::class, 'generatePayment'])->name('subscriptions.generatePayment');
    Route::get('dealer/{id}/block', [DealerController::class, 'block'])->name('dealer.block');
    Route::get('dealer/{id}/unblock', [DealerController::class, 'unblock'])->name('dealer.unblock');
    Route::get('dealer/giveaway-subscription', [DealerController::class, 'giveawaySubscription'])->name('dealer.giveaway-subscription');
    // Route::get('/get/subscriptions/modal', [SubscriptionController::class, 'index'])->name('subscriptions.index');


    Route::get('brands/create', [CarBrandController::class, 'create'])->name('brands.create');
    Route::get('brands/list', [CarBrandController::class, 'index'])->name('brands.index');
    Route::post('brands/store', [CarBrandController::class, 'store'])->name('brands.store');
    Route::get('brands/{brand}/edit', [CarBrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}/update', [CarBrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{brand}/delete', [CarBrandController::class, 'destroy'])->name('brands.destroy');


    Route::get('models/create', [CarModelController::class, 'create'])->name('models.create');
    Route::get('models/list', [CarModelController::class, 'index'])->name('models.index');
    Route::post('models/store', [CarModelController::class, 'store'])->name('models.store');
    Route::get('models/{model}/edit', [CarModelController::class, 'edit'])->name('models.edit');
    Route::put('/models/{model}/update', [CarModelController::class, 'update'])->name('models.update');
    Route::delete('models/{model}/delete', [CarModelController::class, 'destroy'])->name('models.destroy');


    Route::get('types/create', [CarTypeController::class, 'create'])->name('types.create');
    Route::get('types/list', [CarTypeController::class, 'index'])->name('types.index');
    Route::post('types/store', [CarTypeController::class, 'store'])->name('types.store');
    Route::get('types/{type}/edit', [CarTypeController::class, 'edit'])->name('types.edit');
    Route::put('/types/{type}/update', [CarTypeController::class, 'update'])->name('types.update');
    Route::delete('types/{type}/delete', [CarTypeController::class, 'destroy'])->name('types.destroy');

    Route::get('advertisement/create', [AdvertisementController::class, 'create'])->name('advertisement.create');
    Route::get('advertisement/list', [AdvertisementController::class, 'index'])->name('advertisement.index');
    Route::post('advertisement/store', [AdvertisementController::class, 'store'])->name('advertisement.store');
    Route::get('advertisement/{ad}/edit', [AdvertisementController::class, 'edit'])->name('advertisement.edit');
    Route::put('/advertisement/{ad}/update', [AdvertisementController::class, 'update'])->name('advertisement.update');
    Route::delete('advertisement/{ad}/delete', [AdvertisementController::class, 'destroy'])->name('advertisement.destroy');



    // Route::get('subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    // Route::get('subscriptions/list', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    // Route::post('subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    // Route::get('subscriptions/{sub_id}/show', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    // Route::get('subscriptions/{sub_id}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    // Route::put('/subscriptions/{sub_id}/update', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    // Route::delete('subscriptions/{sub_id}/delete', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::get('cities/list', [SettingsController::class, 'getCities'])->name('get.cities');
    Route::get('cities/{city_id}/update', [SettingsController::class, 'updateCity'])->name('update.city');
    Route::get('contact/queries', [SettingsController::class, 'showQueriesList'])->name('contact.showQueriesList');
    Route::get('plans', [SettingsController::class, 'getSubscriptionPlans'])->name('plans.index');
    // Route::get('plans/{type}/edit', [SettingsController::class, 'showSubscriptionPlanForm'])->name('plans.edit');
    Route::post('plans/{type}/addorupdate', [SettingsController::class, 'addOrUpdatePlan'])->name('plans.update');
    Route::delete('plans/{type}/delete', [SettingsController::class, 'deletePlan'])->name('plan.destroy');

});


Route::get('clear-cache', function() {
    Artisan::call('optimize:clear');
    \Request::session()->flash('alert-success', 'System Cache has been cleared!');
    return back();
})->name('clear-cache');
