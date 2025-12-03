<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $bahan = BahanBaku::orderBy('nama_bahan')->get(); 
        return view('admin.stokharian.create', compact('bahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'tanggal' => 'required|date',
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        // Cari stok terakhir sebelum hari ini (untuk stok_awal)
        // Jika tidak ada, anggap 0 atau ambil stok saat ini dari tabel bahan
        $stokTerakhir = StokHarian::where('bahan_id', $request->bahan_id)
            ->whereDate('tanggal', '<', $request->tanggal)
            ->orderBy('tanggal', 'desc')
            ->value('stok_akhir');
        
        // Jika input manual stok awal kosong, pakai logika otomatis
        $stok_awal = $request->stok_awal ?? ($stokTerakhir ?? $bahan->stok);
        $stok_akhir = $request->stok_akhir;

        // Tentukan Status Warna
        $status = 'aman';
        if ($stok_akhir <= 0) {
            $status = 'habis';
        } elseif ($stok_akhir <= $bahan->batas_merah) {
            $status = 'menipis';
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

        return redirect()->route('stokharian.index')->with('success', 'Stok harian berhasil dicatat.');
    }

    public function edit($id)
    {
        $stok = StokHarian::findOrFail($id);
        $bahan = BahanBaku::all();
        return view('admin.stokharian.edit', compact('stok', 'bahan'));
    }

    // --- BAGIAN YANG TADI ERROR (HILANG) ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $stok = StokHarian::findOrFail($id);
        $bahan = $stok->bahan; // Ambil info bahan terkait (untuk cek batas merah)

        // Update Data
        $stok->stok_akhir = $request->stok_akhir;
        
        // Hitung Ulang Status Warna
        if ($stok->stok_akhir <= 0) {
            $stok->status_warna = 'habis';
        } elseif ($stok->stok_akhir <= $bahan->batas_merah) {
            $stok->status_warna = 'menipis';
        } else {
            $stok->status_warna = 'aman';
        }

        $stok->save();

        return redirect()->route('stokharian.index')
            ->with('success', 'Data stok harian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = StokHarian::findOrFail($id);
        $stok->delete();

        return redirect()->route('stokharian.index')
            ->with('success', 'Data stok harian dihapus.');
    }
}