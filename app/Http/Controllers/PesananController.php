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
    public function __construct()
    {
        $this->middleware('auth');
    }

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
     * items => [{menu_id, jumlah}, ...]
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
                'status' => 'diproses',
                'kasir_id' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $harga = $menu->harga;
                $jumlah = $item['jumlah'];
                $subtotal = $harga * $jumlah;
                $total += $subtotal;

                // Buat detail pesanan
                $pesanan->detail()->create([
                    'menu_id' => $menu->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);

                // OPTIONAL: Kurangi stok (kalau sistem pakai stok)
                // jika tidak pakai stok, hapus blok ini
                if ($menu->stok !== null) {
                    $menu->stok -= $jumlah;
                    $menu->save();
                }
            }

            // Update total harga
            $pesanan->total_harga = $total;
            $pesanan->save();

            // Catat pemasukan keuangan
            Keuangan::create([
                'tanggal' => now()->toDateString(),
                'jenis' => 'pemasukan',
                'sumber' => 'penjualan',
                'nominal' => $total,
                'keterangan' => 'Penjualan Kode: '.$pesanan->kode_pesanan,
                'ref_id' => $pesanan->id,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('pesanan.show', $pesanan->id)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pesanan: '.$e->getMessage());
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
        return back()->with('success', 'Pesanan berhasil diupdate.');
    }

    public function destroy(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {

            // Hapus catatan pemasukan keuangan
            Keuangan::where('ref_id', $pesanan->id)
                ->where('jenis', 'pemasukan')
                ->delete();

            // Kembalikan stok jika sebelumnya dikurangi
            foreach ($pesanan->detail as $detail) {
                if ($detail->menu->stok !== null) {
                    $detail->menu->stok += $detail->jumlah;
                    $detail->menu->save();
                }
            }

            // Hapus detail dan pesanan
            $pesanan->detail()->delete();
            $pesanan->delete();
        });

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
