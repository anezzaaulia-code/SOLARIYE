<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// ===============================
// ROOT
// ===============================
Route::get('/', fn() => redirect()->route('dashboard'));


// ===============================
// DASHBOARD (Admin + Kasir)
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


// ===============================
// ADMIN ONLY
// ===============================
// Penting: gunakan 'admin' sesuai alias di bootstrap/app.php
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    Route::resource('kategori', KategoriMenuController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('pembelian', PembelianBahanController::class);
    Route::resource('pembelian-detail', DetailPembelianBahanController::class);
    Route::resource('stok', StokHarianController::class);
    Route::resource('keuangan', KeuanganController::class);
});


// ===============================
// KASIR ONLY
// ===============================
// Penting: gunakan 'kasir' sesuai alias di bootstrap/app.php
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos', [POSController::class, 'store'])->name('pos.store');
});


// ===============================
// PESANAN (Admin + Kasir)
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::resource('pesanan', PesananController::class);
});


// ===============================
// LOGOUT
// ===============================
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/pendapatan', [KeuanganController::class, 'pendapatan'])->name('pendapatan.index');
Route::get('/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('pengeluaran.index');
Route::get('/laporan-keuangan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
Route::get('/admin/keuangan/laporan', [KeuanganController::class, 'laporan'])
    ->name('keuangan.laporan');
Route::get('/admin/keuangan/pengeluaran', [KeuanganController::class, 'pengeluaran'])
    ->name('keuangan.pengeluaran');

    // EXPORT PENGELUARAN
Route::get('/keuangan/export-pengeluaran', [KeuanganController::class, 'exportPengeluaran'])
    ->name('keuangan.exportPengeluaran')
    ->middleware('admin');


