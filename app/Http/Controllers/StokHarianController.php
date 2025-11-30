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

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'tanggal' => 'required|date',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        StokHarian::updateOrCreate(
            [
                'bahan_id' => $request->bahan_id,
                'tanggal'  => $request->tanggal
            ],
            [
                'stok' => $bahan->stok,
                'status' => $bahan->status,
            ]
        );

        return back()->with('success', 'Stok harian dicatat.');
    }
}
