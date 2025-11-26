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
            'pembelian_bahan_id' => 'required|exists:pembelian_bahan,id',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'qty' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $subtotal = $request->qty * $request->harga_satuan;

            DetailPembelianBahan::create([
                'pembelian_bahan_id' => $request->pembelian_bahan_id,
                'bahan_id' => $request->bahan_id,
                'qty' => $request->qty,
                'harga_satuan' => $request->harga_satuan,
                'subtotal' => $subtotal,
            ]);

            $bahan = BahanBaku::find($request->bahan_id);
            $bahan->stok += $request->qty;
            $bahan->save();

            $header = PembelianBahan::find($request->pembelian_bahan_id);
            $header->total_harga = DetailPembelianBahan::where('pembelian_bahan_id', $header->id)->sum('subtotal');
            $header->save();

            $keu = Keuangan::firstOrCreate(
                ['jenis' => 'pengeluaran', 'ref_id' => $header->id],
                ['nominal' => 0]
            );

            $keu->nominal = $header->total_harga;
            $keu->tanggal = $header->tanggal;
            $keu->save();
        });

        return back()->with('success', 'Detail pembelian berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $detail = DetailPembelianBahan::findOrFail($id);
            $pembelian = PembelianBahan::find($detail->pembelian_bahan_id);

            $bahan = BahanBaku::find($detail->bahan_id);
            $bahan->stok -= $detail->qty;
            $bahan->save();

            $detail->delete();

            $pembelian->total_harga = DetailPembelianBahan::where('pembelian_bahan_id', $pembelian->id)->sum('subtotal');
            $pembelian->save();

            $keu = Keuangan::where('jenis', 'pengeluaran')
                ->where('ref_id', $pembelian->id)
                ->first();

            if ($keu) {
                $keu->nominal = $pembelian->total_harga;
                $keu->save();
            }
        });

        return back()->with('success', 'Detail pembelian berhasil dihapus.');
    }
}
