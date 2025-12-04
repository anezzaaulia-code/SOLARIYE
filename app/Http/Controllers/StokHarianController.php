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

    // ============================
    // STORE FINAL + STATUS FIX
    // ============================

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id'   => 'required|exists:bahan_baku,id',
            'tanggal'    => 'required|date',
            'stok_masuk' => 'required|numeric|min:0',
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        // Hitung pemakaian shift
        $pemakaian = $request->stok_masuk - $request->stok_akhir;
        if ($pemakaian < 0) $pemakaian = 0;

        // Stok gudang berkurang sesuai pemakaian
        $bahan->stok -= $pemakaian;
        if ($bahan->stok < 0) $bahan->stok = 0;

        $this->updateStatusBahan($bahan);
        $bahan->save();

        // Simpan stok harian
        StokHarian::create([
            'tanggal'      => $request->tanggal,
            'bahan_id'     => $bahan->id,
            'stok_awal'    => $request->stok_masuk,
            'stok_akhir'   => $request->stok_akhir,
            'pemakaian'    => $pemakaian,
            'status_warna' => $this->getStatusShift($request->stok_awal ?? $request->stok_masuk, $request->stok_akhir, $bahan),
        ]);

        return redirect()->route('stokharian.index')
            ->with('success', 'Stok harian berhasil dicatat.');
    }

    // ============================
    // UPDATE FINAL + STATUS FIX
    // ============================

    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_masuk' => 'required|numeric|min:0',
            'stok_akhir' => 'required|numeric|min:0',
        ]);

        $stok = StokHarian::findOrFail($id);
        $bahan = BahanBaku::findOrFail($stok->bahan_id);

        // Pemakaian lama
        $pemakaian_lama = $stok->pemakaian;

        // Pemakaian baru
        $pemakaian_baru = $request->stok_masuk - $request->stok_akhir;
        if ($pemakaian_baru < 0) $pemakaian_baru = 0;

        // Kembalikan stok lama
        $bahan->stok += $pemakaian_lama;

        // Kurangi stok sesuai pemakaian baru
        $bahan->stok -= $pemakaian_baru;
        if ($bahan->stok < 0) $bahan->stok = 0;

        $this->updateStatusBahan($bahan);
        $bahan->save();

        // Update stok harian
        $stok->update([
            'stok_awal'    => $request->stok_masuk,
            'stok_akhir'   => $request->stok_akhir,
            'pemakaian'    => $pemakaian_baru,
            'status_warna' => $this->getStatusShift($request->stok_masuk, $request->stok_akhir, $bahan),
        ]);

        return redirect()->route('stokharian.index')
            ->with('success', 'Stok harian berhasil diperbarui.');
    }

    // ============================
    // DELETE FINAL
    // ============================

    public function destroy($id)
    {
        $stok = StokHarian::findOrFail($id);
        $bahan = $stok->bahan;

        // Kembalikan pemakaian shift ke stok gudang
        $bahan->stok += $stok->pemakaian;

        if ($bahan->stok < 0) $bahan->stok = 0;

        $this->updateStatusBahan($bahan);
        $bahan->save();

        $stok->delete();

        return redirect()->route('stokharian.index')
            ->with('success', 'Stok harian berhasil dihapus.');
    }

    // ============================
    // STATUS SHIFT (FIXED)
    // ============================

    private function getStatusShift($stok_awal, $stok_akhir, $bahan)
    {
        // Jika stok awal == stok akhir → TIDAK ADA PEMAKAIAN → SHIFT HABIS (SISA TIDAK DITARIK)
        if ($stok_awal == $stok_akhir) {
            return 'habis';
        }

        // Jika tidak ada sisa di shift = benar-benar habis
        if ($stok_akhir <= 0) return 'habis';

        // Menipis jika sisa <= batas kuning
        if ($stok_akhir <= $bahan->batas_kuning) return 'menipis';

        return 'aman';
    }

    // ============================
    // STATUS BAHAN
    // ============================

    private function updateStatusBahan($bahan)
    {
        if ($bahan->stok <= 0) {
            $bahan->status_warna = 'habis';
        } elseif ($bahan->stok <= $bahan->batas_merah) {
            $bahan->status_warna = 'habis';
        } elseif ($bahan->stok <= $bahan->batas_kuning) {
            $bahan->status_warna = 'menipis';
        } else {
            $bahan->status_warna = 'aman';
        }
    }
}
