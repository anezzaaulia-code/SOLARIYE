<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianBahan;
use App\Models\PembelianBahan;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPembelianBahanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'pembelian_id' => 'required|exists:pembelian_bahan,id',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = $data['jumlah'] * $data['harga_satuan'];
            $detail = DetailPembelianBahan::create([
                'pembelian_id' => $data['pembelian_id'],
                'bahan_id' => $data['bahan_id'],
                'jumlah' => $data['jumlah'],
                'harga_satuan' => $data['harga_satuan'],
                'subtotal' => $subtotal,
            ]);

            // tambah ke total header
            $header = PembelianBahan::find($data['pembelian_id']);
            $header->total_harga = ($header->total_harga ?? 0) + $subtotal;
            $header->save();

            // update stok bahan
            $b = BahanBaku::find($data['bahan_id']);
            if ($b) {
                $b->stok_akhir = ($b->stok_akhir ?? 0) + $data['jumlah'];
                $b->save();
            }

            // update/insert keuangan pengeluaran
            $keu = \App\Models\Keuangan::firstOrNew([
                'jenis' => 'pengeluaran',
                'sumber' => 'supplier',
                'ref_id' => $header->id
            ]);
            $keu->tanggal = $header->tanggal;
            $keu->nominal = \App\Models\DetailPembelianBahan::where('pembelian_id', $header->id)->sum('subtotal');
            $keu->keterangan = 'Pembelian bahan ID:'.$header->id;
            $keu->created_by = auth()->id();
            $keu->save();
        });

        return back()->with('success','Detail pembelian ditambahkan.');
    }

    public function destroy(DetailPembelianBahan $detailPembelianBahan)
    {
        DB::transaction(function () use ($detailPembelianBahan) {
            // revert stok
            $b = BahanBaku::find($detailPembelianBahan->bahan_id);
            if ($b) {
                $b->stok_akhir = max(0, ($b->stok_akhir ?? 0) - $detailPembelianBahan->jumlah);
                $b->save();
            }

            $pemb = $detailPembelianBahan->pembelian;
            $detailPembelianBahan->delete();

            // recalc header total
            $pemb->total_harga = $pemb->detail()->sum('subtotal');
            $pemb->save();

            // update keuangan
            \App\Models\Keuangan::where('ref_id', $pemb->id)->delete();
            if ($pemb->total_harga > 0) {
                \App\Models\Keuangan::create([
                    'tanggal' => $pemb->tanggal,
                    'jenis' => 'pengeluaran',
                    'sumber' => 'supplier',
                    'nominal' => $pemb->total_harga,
                    'keterangan' => 'Pembelian bahan ID:'.$pemb->id,
                    'ref_id' => $pemb->id,
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return back()->with('success','Detail pembelian dihapus dan stok direvert.');
    }
}
