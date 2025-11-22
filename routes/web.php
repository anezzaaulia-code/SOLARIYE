<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
// homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/', function(){ return redirect()->route('admin.dashboard'); });
Route::prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // other admin routes here later...
});