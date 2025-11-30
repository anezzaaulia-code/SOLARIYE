<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\Supplier;
use App\Models\DetailPembelianBahan;
use App\Models\BahanBaku;
use App\Models\StokHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // INDEX
    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')
            ->orderBy('tanggal','desc')
            ->get();

        return view('admin.pembelian.index', compact('pembelian'));
    }

    // FORM CREATE
    public function create()
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();

        return view('admin.pembelian.create', compact('suppliers', 'bahan'));
    }

    // STORE PEMBELIAN
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_baru' => 'nullable|string|max:255',

            'bahan_id.*' => 'nullable|exists:bahan_baku,id',
            'bahan_baru.*' => 'nullable|string|max:255',

            'qty.*' => 'required|numeric|min:1',
            'harga_satuan.*' => 'required|numeric|min:0'
        ]);

        DB::transaction(function() use ($request) {

            // Jika supplier baru
            if ($request->supplier_baru) {
                $supplier = Supplier::create([
                    'nama_supplier' => $request->supplier_baru
                ]);
            } else {
                $supplier = Supplier::find($request->supplier_id);
            }

            $pembelian = PembelianBahan::create([
                'supplier_id' => $supplier->id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0,
                'created_by' => auth()->id()
            ]);

            $total = 0;

            foreach ($request->qty as $i => $qty) {

                // Jika bahan baru
                if (!empty($request->bahan_baru[$i])) {
                    $bahan = BahanBaku::create([
                        'nama_bahan' => $request->bahan_baru[$i],
                        'satuan' => 'pcs',
                        'stok' => 0
                    ]);

                    $bahanId = $bahan->id;
                } else {
                    $bahanId = $request->bahan_id[$i];
                }

                $harga = $request->harga_satuan[$i];
                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id' => $bahanId,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal
                ]);

                // UPDATE STOK BAHAN
                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();

                // UPDATE / CREATE STOK HARIAN
                $stok = StokHarian::firstOrCreate(
                    ['bahan_id' => $bahanId, 'tanggal' => $request->tanggal]
                );

                $stok->stok = $bahan->stok;
                $stok->status = $bahan->status;
                $stok->save();

                $total += $subtotal;
            }

            $pembelian->update(['total_harga' => $total]);
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    // SHOW
    public function show($id)
    {
        $pembelian = PembelianBahan::with(['supplier', 'detailPembelian.bahan'])
            ->findOrFail($id);

        return view('pembelian.show', compact('pembelian'));
    }

    // EDIT
    public function edit(PembelianBahan $pembelian)
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();
        $detail = $pembelian->detailPembelian;

        return view('pembelian.edit', compact('pembelian', 'suppliers', 'bahan', 'detail'));
    }

    // UPDATE
    public function update(Request $request, PembelianBahan $pembelian)
    {
        // (Opsional) Bisa dibuatkan kalau kamu mau versi update yang sinkron stok juga
    }

    // DELETE
    public function destroy(PembelianBahan $pembelian)
    {
        DB::transaction(function() use ($pembelian) {
            $pembelian->detailPembelian()->delete();
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
