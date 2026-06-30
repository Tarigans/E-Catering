<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController as AdminMenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menus.index');

Route::get('/keranjang', [CartController::class, 'show'])->name('cart.show');
Route::get('/keranjang/summary', [CartController::class, 'summary'])->name('cart.summary');
Route::post('/keranjang', [CartController::class, 'store'])->middleware('throttle:30,1')->name('cart.store');
Route::patch('/keranjang/{key}', [CartController::class, 'update'])->name('cart.update');

Route::get('/login', [AuthController::class, 'loginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1']);
Route::get('/register', [AuthController::class, 'registerForm'])->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
    Route::get('/akun', [AccountController::class, 'index'])->name('account.index');
});

Route::get('/pesanan/{orderNumber}', [OrderTrackingController::class, 'show'])->name('orders.track');
Route::get('/pesanan/{orderNumber}/export-pdf', [OrderTrackingController::class, 'exportPdf'])->name('orders.export-pdf');
Route::get('/pesanan/{orderNumber}/status', [OrderTrackingController::class, 'status'])->name('orders.status');
Route::post('/pesanan/{orderNumber}/bukti-pembayaran', [OrderTrackingController::class, 'uploadPaymentProof'])->middleware(['auth', 'role:customer', 'throttle:5,1'])->name('orders.payment-proof');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('menus', AdminMenuItemController::class)->except('show');
    Route::get('orders/{order}/receipt', [AdminOrderController::class, 'kitchenReceipt'])->name('orders.receipt');
    Route::patch('orders/{order}/payment/confirm', [AdminOrderController::class, 'confirmPayment'])->name('orders.payment.confirm');
    Route::patch('orders/{order}/payment/reject', [AdminOrderController::class, 'rejectPayment'])->name('orders.payment.reject');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('reports/orders', [AdminReportController::class, 'orders'])->name('reports.orders');
    Route::get('reports/orders/pdf', [AdminReportController::class, 'ordersPdf'])->name('reports.orders.pdf');
    Route::get('reports/finance', [AdminReportController::class, 'finance'])->name('reports.finance');
    Route::get('reports/finance/pdf', [AdminReportController::class, 'financePdf'])->name('reports.finance.pdf');
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings/areas', [AdminSettingController::class, 'storeArea'])->name('settings.areas');
    Route::post('settings/vouchers', [AdminSettingController::class, 'storeVoucher'])->name('settings.vouchers');
    Route::post('settings/slots', [AdminSettingController::class, 'storeSlot'])->name('settings.slots');
    Route::post('settings/hours', [AdminSettingController::class, 'storeHour'])->name('settings.hours');
});
