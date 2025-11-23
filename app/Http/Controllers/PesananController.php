<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Menu;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('kasir')->orderBy('created_at','desc')->paginate(30);
        return view('pesanan.index', compact('pesanan'));
    }

    public function create()
    {
        $menus = Menu::where('status','tersedia')->orderBy('nama')->get();
        return view('pesanan.create', compact('menus'));
    }

    /**
     * Request expects:
     * - items: array of [menu_id, jumlah]
     * - metode_bayar: tunai|qris|transfer
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'metode_bayar' => 'required|in:tunai,qris,transfer',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $kode = 'PSN-'.date('YmdHis').'-'.Str::upper(Str::random(4));

            $pesanan = Pesanan::create([
                'kode_pesanan' => $kode,
                'total_harga' => 0,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'menunggu',
                'kasir_id' => auth()->id(),
            ]);

            foreach ($request->items as $it) {
                $menu = Menu::find($it['menu_id']);
                $harga = $menu->harga;
                $jumlah = $it['jumlah'];
                $subtotal = $harga * $jumlah;
                $total += $subtotal;

                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }

            $pesanan->total_harga = $total;
            $pesanan->status = 'diproses';
            $pesanan->save();

            // insert keuangan (pemasukan)
            Keuangan::create([
                'tanggal' => now()->toDateString(),
                'jenis' => 'pemasukan',
                'sumber' => 'penjualan',
                'nominal' => $total,
                'keterangan' => 'Penjualan Kode:'.$pesanan->kode_pesanan,
                'ref_id' => $pesanan->id,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('pesanan.show', $pesanan->id)->with('success','Pesanan dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error','Gagal membuat pesanan: '.$e->getMessage());
        }
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('detail.menu','kasir','keuangan');
        return view('pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
            'metode_bayar' => 'nullable|in:tunai,qris,transfer',
        ]);

        $pesanan->update($data);
        return back()->with('success','Pesanan diupdate.');
    }

    public function destroy(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {
            // hapus keuangan terkait
            Keuangan::where('ref_id', $pesanan->id)->where('jenis','pemasukan')->delete();
            $pesanan->detail()->delete();
            $pesanan->delete();
        });

        return back()->with('success','Pesanan dihapus.');
    }
}
