<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DetailPembelianBahan;
use App\Models\Keuangan;
use App\Models\PembelianBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPembelianBahanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelian_bahan,id',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $subtotal = $request->jumlah * $request->harga_satuan;

            // Simpan detail
            $detail = DetailPembelianBahan::create([
                'pembelian_id' => $request->pembelian_id,
                'bahan_id' => $request->bahan_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'subtotal' => $subtotal,
            ]);

            // Update stok bahan
            $bahan = BahanBaku::find($request->bahan_id);
            $bahan->stok += $request->jumlah;
            $bahan->save();

            // Update TOTAL pembelian
            $header = PembelianBahan::find($request->pembelian_id);
            $header->total_harga = DetailPembelianBahan::where('pembelian_id', $header->id)->sum('subtotal');
            $header->save();

            // Update atau buat keuangan
            $keu = Keuangan::firstOrCreate(
                ['jenis' => 'pengeluaran', 'ref_id' => $header->id],
                ['nominal' => 0]
            );

            $keu->nominal = $header->total_harga;
            $keu->tanggal = $header->tanggal;
            $keu->save();
        });

        return back()->with('success', 'Detail pembelian ditambahkan.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $detail = DetailPembelianBahan::findOrFail($id);
            $pembelian = PembelianBahan::find($detail->pembelian_id);

            // Kembalikan stok
            $bahan = BahanBaku::find($detail->bahan_id);
            $bahan->stok -= $detail->jumlah;
            $bahan->save();

            // Hapus detail
            $detail->delete();

            // Update total pembelian
            $pembelian->total_harga = DetailPembelianBahan::where('pembelian_id', $pembelian->id)->sum('subtotal');
            $pembelian->save();

            // Update keuangan (tidak dihapus, cukup diupdate)
            $keu = Keuangan::where('jenis', 'pengeluaran')
                ->where('ref_id', $pembelian->id)
                ->first();

            if ($keu) {
                $keu->nominal = $pembelian->total_harga;
                $keu->save();
            }
        });

        return back()->with('success', 'Detail pembelian dihapus.');
    }
}
