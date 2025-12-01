<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokHarian;
use Illuminate\Http\Request;

class StokHarianController extends Controller
{
    public function index()
    {
        $stokharian = StokHarian::with('bahan')
            ->orderBy('tanggal','desc')
            ->get();

        return view('admin.stokharian.index', compact('stokharian'));
    }

        public function create()
    {
        $bahan = BahanBaku::all(); // untuk dropdown pilih bahan

        return view('admin.stokharian.create', compact('bahan'));
    }

    public function edit($id)
    {
        $stok = StokHarian::findOrFail($id);
        $bahan = BahanBaku::all();

        return view('admin.stokharian.edit', compact('stok', 'bahan'));
    }


    public function store(Request $request)
    {
        dd('MASUK STORE');
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'tanggal' => 'required|date',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        // Ambil stok terakhir untuk bahan ini
        $stokTerakhir = StokHarian::where('bahan_id', $request->bahan_id)
            ->orderBy('tanggal', 'desc')
            ->value('stok_akhir');

        // Jika belum ada stok harian sebelumnya
        $stok_awal = $stokTerakhir ?? 0;
        $stok_akhir = $stok_awal; // belum ada pemakaian/penambahan karena harian baru

        // Tentukan status warna berdasarkan stok akhir
        if ($stok_akhir <= 0) {
            $status = 'habis';
        } elseif ($stok_akhir <= $bahan->batas_merah) {
            $status = 'menipis';
        } else {
            $status = 'aman';
        }

        StokHarian::updateOrCreate(
            [
                'bahan_id' => $request->bahan_id,
                'tanggal'  => $request->tanggal
            ],
            [
                'stok_awal'    => $stok_awal,
                'stok_akhir'   => $stok_akhir,
                'status_warna' => $status
            ]
        );

        return back()->with('success', 'Stok harian berhasil dicatat.');
    }

}
