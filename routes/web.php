<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// --- 1. IMPORT CONTROLLER UMUM (Tetap di luar) ---
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananController; 
// AuthController opsional, kalau pakai Breeze/Jetstream biasanya otomatis

// --- 2. IMPORT CONTROLLER ADMIN (Namespace Baru) ---
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\KategoriMenuController;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PembelianBahanController;
use App\Http\Controllers\Admin\DetailPembelianBahanController;
use App\Http\Controllers\Admin\StokHarianController;
use App\Http\Controllers\Admin\KeuanganController;

// --- 3. IMPORT CONTROLLER KASIR (Namespace Baru) ---
use App\Http\Controllers\Kasir\POSController;


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { 
        // Redirect pintar: Admin ke Dashboard, Kasir ke POS
        if(auth()->user()->role == 'kasir') return redirect()->route('kasir.pos');
        return redirect()->route('dashboard'); 
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pesanan', PesananController::class);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD & UMUM
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pesanan (Bisa diakses Admin untuk liat, Kasir untuk input)
    // Controller ini ada di folder utama (App\Http\Controllers), bukan Admin/Kasir
    Route::resource('pesanan', PesananController::class);
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Master Data
    Route::resource('kategori-menu', KategoriMenuController::class); 
    Route::resource('menu', MenuController::class);
    Route::resource('bahanbaku', BahanBakuController::class); 

    // Inventory & Supplier
    Route::resource('supplier', SupplierController::class);
    Route::resource('pembelian', PembelianBahanController::class);
    Route::resource('detail-pembelian', DetailPembelianBahanController::class);
    Route::resource('stokharian', StokHarianController::class);

    // Keuangan (Custom Routes)
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index'); 
        Route::get('/create', [KeuanganController::class, 'create'])->name('create');
        Route::post('/store', [KeuanganController::class, 'store'])->name('store');
        
        Route::get('/pendapatan', [KeuanganController::class, 'pendapatan'])->name('pendapatan');
        Route::get('/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('laporan');
        Route::get('/laba-rugi', [KeuanganController::class, 'labaRugi'])->name('labarugi');
        
        Route::get('/export-pengeluaran', [KeuanganController::class, 'exportPengeluaran'])->name('exportPengeluaran');
        
        Route::delete('/{keuangan}', [KeuanganController::class, 'destroy'])->name('destroy');
    });
});


/*
|--------------------------------------------------------------------------
| KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    
    // POS System
    Route::get('/pos', [POSController::class, 'index'])->name('pos');
    Route::post('/pos/store', [POSController::class, 'store'])->name('pos.store');
    Route::get('/pos/struk/{id}', [POSController::class, 'struk'])->name('pos.struk');

    // Riwayat
    Route::get('/riwayat', [POSController::class, 'riwayat'])->name('riwayat');
});