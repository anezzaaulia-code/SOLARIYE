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
            'bahan_id' => 'required|array',
            'qty' => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $pembelian = PembelianBahan::create([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'total_harga' => 0,
                'created_by' => auth()->id(),
            ]);

            $total = 0;

            foreach ($request->bahan_id as $i => $bahanId) {
                $qty = $request->qty[$i];
                $harga = $request->harga_satuan[$i]; // PERBAIKAN
                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id' => $bahanId,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'total_harga' => $subtotal,
                ]);

                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();

                // Stok Harian
                $stok = StokHarian::where('bahan_id', $bahanId)
                    ->where('tanggal', $request->tanggal)
                    ->first();

                $stok_awal = $stok ? $stok->stok_awal : $bahan->stok - $qty;
                $stok_akhir = $bahan->stok;

                if ($stok_akhir <= 0) {
                    $warna = 'habis';
                } elseif ($stok_akhir <= $bahan->batas_merah) {
                    $warna = 'menipis';
                } else {
                    $warna = 'aman';
                }

                StokHarian::updateOrCreate(
                    ['bahan_id' => $bahanId, 'tanggal' => $request->tanggal],
                    ['stok_awal' => $stok_awal, 'stok_akhir' => $stok_akhir, 'status_warna' => $warna]
                );

                $total += $subtotal;
            }

            $pembelian->update(['total_harga' => $total]);

            DB::commit();
            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }

    // SHOW
    public function show($id)
    {
        $pembelian = PembelianBahan::with(['supplier', 'detailPembelian.bahan'])
            ->findOrFail($id);

        return view('admin.pembelian.show', compact('pembelian'));
    }

    // EDIT
    public function edit(PembelianBahan $pembelian)
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();
        $detail = $pembelian->detailPembelian;

        return view('admin.pembelian.edit', compact('pembelian', 'suppliers', 'bahan', 'detail'));
    }

    // UPDATE
    public function update(Request $request, PembelianBahan $pembelian)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required',
            'bahan_id' => 'required|array',
            'qty' => 'required|array',
            'harga' => 'required|array',
        ]);

        DB::beginTransaction();
        try {

            // Kembalikan stok lama
            foreach ($pembelian->detailPembelian as $detail) {
                $bahan = BahanBaku::find($detail->bahan_id);
                $bahan->stok -= $detail->qty;
                $bahan->save();
            }

            // Hapus detail lama
            $pembelian->detailPembelian()->delete();

            // Update header pembelian
            $pembelian->update([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
            ]);

            $total = 0;

            // Simpan detail baru
            foreach ($request->bahan_id as $i => $bahanId) {

                $qty = $request->qty[$i];
                $harga = $request->harga[$i];
                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id' => $bahanId,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);

                // update stok bahan
                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();

                $total += $subtotal;
            }

            // update total pembelian
            $pembelian->update(['total_harga' => $total]);

            DB::commit();
            return redirect()
                ->route('pembelian.index')
                ->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
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
