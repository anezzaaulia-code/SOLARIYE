<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
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

// ========================
// Redirect root → dashboard
// ========================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// =====================
// DASHBOARD (Admin + Kasir)
// =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// =====================
// ADMIN ONLY
// =====================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // User Management
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

// =====================
// KASIR ONLY
// =====================
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos', [POSController::class, 'store'])->name('pos.store');
});

// =====================
// Pesanan (Admin + Kasir)
// =====================
Route::middleware(['auth'])->group(function () {
    Route::resource('pesanan', PesananController::class);
});
// =====================
// LOGOUT (Admin + Kasir)
// =====================

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
