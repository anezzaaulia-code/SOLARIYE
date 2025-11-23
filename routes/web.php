<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\KategoriMenuController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianBahanController;
use App\Http\Controllers\DetailPembelianBahanController;
use App\Http\Controllers\StokHarianController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// AUTH
// =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// =====================
// DASHBOARD
// =====================

// Redirect otomatis setelah login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard untuk admin & kasir tetapi view-nya beda
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

// ======================
// ADMIN ONLY
// ======================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // User management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Kategori & Menu
    Route::resource('kategori', KategoriMenuController::class);
    Route::resource('menu', MenuController::class);

    // Supplier & Pembelian
    Route::resource('supplier', SupplierController::class);
    Route::resource('pembelian', PembelianBahanController::class);
    Route::resource('pembelian-detail', DetailPembelianBahanController::class);

    // Stok harian
    Route::resource('stok', StokHarianController::class);

    // Keuangan
    Route::resource('keuangan', KeuanganController::class);
});

// ======================
// KASIR ONLY
// ======================
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos', [POSController::class, 'store'])->name('pos.store');
});

// ======================
// Pesanan (Admin & Kasir)
// ======================
Route::middleware(['auth'])->group(function () {
    Route::resource('pesanan', PesananController::class);
});
