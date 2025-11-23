<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokHarian;
use Illuminate\Http\Request;

class StokHarianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'stok_awal' => 'required|numeric|min:0',
            'stok_akhir' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        StokHarian::create([
            'bahan_id' => $request->bahan_id,
            'stok_awal' => $request->stok_awal,
            'stok_akhir' => $request->stok_akhir,
            'tanggal' => $request->tanggal
        ]);

        return back()->with('success', 'Stok harian dicatat.');
    }
}
