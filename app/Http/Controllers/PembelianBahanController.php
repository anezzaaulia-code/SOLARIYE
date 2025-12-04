<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\Supplier;
use App\Models\DetailPembelianBahan;
use App\Models\BahanBaku;
use App\Models\Keuangan;
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
            ->orderBy('tanggal', 'desc')
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

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'bahan_id'      => 'required|array',
            'qty'           => 'required|array',
            'harga_satuan'  => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Supplier baru?
            if ($request->supplier_baru) {
                $supplier = Supplier::create([
                    'nama_supplier' => $request->supplier_baru
                ]);
                $supplierId = $supplier->id;
            } else {
                $supplierId = $request->supplier_id;
            }

            // Header pembelian
            $pembelian = PembelianBahan::create([
                'tanggal'     => $request->tanggal,
                'supplier_id' => $supplierId,
                'total_harga' => 0,
                'created_by'  => auth()->id(),
            ]);

            $total = 0;

            // Loop detail
            foreach ($request->bahan_id as $i => $bahanId) {

                $bahanBaru = $request->bahan_baru[$i] ?? null;
                $qty       = $request->qty[$i] ?? null;
                $harga     = $request->harga_satuan[$i] ?? null;
                $satuan    = $request->satuan[$i] ?? null;

                if ((!$bahanId && !$bahanBaru) || !$qty || !$harga) {
                    continue;
                }

                // Bahan baru?
                if (!$bahanId && $bahanBaru) {
                    $newBahan = BahanBaku::create([
                        'nama_bahan'  => $bahanBaru,
                        'stok'        => 0,
                        'batas_merah' => 5,
                        'satuan'      => $satuan,
                    ]);
                    $bahanId = $newBahan->id;
                }

                // Insert detail
                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id'           => $bahanId,
                    'qty'                => $qty,
                    'harga_satuan'       => $harga,
                    'subtotal'           => $subtotal,
                ]);

                // UPDATE STOK BAHAN
                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();

                $total += $subtotal;
            }

            // Update total pembelian
            $pembelian->update(['total_harga' => $total]);

            // Catat keuangan
            Keuangan::create([
                'tanggal'    => $request->tanggal,
                'jenis'      => 'pengeluaran',
                'sumber'     => 'suppliers',
                'nominal'    => $total,
                'keterangan' => 'Pembelian dari ' . ($pembelian->supplier->nama_supplier ?? 'Supplier'),
                'ref_id'     => $pembelian->id,
                'ref_table'  => 'pembelian',
                'created_by' => auth()->id(),
            ]);

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
            'tanggal'      => 'required|date',
            'supplier_id'  => 'required|exists:suppliers,id',
            'bahan_id'     => 'required|array',
            'qty'          => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Balikkan stok lama
            foreach ($pembelian->detailPembelian as $detail) {
                $bahan = BahanBaku::find($detail->bahan_id);
                $bahan->stok -= $detail->qty;
                $bahan->save();
            }

            // Hapus detail
            $pembelian->detailPembelian()->delete();

            // Update header
            $pembelian->update([
                'tanggal'     => $request->tanggal,
                'supplier_id' => $request->supplier_id,
            ]);

            $total = 0;

            // Insert detail baru
            foreach ($request->bahan_id as $i => $bahanId) {

                $qty   = $request->qty[$i] ?? null;
                $harga = $request->harga_satuan[$i] ?? null;

                if (!$bahanId || !$qty || !$harga) continue;

                $subtotal = $qty * $harga;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id'           => $bahanId,
                    'qty'                => $qty,
                    'harga_satuan'       => $harga,
                    'subtotal'           => $subtotal,
                ]);

                // Update stok
                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();

                $total += $subtotal;
            }

            // Update total pembelian
            $pembelian->update(['total_harga' => $total]);

            // Update catatan keuangan
            $keu = Keuangan::where('ref_id', $pembelian->id)
                ->where('ref_table', 'pembelian')
                ->first();

            if ($keu) {
                $keu->update([
                    'tanggal'    => $request->tanggal,
                    'nominal'    => $total,
                    'keterangan' => 'Update pembelian dari ' . ($pembelian->supplier->nama_supplier ?? 'Supplier'),
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }

    // DELETE
    public function destroy(PembelianBahan $pembelian)
    {
        DB::transaction(function () use ($pembelian) {

            // Hapus catatan keuangan
            Keuangan::where('ref_id', $pembelian->id)
                ->where('ref_table', 'pembelian')
                ->delete();

            // Balikkan stok
            foreach ($pembelian->detailPembelian as $detail) {
                $bahan = BahanBaku::find($detail->bahan_id);
                $bahan->stok -= $detail->qty;
                $bahan->save();
            }

            // Hapus detail & header
            $pembelian->detailPembelian()->delete();
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dihapus.');
    }
}
