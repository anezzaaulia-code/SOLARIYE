<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\DetailPembelianBahan;
use App\Models\BahanBaku;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')->orderBy('tanggal','desc')->paginate(20);
        return view('pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        // view harus menyediakan list supplier & bahan
        return view('pembelian.create');
    }

    /**
     * Expect request:
     * - supplier_id
     * - tanggal
     * - items => array of [bahan_id, jumlah, harga_satuan]
     * - keterangan (opt)
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.bahan_id' => 'required|exists:bahan_baku,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            // create header
            $pembelian = PembelianBahan::create([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $it) {
                $subtotal = $it['jumlah'] * $it['harga_satuan'];
                $total += $subtotal;

                DetailPembelianBahan::create([
                    'pembelian_id' => $pembelian->id,
                    'bahan_id' => $it['bahan_id'],
                    'jumlah' => $it['jumlah'],
                    'harga_satuan' => $it['harga_satuan'],
                    'subtotal' => $subtotal,
                ]);

                // update stok_akhir di bahan_baku (tambah stok)
                $bahan = BahanBaku::find($it['bahan_id']);
                if ($bahan) {
                    $bahan->stok_akhir = ($bahan->stok_akhir ?? 0) + $it['jumlah'];
                    $bahan->save();
                }
            }

            // update total di header
            $pembelian->total_harga = $total;
            $pembelian->save();

            // buat keuangan (pengeluaran)
            Keuangan::create([
                'tanggal' => $pembelian->tanggal,
                'jenis' => 'pengeluaran',
                'sumber' => 'supplier',
                'nominal' => $total,
                'keterangan' => 'Pembelian bahan ID:'.$pembelian->id,
                'ref_id' => $pembelian->id,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('pembelian-bahan.index')->with('success','Pembelian disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error','Gagal simpan pembelian: '.$e->getMessage());
        }
    }

    public function show(PembelianBahan $pembelianBahan)
    {
        $pembelianBahan->load('detail.bahan','supplier','user');
        return view('pembelian.show', compact('pembelianBahan'));
    }

    public function destroy(PembelianBahan $pembelianBahan)
    {
        // Hati-hati: menghapus pembelian sebaiknya adjust stok & keuangan
        DB::transaction(function () use($pembelianBahan) {
            // revert stok
            foreach ($pembelianBahan->detail as $d) {
                $b = BahanBaku::find($d->bahan_id);
                if ($b) {
                    $b->stok_akhir = max(0, ($b->stok_akhir ?? 0) - $d->jumlah);
                    $b->save();
                }
            }
            // hapus keuangan terkait
            \App\Models\Keuangan::where('ref_id', $pembelianBahan->id)
                ->where('jenis','pengeluaran')->delete();

            $pembelianBahan->detail()->delete();
            $pembelianBahan->delete();
        });

        return back()->with('success','Pembelian dihapus dan stok direvert.');
    }
}
