<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.index', [
            'menu' => Menu::where('status', 'tersedia')->get()
        ]);
    }

    public function createPesanan(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'metode_pembayaran' => 'required',
            'bayar' => 'nullable|numeric'
        ]);

        return DB::transaction(function () use ($request) {

            $nomorNota = 'TRX-' . date('Ymd-His');

            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['harga'] * $item['qty'];
            }

            $pesanan = Pesanan::create([
                'nomor_nota' => $nomorNota,
                'user_id' => auth()->id(),
                'total_harga' => $total,
                'diskon' => $request->diskon ?? 0,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar ? ($request->bayar - $total) : 0,
                'status' => 'selesai',
                'catatan' => $request->catatan
            ]);

            foreach ($request->items as $i) {
                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id' => $i['menu_id'],
                    'nama_menu' => $i['nama'],
                    'qty' => $i['qty'],
                    'harga_satuan' => $i['harga'],
                    'subtotal' => $i['harga'] * $i['qty']
                ]);
            }

            Keuangan::create([
                'tanggal' => now(),
                'jenis' => 'pemasukan',
                'sumber' => 'penjualan',
                'nominal' => $total,
                'keterangan' => "Pendapatan dari pesanan $nomorNota",
                'ref_id' => $pesanan->id,
                'created_by' => auth()->id()
            ]);

            return [
                'success' => true,
                'nomor_nota' => $nomorNota,
                'pesanan_id' => $pesanan->id
            ];
        });
    }
}
