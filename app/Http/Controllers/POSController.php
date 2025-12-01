<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
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
        try {

            // Panggil pesanan controller
            $pesananController = app()->make(PesananController::class);
            $pesanan = $pesananController->store($request, true); // <-- TRUE = return data, bukan redirect

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'struk_url' => route('kasir.pos.struk', $pesanan->id)
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }


    public function riwayat()
    {
        // Ambil transaksi berdasarkan kasir yang login
        $riwayat = Pesanan::where('kasir_id', auth()->id())
                    ->latest()
                    ->get();

        return view('kasir.riwayat.index', compact('riwayat'));
    }

}
