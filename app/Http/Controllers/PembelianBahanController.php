<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id',
            'tanggal' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {

            $pembelian = PembelianBahan::create([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0, // akan diupdate oleh detail
            ]);

            return redirect()->route('pembelian.show', $pembelian->id)
                ->with('success', 'Pembelian berhasil dibuat, silakan tambah detail.');
        });
    }
}
