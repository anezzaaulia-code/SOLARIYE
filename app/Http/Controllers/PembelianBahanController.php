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

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.pembelian.index', compact('pembelian'));
    }

    // ==============================
    // FORM CREATE
    // ==============================
    public function create()
    {
        return view('admin.pembelian.create', [
            'suppliers' => Supplier::all(),
            'bahan'     => BahanBaku::all(),
        ]);
    }

    // ==============================
    // STORE PEMBELIAN
    // ==============================
    public function store(Request $request)
    {
        // VALIDASI DASAR
        $request->validate([
            'tanggal'        => 'required|date',
            'supplier_id'    => 'required_without:supplier_baru|nullable|exists:suppliers,id',
            'supplier_baru'  => 'required_without:supplier_id|nullable|string|max:100',

            'bahan_id'       => 'required|array',
            'qty'            => 'required|array',
            'harga_satuan'   => 'required|array',
            'satuan'         => 'required|array',
            'bahan_baru'     => 'nullable|array',
        ], [
            'supplier_id.required_without'   => 'Pilih supplier atau isi supplier baru.',
            'supplier_baru.required_without' => 'Isi supplier baru jika tidak memilih supplier.',
        ]);

        // ==============================
        // VALIDASI SUPPLIER DUPLIKAT
        // ==============================
        if (!empty($request->supplier_baru)) {
            $cekSupplier = Supplier::where('nama_supplier', 'LIKE', $request->supplier_baru)->first();
            if ($cekSupplier) {
                return back()->withInput()->with(
                    'error',
                    "Supplier '{$request->supplier_baru}' sudah terdaftar! Silakan pilih dari dropdown."
                );
            }
        }

        // ==============================
        // VALIDASI BAHAN BARU DUPLIKAT
        // ==============================
        foreach ($request->bahan_baru as $i => $namaBaru) {
            if (!empty($namaBaru)) {
                $cek = BahanBaku::where('nama_bahan', 'LIKE', $namaBaru)->first();
                if ($cek) {
                    return back()->withInput()->with(
                        'error',
                        "Bahan '{$namaBaru}' sudah terdaftar (baris ke-" . ($i + 1) . "). Gunakan dropdown."
                    );
                }
            }
        }

        // ==============================
        // SIMPAN PEMBELIAN
        // ==============================
        DB::beginTransaction();
        try {
            // SIMPAN SUPPLIER
            if (!empty($request->supplier_baru)) {
                $supplier = Supplier::create([
                    'nama_supplier' => $request->supplier_baru,
                ]);
                $supplierId = $supplier->id;
            } else {
                $supplierId = $request->supplier_id;
            }

            // HEADER PEMBELIAN
            $pembelian = PembelianBahan::create([
                'tanggal'     => $request->tanggal,
                'supplier_id' => $supplierId,
                'total_harga' => 0,
                'created_by'  => auth()->id(),
            ]);

            $grandTotal = 0;

            // ==============================
            // SIMPAN DETAIL PEMBELIAN
            // ==============================
            foreach ($request->bahan_id as $i => $dropdownId) {
                $qty    = (int)$request->qty[$i];
                $harga  = (int)$request->harga_satuan[$i];
                $satuan = $request->satuan[$i] ?? 'pcs';
                $baru   = $request->bahan_baru[$i] ?? null;

                if ($qty <= 0 || $harga < 0) continue;

                // Tentukan bahan yang digunakan
                if (!empty($dropdownId)) {
                    $finalBahanId = $dropdownId;
                } elseif (!empty($baru)) {
                    $bahan = BahanBaku::create([
                        'nama_bahan' => $baru,
                        'satuan'     => $satuan,
                        'stok'       => 0,
                    ]);
                    $finalBahanId = $bahan->id;
                } else {
                    throw new \Exception("Baris ke-" . ($i + 1) . ": Harap pilih bahan atau isi bahan baru.");
                }

                $subtotal = $qty * $harga;
                $grandTotal += $subtotal;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id'           => $finalBahanId,
                    'qty'                => $qty,
                    'harga_satuan'       => $harga,
                    'subtotal'           => $subtotal,
                ]);

                // UPDATE STOK
                $bahan = BahanBaku::find($finalBahanId);
                $bahan->stok += $qty;
                $bahan->save();
            }

            // UPDATE TOTAL
            $pembelian->update(['total_harga' => $grandTotal]);

            // CATAT KEUANGAN
            Keuangan::create([
                'tanggal'    => $request->tanggal,
                'jenis'      => 'pengeluaran',
                'sumber'     => 'pembelian_bahan',
                'nominal'    => $grandTotal,
                'keterangan' => 'Pembelian bahan baku dari ' . $pembelian->supplier->nama_supplier,
                'ref_id'     => $pembelian->id,
                'ref_table'  => 'pembelian_bahan',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    // ==============================
    // SHOW
    // ==============================
    public function show($id)
    {
        $pembelian = PembelianBahan::with(['supplier', 'detailPembelian.bahan'])
            ->findOrFail($id);

        return view('admin.pembelian.show', compact('pembelian'));
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        return view('admin.pembelian.edit', [
            'pembelian' => PembelianBahan::with('detailPembelian')->findOrFail($id),
            'suppliers' => Supplier::all(),
            'bahan'     => BahanBaku::all(),
        ]);
    }

    // ==============================
    // UPDATE PEMBELIAN
    // ==============================
    public function update(Request $request, $id)
    {
        $pembelian = PembelianBahan::findOrFail($id);

        $request->validate([
            'tanggal'      => 'required|date',
            'supplier_id'  => 'required|exists:suppliers,id',
            'bahan_id'     => 'required|array',
            'qty'          => 'required|array',
            'harga_satuan' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // ROLLBACK STOK LAMA
            foreach ($pembelian->detailPembelian as $detail) {
                $bahan = BahanBaku::find($detail->bahan_id);
                if ($bahan) {
                    $bahan->stok -= $detail->qty;
                    $bahan->save();
                }
            }

            // HAPUS DETAIL LAMA
            $pembelian->detailPembelian()->delete();

            // UPDATE HEADER
            $pembelian->update([
                'tanggal'     => $request->tanggal,
                'supplier_id' => $request->supplier_id,
            ]);

            $grandTotal = 0;

            // SIMPAN DETAIL BARU
            foreach ($request->bahan_id as $i => $bahanId) {
                $qty   = $request->qty[$i] ?? 0;
                $harga = $request->harga_satuan[$i] ?? 0;

                if ($qty <= 0) continue;

                $subtotal = $qty * $harga;
                $grandTotal += $subtotal;

                DetailPembelianBahan::create([
                    'pembelian_bahan_id' => $pembelian->id,
                    'bahan_id'           => $bahanId,
                    'qty'                => $qty,
                    'harga_satuan'       => $harga,
                    'subtotal'           => $subtotal,
                ]);

                $bahan = BahanBaku::find($bahanId);
                $bahan->stok += $qty;
                $bahan->save();
            }

            // UPDATE TOTAL
            $pembelian->update(['total_harga' => $grandTotal]);

            // UPDATE KEUANGAN
            if ($keu = Keuangan::where('ref_id', $id)->where('ref_table', 'pembelian_bahan')->first()) {
                $keu->update([
                    'tanggal' => $request->tanggal,
                    'nominal' => $grandTotal,
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // ==============================
    // DELETE PEMBELIAN
    // ==============================
    public function destroy($id)
    {
        $pembelian = PembelianBahan::findOrFail($id);

        DB::beginTransaction();
        try {
            // ROLLBACK STOK
            foreach ($pembelian->detailPembelian as $detail) {
                $bahan = BahanBaku::find($detail->bahan_id);
                if ($bahan) {
                    $bahan->stok -= $detail->qty;
                    $bahan->save();
                }
            }

            // HAPUS DATA KEUANGAN
            Keuangan::where('ref_id', $id)->where('ref_table', 'pembelian_bahan')->delete();

            // HAPUS DETAIL & HEADER
            $pembelian->detailPembelian()->delete();
            $pembelian->delete();

            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}
