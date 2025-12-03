<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Menu;
use App\Models\Keuangan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pesanan = Pesanan::with(['kasir', 'detail'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function store(Request $request, $returnObject = false)
    {
        $request->validate([
            'items'      => 'required|array',
            'total'      => 'required|numeric',
            'bayar'      => 'required|numeric',
            'metode'     => 'required|string',
        ]);

        return DB::transaction(function () use ($request, $returnObject) {
            
            // Header Pesanan
            $pesanan = Pesanan::create([
                'invoice'    => 'INV-' . time(),
                'pelanggan'  => $request->pelanggan ?? 'Umum',
                'nomor_wa'   => $request->nomor_wa,
                'kasir_id'   => auth()->id(),
                'metode'     => $request->metode,
                'status'     => 'selesai',
                'total'      => $request->total,
                'bayar'      => $request->bayar,
                'kembali'    => $request->bayar - $request->total,
            ]);

            // Detail Item
            foreach ($request->items as $item) {
                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id'    => $item['id'],
                    'nama_menu'  => $item['nama'],
                    'harga'      => $item['harga'],
                    
                    // PERBAIKAN: Ganti 'qty' jadi 'jumlah' sesuai nama kolom database
                    'jumlah'     => $item['qty'], 
                    
                    'subtotal'   => $item['harga'] * $item['qty'],
                ]);

                // Update Stok Menu (Opsional)
                // ...
            }

            // Catat Keuangan
            Keuangan::create([
                'jenis'      => 'pemasukan',
                'nominal'    => $pesanan->total,
                'tanggal'    => now(),
                'keterangan' => 'Penjualan POS #' . $pesanan->invoice,
                'ref_id'     => $pesanan->id,
                'sumber'     => 'Penjualan Kasir'
            ]);

            if ($returnObject) {
                return $pesanan;
            }

            return redirect()->route('pesanan.index')->with('success', 'Transaksi berhasil.');
        });
    }

    // ... (method show dan destroy biarkan tetap sama) ...
    public function show($id)
    {
        $pesanan = Pesanan::with(['detail.menu', 'kasir'])->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function destroy(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {
            Keuangan::where('ref_id', $pesanan->id)
                ->where('keterangan', 'like', '%POS%')
                ->delete();

            $pesanan->detail()->delete();
            $pesanan->delete();
        });

        return back()->with('success', 'Pesanan dihapus & data keuangan dibatalkan.');
    }
}