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

    public function store(Request $request, $returnObject = false)
    {
        DB::beginTransaction();

        try {
            // Validasi minimum
            if (!$request->items || count($request->items) == 0) {
                throw new \Exception("Item pesanan tidak ditemukan.");
            }

            // Hitung total
            $total = collect($request->items)
                    ->sum(fn($i) => $i['harga'] * $i['qty']);

            // Simpan pesanan
            $pesanan = Pesanan::create([
                'kode_pesanan'=> 'PSN-' .time() .rand(100,999),
                'pelanggan'   => $request->pelanggan,
                'nomor_wa'    => $request->nomor_wa,
                'kasir_id'    => $request->kasir_id,
                'kasir_nama'  => $request->kasir_nama,
                'metode_bayar'=> $request->metode,
                'bayar'       => $request->bayar,
                'total_harga' => $total,
                'status'      => 'menunggu',
            ]);

            // Detail
            foreach ($request->items as $i) {
                $pesanan->detail()->create([
                    'menu_id' => $i['id'],
                    'nama'    => $i['nama'],
                    'harga'   => $i['harga'],
                    'jumlah'     => $i['qty'],
                    'subtotal'   => $i['harga'] * $i['qty'],
                ]);
            }

            // Keuangan (pemasukan)
            Keuangan::create([
                'tanggal'    => now()->format('Y-m-d'),
                'jenis'      => 'pemasukan',
                'sumber'     => 'penjualan',
                'nominal'    => $total,
                'keterangan' => 'Penjualan #' . $pesanan->id . ' oleh ' . $pesanan->kasir_nama,
                'ref_id'     => $pesanan->id,
                'ref_table'  => 'pesanan',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            if ($returnObject) return $pesanan;

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil disimpan');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; // supaya POSController catch & kirim JSON error
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

            Keuangan::where('ref_id', $pesanan->id)
                ->where('jenis', 'pemasukan')
                ->delete();

            // ✔ FIX ERROR: gunakan qty bukan jumlah
            foreach ($pesanan->detail as $detail) {
                if ($detail->menu && $detail->menu->stok !== null) {
                    $detail->menu->stok += $detail->qty;
                    $detail->menu->save();
                }
            }

            $pesanan->detail()->delete();
            $pesanan->delete();
        });

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
