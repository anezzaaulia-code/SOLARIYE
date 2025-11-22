<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function checkout()
    {
        $items = Keranjang::with('menu')->get();
        return view('pembeli.checkout', compact('items'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'alamat' => 'required|string',
        ]);

        $items = Keranjang::with('menu')->get();

        $pesanan = Pesanan::create([
            'nama_pembeli' => $request->nama_pembeli,
            'metode_pembayaran' => $request->metode_pembayaran,
            'alamat' => $request->alamat,
            'ongkir' => $request->ongkir ?? 0,
            'status' => 'menunggu'
        ]);

        foreach ($items as $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'menu_id' => $item->menu_id,
                'jumlah' => $item->jumlah,
                'catatan' => $item->catatan
            ]);
        }

        Keranjang::truncate();

        return redirect()->route('pesanan.selesai', $pesanan->id);
    }

    public function selesai($id)
    {
        $pesanan = Pesanan::with('detail.menu')->findOrFail($id);
        return view('pembeli.selesai', compact('pesanan'));
    }
}
