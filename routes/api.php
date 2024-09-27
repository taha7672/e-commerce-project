<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VariantAttributeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\UserFavouriteController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\TicketsController;
use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\LocalityController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1/user', 'middleware' => ['check.site.status']], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify', [VerificationController::class, 'verify']);
    Route::post('/forget-password/link', [AuthController::class, 'sendForgetPasswordLink']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['check.bearer.token', 'check.site.status']], function () {
    Route::post('sub-categories-list', [SubCategoryController::class, 'subCategoryList']);
    Route::post('tags-list', [TagController::class, 'tagList']);
    Route::post('variant-attribute-list', [VariantAttributeController::class, 'variantAttributeList']);
    Route::post('product-list', [ProductController::class, 'productList']);
    Route::post('product-details', [ProductController::class, 'productDetails']);
    Route::post('coupon-list', [CouponController::class, 'couponList']);
    Route::post('filter-coupon-list', [CouponController::class, 'filterCouponList']);
    Route::post('add-product-review', [ProductReviewController::class, 'addProductReview']);
    Route::post('product-review-list', [ProductReviewController::class, 'productReviewList']);
    Route::post('update-product-review', [ProductReviewController::class, 'updateProductReview']);
    Route::post('add-favourite-product', [UserFavouriteController::class, 'addRemoveFavouriteProduct']);
    Route::post('favourite-product-list', [UserFavouriteController::class, 'favouriteProductList']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('add-to-cart', [AddToCartController::class, 'addToCart']);
    Route::post('cart-details', [AddToCartController::class, 'cartDetails']);
    Route::post('remove-from-cart', [AddToCartController::class, 'removeFromCart']);
    Route::post('apply-coupon-to-cart', [AddToCartController::class, 'applyCouponToCart']);

    Route::post('add-shipping-address', [AddressController::class, 'addShippingAddress']);
    Route::post('get-shipping-address', [AddressController::class, 'getShippingAddress']);

    Route::post('add-billing-address', [AddressController::class, 'addBillingAddress']);
    Route::post('get-billing-address', [AddressController::class, 'getBillingAddress']);

    Route::post('add-order', [OrderController::class, 'createOrder']);
    Route::post('orders-list', [OrderController::class, 'ordersList']);
    Route::post('get-single-order', [OrderController::class, 'getSingleOrder']);

    Route::prefix('orders')->group(function () {
        Route::get('/order-inquiry', [OrderController::class, 'inquiry']);
    });

    Route::post('search-product-list', [ProductController::class, 'searchProductList']);
    Route::post('tickets/post-comment', [TicketsController::class, 'postComment'])->name('tickets.post-comment');
    Route::post('tickets/{ticket_id}/update', [TicketsController::class, 'update'])->name('tickets.update');
    Route::apiResource('tickets', TicketsController::class)->only(['index', 'show', 'store', 'destroy']);

    Route::get('/user-info', [UserController::class, 'show']);
    Route::post('/user-update', [UserController::class, 'update']);
    Route::get('/currency-list', [SettingController::class, 'currencyList']);
    Route::get('/language-list', [SettingController::class, 'languageList']);
});



Route::group(['prefix' => 'v1', 'middleware' => ['check.site.status']], function () {
    Route::post('categories-list', [CategoryController::class, 'categoryList']);
    Route::post('get-provinces', [LocalityController::class, 'provincesList']);
    Route::post('get-districts/{provience_id}', [LocalityController::class, 'districtsList']);
    Route::post('get-villages/{district_id}', [LocalityController::class, 'villagesList']);
    Route::post('filtered-product-list', [ProductController::class, 'filteredProductList']);
    Route::get('/sliders', [SliderController::class, 'index']);
    Route::post('/visitor', [VisitorController::class, 'addVisitor']);
});
// No Auth Routes
Route::group(['prefix' => 'v1'], function () {
    Route::get('/site-setting', [SettingController::class, 'getSiteSetting']);
    Route::post('/update-site-setting', [SettingController::class, 'updateSiteSetting']);
});
