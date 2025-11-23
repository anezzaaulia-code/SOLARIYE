<?php

use Illuminate\Support\Facades\Route;
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

// Auth routes (login/logout)
// Auth::routes();

// Dashboard (admin & kasir)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ======================
// User management (Admin only)
// ======================
Route::middleware(['auth','role:admin'])->group(function() {
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

    // Stok Harian
    Route::resource('stok', StokHarianController::class);

    // Keuangan
    Route::resource('keuangan', KeuanganController::class);
});

// ======================
// POS (Kasir only)
// ======================
Route::middleware(['auth','role:kasir'])->group(function() {
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos', [POSController::class, 'store'])->name('pos.store');
});

// ======================
// Pesanan (Admin & Kasir)
// ======================
Route::middleware(['auth'])->group(function() {
    Route::resource('pesanan', PesananController::class);
});
