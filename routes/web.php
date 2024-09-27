<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CurrencySettingController;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Admin\ImportProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\SendEmailController;
use App\Http\Controllers\Admin\sliderController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\TicketController;


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
// landing pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/orders/update/eft', [PageController::class, 'updateEFT'])->name('update.eft');

// password reset
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-password-status', [PasswordResetController::class, 'resetPasswordStatus'])->name('reset-password.status');

// admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('showLogin');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AdminAuthController::class, 'resetPasswordRequest'])->name('forgot-password.request');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'adminShowUpdateForm'])->name('password.reset');
    Route::get('/two-factor-auth', [AdminAuthController::class, 'twoFactorAuth'])->name('two.factor.auth');
    Route::post('/two-factor-auth', [AdminAuthController::class, 'verifyTwoFactorAuth'])->name('two-factor-auth.verify');

    Route::post('/reset-password', [AdminAuthController::class, 'adminResetPassword'])->name('password.update');
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::resource('slider', sliderController::class);
        // change language
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanguage'])->name('changeLanguage');
        Route::get('/notification', [NotificationController::class, 'fetchNotification'])->name('showNotifications');
        Route::get('/read-notifications', [NotificationController::class, 'readNotifications'])->name('notification.read');

        Route::get('get-subcategories/{id}', [SubCategoryController::class, 'getSubcategories']);
        Route::get('get-attributes/{subCategoryId}', [AttributeController::class, 'getAttributes']);
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('sub-categories', SubCategoryController::class);
        Route::resource('tags', TagController::class);
        Route::resource('tickets', TicketController::class);
        Route::get('tickets/comment/{id}', [TicketController::class, 'comment'])->name('tickets.comment');
        Route::post('tickets/comment/{id}', [TicketController::class, 'save'])->name('commit.save');
        Route::prefix('products')->group(function () {
            Route::get('import', [ImportProductController::class, 'index'])->name('products.import.index');
            Route::post('import-upload', [ImportProductController::class, 'upload'])->name('products.import.upload');
            Route::post('import', [ImportProductController::class, 'import'])->name('products.import.save');
        });
        Route::resource('products', ProductController::class);
        Route::resource('coupons', CouponController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('orders', OrderController::class);
        Route::post('orders-status-update/{id}', [OrderController::class, 'updateStatus'])->name('order.status');
        Route::post('ajax-orders-status-update', [OrderController::class, 'updateStatusAjax'])->name('order.status.ajax');
        Route::post('add-orders-note/{id}', [OrderController::class, 'AddNote'])->name('order.note');
        Route::post('ajax-add-orders-note/{id}', [OrderController::class, 'addNoteAjax'])->name('order.note.ajax');

        Route::post('ajax-order-shipping-address-save', [OrderController::class, 'saveShippingAddress'])->name('order.shipping-address.save.ajax');
        Route::post('ajax-order-billing-address-save', [OrderController::class, 'saveBillingAddress'])->name('order.billing-address.save.ajax');
        Route::prefix('/orders')->group(function () {
            Route::post('fetch-shipping-table/{order}', [OrderController::class, 'fetchShippingTable'])->name('order.fetch.shipping-table');
            Route::post('fetch-billing-table/{order}', [OrderController::class, 'fetchBillingTable'])->name('order.fetch.billing-table');
            Route::get('generate-invoice/{order}', [OrderController::class, 'generateInvoice'])->name('order.generate-invoice');
        });

        Route::get('reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
        Route::post('reviews/{id}/approve', [ProductReviewController::class, 'approve'])->name('reviews.approve');
        Route::resource('sub-admins', SubAdminController::class);

        Route::prefix('settings')->group(function () {
            Route::get('currency', [CurrencySettingController::class, 'index'])->name('settings.currency.index');
            Route::post('currency', [CurrencySettingController::class, 'save'])->name('settings.currency.save');
            Route::delete('currency', [CurrencySettingController::class, 'delete'])->name('settings.currency.delete');
        });
        Route::get('settings', [SettingController::class, 'index'])->name('settings');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/logo', [SettingController::class, 'updateLogo'])->name('settings.update.logo');

        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('update-profile');
        Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('update-password');

        Route::post('upload-editor-media', [DashboardController::class, 'uploadEditorMedia'])->name('upload-editor-media');

        // Route::prefix('email-templates')->group(function() {
        //     Route::get('/', [EmailTemplatesController::class, 'index'])->name('email-templates.index');
        // });

        Route::resource('email-templates', EmailTemplateController::class);
        Route::resource('send-emails', SendEmailController::class);
        Route::resource('payment-gateways', PaymentGatewayController::class);
        Route::post('payment-gateways/{payment_gateway}/setDefault', [PaymentGatewayController::class, 'setDefault'])->name('payment-gateways.setDefault');
    });
});
