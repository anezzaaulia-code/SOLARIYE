<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Controllers\PesananController;

class POSController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth','kasir']);
    // }

    public function index()
    {
        $menus = Menu::where('status','tersedia')->orderBy('nama')->get();
        return view('kasir.pos.index', compact('menus'));
    }

    public function store(Request $request)
    {
        // delegate to PesananController store
        $pesananController = app()->make(PesananController::class);
        return $pesananController->store($request);
    }

    public function riwayat()
    {
        // Ambil transaksi berdasarkan kasir yang login
        $riwayat = Transaksi::where('kasir_id', auth()->id())
                    ->latest()
                    ->get();

        return view('kasir.riwayat.index', compact('riwayat'));
    }

}
