<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


use App\Http\Controllers\AuthController; // GANTI LoginController
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
use App\Http\Controllers\BahanBakuController;
use App\Models\Menu;  



/*
|--------------------------------------------------------------------------
| LOGIN (GUEST)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ]);
    });
});




/*
|--------------------------------------------------------------------------
| ROOT REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth']);

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});



/*
|--------------------------------------------------------------------------
| DASHBOARD (ADMIN + KASIR)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});



/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    Route::resource('kategori', KategoriMenuController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('pembelian', PembelianBahanController::class);
    Route::resource('pembelian', DetailPembelianBahanController::class);
    Route::resource('stok', StokHarianController::class);

    // Keuangan
    Route::get('/keuangan/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('pengeluaran');
    Route::get('/keuangan/pendapatan', [KeuanganController::class, 'pendapatan'])->name('pendapatan');
    Route::get('/keuangan/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
    Route::get('/keuangan/create', [KeuanganController::class, 'create'])->name('keuangan.create');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::get('/keuangan/export-pengeluaran', [KeuanganController::class, 'exportPengeluaran'])->name('keuangan.exportPengeluaran');
});



/*
|--------------------------------------------------------------------------
| KASIR ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'kasir'])->prefix('kasir')->name('kasir.')->group(function () {

    // Dashboard Kasir
    Route::get('/dashboard', function () {
        $menus = Menu::where('status', 'tersedia')->orderBy('nama')->get();
        return view('kasir.dashboard.index', compact('menus'));
    })->name('dashboard');

    // POS
    Route::get('/', [POSController::class, 'index'])->name('pos');
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [POSController::class, 'store'])->name('pos.store');

    Route::post('/kasir/pos/store', [POSController::class, 'store'])
    ->name('kasir.pos.store');

    // Riwayat transaksi
    Route::get('/riwayat', [POSController::class, 'riwayat'])->name('riwayat');
});



/*
|--------------------------------------------------------------------------
| PESANAN (ADMIN + KASIR)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::resource('pesanan', PesananController::class);
});



/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');


// STOK HARIAN
Route::prefix('stok-harian')->name('stokharian.')->group(function () {
    Route::get('/', [App\Http\Controllers\StokHarianController::class, 'index'])
        ->name('index');
});



// Bahan baku
Route::resource('bahanbaku', BahanBakuController::class);

// Pembelian
Route::resource('pembelian', PembelianBahanController::class);

// Stok Harian
Route::resource('stokharian', StokHarianController::class);

