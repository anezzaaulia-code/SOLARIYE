<?php

namespace App\Http\Controllers;

use App\Models\StokHarian;
use Illuminate\Http\Request;

class StokHarianController extends Controller
{
    public function index()
    {
        return view('admin.stok.index', [
            'stok' => StokHarian::orderBy('tanggal', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'stok_awal' => 'required|numeric',
            'stok_akhir' => 'required|numeric'
        ]);

        $pemakaian = $request->stok_awal - $request->stok_akhir;

        if ($request->stok_akhir == 0) {
            $warna = 'habis';
        } elseif ($request->stok_akhir <= $request->batas_minimal) {
            $warna = 'menipis';
        } else {
            $warna = 'aman';
        }

        StokHarian::create([
            'nama_bahan' => $request->nama_bahan,
            'satuan' => $request->satuan,
            'stok_awal' => $request->stok_awal,
            'stok_akhir' => $request->stok_akhir,
            'pemakaian' => $pemakaian,
            'batas_minimal' => $request->batas_minimal,
            'status_warna' => $warna,
            'tanggal' => now()->toDateString(),
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Stok bahan berhasil diperbarui');
    }
}
