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
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.stokharian.index', compact('stokharian'));
    }

    public function create()
    {
        $bahan = BahanBaku::all();
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
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'tanggal'  => 'required|date',
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        // Ambil stok terakhir untuk bahan ini
        $stokTerakhir = StokHarian::where('bahan_id', $request->bahan_id)
            ->orderBy('tanggal', 'desc')
            ->value('stok_akhir');

        // Stok awal = stok akhir sebelumnya, jika tidak ada, ambil stok dari tabel bahan
        $stok_awal = $stokTerakhir ?? $bahan->stok;
        $stok_akhir = $request->stok_akhir;

        // Hitung pemakaian
        $pemakaian = $stok_awal - $stok_akhir;

        // Tentukan status
        if ($stok_akhir <= 0) {
            $status = 'habis';
        } elseif ($stok_akhir <= $bahan->batas_merah) {
            $status = 'menipis';
        } else {
            $status = 'aman';
        }

        // Simpan stok harian
        StokHarian::updateOrCreate(
            [
                'bahan_id' => $request->bahan_id,
                'tanggal'  => $request->tanggal
            ],
            [
                'stok_awal'  => $stok_awal,
                'stok_akhir' => $stok_akhir,
                'pemakaian'  => $pemakaian,
                'status_warna'     => $status
            ]
        );

        // Update stok terbaru pada tabel bahan
        $bahan->update([
            'stok' => $stok_akhir
        ]);

        return redirect()->route('stokharian.index')->with('success', 'Stok harian berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $stok = StokHarian::findOrFail($id);
        $bahan = BahanBaku::findOrFail($stok->bahan_id);

        $stok_akhir = $request->stok_akhir;

        // Hitung pemakaian: stok awal tidak berubah
        $pemakaian = $stok->stok_awal - $stok_akhir;

        // Tentukan status
        if ($stok_akhir <= 0) {
            $status = 'habis';
        } elseif ($stok_akhir <= $bahan->batas_merah) {
            $status = 'menipis';
        } else {
            $status = 'aman';
        }

        // Update stok harian
        $stok->update([
            'stok_akhir' => $stok_akhir,
            'pemakaian'  => $pemakaian,
            'status_warna'     => $status
        ]);

        // Update stok pada tabel bahan
        $bahan->update([
            'stok' => $stok_akhir
        ]);

        return redirect()->route('stokharian.index')->with('success', 'Stok harian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = StokHarian::findOrFail($id);
        $stok->delete();

        return redirect()->route('stokharian.index')->with('success', 'Stok harian berhasil dihapus.');
    }
}
