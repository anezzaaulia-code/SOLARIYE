<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembelianBahan;
use App\Models\Supplier;
use App\Models\DetailPembelianBahan;
use App\Models\BahanBaku;
use App\Models\StokHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')->orderBy('tanggal','desc')->get();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $bahan = BahanBaku::all();
        return view('admin.pembelian.create', compact('suppliers', 'bahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'bahan_id'     => 'required|array',
            'qty'          => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Supplier
            $supplierId = $request->supplier_id;
            if ($request->supplier_baru) {
                $supp = Supplier::create(['nama_supplier' => $request->supplier_baru]);
                $supplierId = $supp->id;
            }

            // 2. Create Header
            $pembelian = PembelianBahan::create([
                'tanggal'     => $request->tanggal,
                'supplier_id' => $supplierId,
                'total_harga' => 0,
                'created_by'  => auth()->id(),
            ]);

            $total = 0;

            // 3. Loop Detail
            foreach ($request->bahan_id as $i => $bahanId) {
                // Skip kosong
                if (!$bahanId && empty($request->bahan_baru[$i])) continue;

                // Handle Bahan Baru
                if (!$bahanId && !empty($request->bahan_baru[$i])) {
                    $newBahan = BahanBaku::create([
                        'nama_bahan'  => $request->bahan_baru[$i],
                        'stok'        => 0,
                        'batas_merah' => 5,
                        'satuan'      => $request->satuan[$i] ?? 'pcs',
                    ]);
                    $bahanId = $newBahan->id;
                }

                $qty = $request->qty[$i];
                $harga = $request->harga_satuan[$i];
                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id'           => $bahanId,
                    'qty'                => $qty,
                    'harga_satuan'       => $harga,
                    'subtotal'           => $subtotal,
                ]);

                // Update Stok Realtime
                $bahanObj = BahanBaku::find($bahanId);
                $bahanObj->stok += $qty;
                $bahanObj->save();

                // Update Stok Harian (Optional Logic)
                $this->updateStokHarian($bahanId, $request->tanggal, $bahanObj);

                $total += $subtotal;
            }

            $pembelian->update(['total_harga' => $total]);
            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    
    // Fungsi Helper untuk Stok Harian agar codingan store tidak kepanjangan
    private function updateStokHarian($bahanId, $tanggal, $bahanObj) {
        $stok = StokHarian::where('bahan_id', $bahanId)->where('tanggal', $tanggal)->first();
        
        // Logic sederhana: Update stok akhir sesuai stok real saat ini
        StokHarian::updateOrCreate(
            ['bahan_id' => $bahanId, 'tanggal' => $tanggal],
            [
                'stok_awal'    => $stok ? $stok->stok_awal : ($bahanObj->stok - $bahanObj->stok), // Fallback logic
                'stok_akhir'   => $bahanObj->stok,
                'status_warna' => $bahanObj->stok <= 0 ? 'habis' : ($bahanObj->stok <= $bahanObj->batas_merah ? 'menipis' : 'aman')
            ]
        );
    }
    
    public function show($id) {
         $pembelian = PembelianBahan::with(['supplier', 'detailPembelian.bahan'])->findOrFail($id);
         return view('admin.pembelian.show', compact('pembelian'));
    }

    public function destroy(PembelianBahan $pembelian)
    {
        DB::transaction(function() use ($pembelian) {
            // Rollback Stok dulu sebelum hapus
            foreach($pembelian->detailPembelian as $det) {
                 $b = BahanBaku::find($det->bahan_id);
                 if($b) { $b->stok -= $det->qty; $b->save(); }
            }
            $pembelian->detailPembelian()->delete();
            $pembelian->delete();
        });
        return redirect()->route('pembelian.index')->with('success', 'Pembelian dihapus & stok dikembalikan.');
    }
}