<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pembeli\HomeController;
use App\Http\Controllers\Pembeli\MenuController;
use App\Http\Controllers\Pembeli\KeranjangController;
use App\Http\Controllers\Pembeli\PesananController;

Route::get('/', function () {
    return view('welcome');
});

// homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// detail menu
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// keranjang
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah/{menu_id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

// pesanan
Route::get('/checkout', [PesananController::class, 'checkout'])->name('pesanan.checkout');
Route::post('/checkout/simpan', [PesananController::class, 'simpan'])->name('pesanan.simpan');
Route::get('/pesanan/selesai/{id}', [PesananController::class, 'selesai'])->name('pesanan.selesai');