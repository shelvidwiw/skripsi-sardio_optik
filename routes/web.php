<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EyeExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EyeExamBookingController as AdminEyeExamBookingController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ITERASI 1 ROUTES
| Hanya fitur core: browse produk, cart, checkout -> WA, admin kelola order.
|--------------------------------------------------------------------------
*/

// ===== CUSTOMER (public, no login) =====
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Pemeriksaan Mata: halaman tersendiri, bukan produk — booking jadwal periksa mata, bukan checkout cart.
Route::get('/pemeriksaan-mata', [EyeExamController::class, 'index'])->name('eye-exam.index');
Route::post('/pemeriksaan-mata', [EyeExamController::class, 'store'])->name('eye-exam.store');

// Halaman info toko fisik (dulu link "Store" di navbar berstatus "Segera hadir").
Route::get('/toko', [StoreController::class, 'index'])->name('store.index');

// Lacak pesanan tanpa login: cocokkan Kode Order + Nomor WhatsApp (dulu ikon
// tracking di navbar & "Track Order" di footer berstatus "Segera hadir").
Route::get('/lacak-pesanan', [TrackOrderController::class, 'index'])->name('track-order.index');
Route::post('/lacak-pesanan', [TrackOrderController::class, 'track'])->name('track-order.track');

// ===== CUSTOMER AUTH (register, login, logout, akun) =====
Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('/akun', [AccountController::class, 'index'])->name('account.index');
    Route::patch('/akun', [AccountController::class, 'update'])->name('account.update');
});

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/eye-exam-bookings', [AdminEyeExamBookingController::class, 'index'])->name('eye-exam-bookings.index');
        Route::get('/eye-exam-bookings/{eyeExamBooking}', [AdminEyeExamBookingController::class, 'show'])->name('eye-exam-bookings.show');
        Route::patch('/eye-exam-bookings/{eyeExamBooking}/status', [AdminEyeExamBookingController::class, 'updateStatus'])->name('eye-exam-bookings.updateStatus');

        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

        Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::patch('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
