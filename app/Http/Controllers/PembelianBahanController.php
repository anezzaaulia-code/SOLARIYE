<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\Supplier;
use App\Models\DetailPembelianBahan;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')->orderBy('tanggal', 'desc')->get();
        return view('pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();
        return view('pembelian.create', compact('suppliers', 'bahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'bahan_id.*' => 'required|exists:bahan_baku,id',
            'jumlah.*' => 'required|numeric|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $pembelian = PembelianBahan::create([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0,
            ]);

            $total = 0;
            foreach ($request->bahan_id as $i => $bahanId) {
                $jumlah = $request->jumlah[$i];
                $harga = $request->harga_satuan[$i];
                $subTotal = $jumlah * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id' => $bahanId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'total_harga' => $subTotal,
                ]);

                $total += $subTotal;
            }

            $pembelian->update(['total_harga' => $total]);
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dibuat.');
    }

    public function show($id)
    {
        $pembelian = PembelianBahan::with(['supplier', 'detailPembelian.bahan'])->findOrFail($id);
        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(PembelianBahan $pembelian)
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();
        $detail = $pembelian->detailPembelian;
        return view('pembelian.edit', compact('pembelian', 'suppliers', 'bahan', 'detail'));
    }

    public function update(Request $request, PembelianBahan $pembelian)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'bahan_id.*' => 'required|exists:bahan_baku,id',
            'jumlah.*' => 'required|numeric|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $pembelian) {
            $pembelian->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
            ]);

            // Hapus detail lama
            $pembelian->detailPembelian()->delete();

            // Tambah detail baru
            $total = 0;
            foreach ($request->bahan_id as $i => $bahanId) {
                $jumlah = $request->jumlah[$i];
                $harga = $request->harga_satuan[$i];
                $subTotal = $jumlah * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id' => $bahanId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'total_harga' => $subTotal,
                ]);

                $total += $subTotal;
            }

            $pembelian->update(['total_harga' => $total]);
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diupdate.');
    }

    public function destroy(PembelianBahan $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            $pembelian->detailPembelian()->delete();
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
