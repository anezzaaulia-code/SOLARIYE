<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailSupllierController extends Controller
{
    public function index()
    {
        return view('admin.pembelian.index', [
            'data' => DetailSupplier::with('supplier')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'nama_bahan' => 'required',
            'jumlah' => 'required|numeric',
            'harga_satuan' => 'required|numeric'
        ]);

        return DB::transaction(function () use ($request) {

            $total = $request->jumlah * $request->harga_satuan;

            $pembelian = DetailSupplier::create([
                'supplier_id' => $request->supplier_id,
                'nama_bahan' => $request->nama_bahan,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'harga_satuan' => $request->harga_satuan,
                'harga_total' => $total,
                'tanggal' => now(),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id()
            ]);

            Keuangan::create([
                'tanggal' => now(),
                'jenis' => 'pengeluaran',
                'sumber' => 'supplier',
                'nominal' => $total,
                'keterangan' => "Pembelian bahan $request->nama_bahan",
                'ref_id' => $pembelian->id,
                'created_by' => auth()->id()
            ]);

            return back()->with('success', 'Pembelian bahan dicatat');
        });
    }
}
