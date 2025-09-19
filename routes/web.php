<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AHomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\WishlistController;


// Tuyến đường công khai cho trang danh sách và chi tiết game
Route::get('/guest', [PagesController::class, 'index'])->name('guest.index');
Route::get('/games/{id}', [PagesController::class, 'show'])->name('guest.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

// Tuyến đường cho guest (người dùng đã đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('guest.account');
        Route::get('/settings', [AccountController::class, 'settings'])->name('guest.settings');
        Route::put('/update', [AccountController::class, 'update'])->name('guest.account.update');
        Route::post('/change-password', [AccountController::class, 'changePassword'])->name('guest.account.changePassword');
        Route::post('/notifications', [AccountController::class, 'updateNotifications'])->name('guest.account.updateNotifications');
    });
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/add-all-to-cart', [WishlistController::class, 'addAllToCart'])->name('wishlist.addAllToCart');
    
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('guest.orders');
    Route::get('/order/{id}/details', [CheckoutController::class, 'show'])->name('order.details');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order/success', function () {
        return view('guest.order-success');
    })->name('order.success');
    
    Route::post('/checkout/refund/{id}', [CheckoutController::class, 'refund'])->name('order.refund');
    Route::post('/zalopay/callback', [ZaloPayController::class, 'callback']);
});

// Tuyến đường cho admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/home', [AHomeController::class, 'index'])->name('admin.home');

    Route::resource('admin', AdminController::class);
    Route::get('/admin/games/{id}', [AdminController::class, 'show'])->name('admin.games.show');
    Route::get('/api/revenue-chart', [AHomeController::class, 'revenueChart'])->name('admin.api.revenueChart');
    Route::get('/api/top-games-chart', [AHomeController::class, 'topGamesChart'])->name('admin.api.topGamesChart');



    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{id}/update-status', [UserController::class, 'updateStatus'])->name('admin.users.update-status');
    Route::patch('/users/{id}/upgrade-to-admin', [UserController::class, 'upgradeToAdmin'])->name('admin.users.upgrade-to-admin');
    Route::patch('/users/{id}/downgrade-to-user', [UserController::class, 'downgradeToUser'])->name('admin.users.downgrade-to-user');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/order', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/order/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::prefix('/acc')->group(function () {
        Route::get('/', [AccController::class, 'index'])->name('admin.account');
        Route::get('/settings', [AccController::class, 'settings'])->name('admin.settings');
        Route::put('/update', [AccController::class, 'update'])->name('admin.account.update');
        Route::post('/change-password', [AccController::class, 'changePassword'])->name('admin.account.changePassword');
        Route::post('/notifications', [AccController::class, 'updateNotifications'])->name('admin.account.updateNotifications');
    });
});

// Tuyến đường xác thực
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister'])->name('postRegister');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forgot', [ForgotController::class, 'forgot'])->name('forgot');
Route::post('forgot', [ForgotController::class, 'postForgot'])->name('postForgot');

Route::get('reset/{token}', [ResetController::class, 'reset'])->name('password.reset');
Route::post('reset', [ResetController::class, 'postReset'])->name('postReset');