<?php

namespace App\Http\Controllers\Kasir; // 1. Namespace Baru

use App\Http\Controllers\Controller; // 2. Import Controller Induk
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Http\Controllers\PesananController; // 3. Import PesananController (karena ada di folder luar)

class POSController extends Controller
{
    // Middleware sudah dihandle di Routes, jadi construct ini opsional
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
            // Kita panggil logic simpan dari PesananController
            // Pastikan method store di PesananController punya parameter $returnObject = true
            $pesananController = app()->make(PesananController::class);
            
            // Simpan & minta return object data (bukan redirect)
            $pesanan = $pesananController->store($request, true); 

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'struk_url' => route('kasir.pos.struk', $pesanan->id) // Arahkan ke method struk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // SAYA TAMBAHKAN INI KARENA DIPANGGIL DI STORE (struk_url)
    public function struk($id)
    {
        $pesanan = Pesanan::with(['detail.menu', 'kasir'])->findOrFail($id);
        
        // Pastikan kamu punya view 'kasir.pos.struk'
        // Jika belum punya, buat file view-nya atau return view detail biasa dulu
        return view('kasir.pos.struk', compact('pesanan'));
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