<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananDetailController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'pesanan_id' => 'required|exists:pesanan,id',
            'menu_id' => 'required|exists:menu,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            $menu = Menu::find($data['menu_id']);
            $subtotal = $menu->harga * $data['jumlah'];

            PesananDetail::create([
                'pesanan_id' => $data['pesanan_id'],
                'menu_id' => $data['menu_id'],
                'jumlah' => $data['jumlah'],
                'harga' => $menu->harga,
                'subtotal' => $subtotal,
            ]);

            $pesanan = Pesanan::find($data['pesanan_id']);
            $pesanan->total_harga = $pesanan->detail()->sum('subtotal');
            $pesanan->save();

            // update keuangan related
            $keu = \App\Models\Keuangan::firstOrNew(['ref_id' => $pesanan->id, 'jenis' => 'pemasukan']);
            $keu->tanggal = now()->toDateString();
            $keu->nominal = $pesanan->total_harga;
            $keu->sumber = 'penjualan';
            $keu->keterangan = 'Penjualan Kode:'.$pesanan->kode_pesanan;
            $keu->created_by = auth()->id();
            $keu->save();
        });

        return back()->with('success','Item pesanan ditambahkan.');
    }

    public function destroy(PesananDetail $pesananDetail)
    {
        DB::transaction(function () use ($pesananDetail) {
            $pesanan = $pesananDetail->pesanan;
            $pesananDetail->delete();

            $pesanan->total_harga = $pesanan->detail()->sum('subtotal');
            $pesanan->save();

            \App\Models\Keuangan::where('ref_id', $pesanan->id)->delete();
            if ($pesanan->total_harga > 0) {
                \App\Models\Keuangan::create([
                    'tanggal' => now()->toDateString(),
                    'jenis' => 'pemasukan',
                    'sumber' => 'penjualan',
                    'nominal' => $pesanan->total_harga,
                    'keterangan' => 'Penjualan Kode:'.$pesanan->kode_pesanan,
                    'ref_id' => $pesanan->id,
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return back()->with('success','Item pesanan dihapus.');
    }
}
