<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan user login
    }

    // -------------------------
    // List semua pembelian
    // -------------------------
    public function index()
    {
        $pembelian = PembelianBahan::with('supplier')->orderBy('tanggal','desc')->get();
        return view('pembelian.index', compact('pembelian'));
    }

    // -------------------------
    // Form tambah pembelian baru
    // -------------------------
    public function create()
    {
        $suppliers = Supplier::all();
        return view('pembelian.create', compact('suppliers'));
    }

    // -------------------------
    // Simpan pembelian baru
    // -------------------------
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $pembelian = PembelianBahan::create([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0, // nanti diupdate lewat detail
            ]);

            return redirect()->route('pembelian.show', $pembelian->id)
                ->with('success', 'Pembelian berhasil dibuat, silakan tambah detail.');
        });
    }

    // -------------------------
    // Tampilkan detail pembelian
    // -------------------------
    public function show(PembelianBahan $pembelian)
    {
        $pembelian->load('supplier', 'detailPembelian'); // relasi detail
        return view('pembelian.show', compact('pembelian'));
    }

    // -------------------------
    // Form edit pembelian
    // -------------------------
    public function edit(PembelianBahan $pembelian)
    {
        $suppliers = Supplier::all();
        return view('pembelian.edit', compact('pembelian', 'suppliers'));
    }

    // -------------------------
    // Update pembelian
    // -------------------------
    public function update(Request $request, PembelianBahan $pembelian)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $pembelian) {
            $pembelian->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                // total_harga tetap diupdate lewat detail jika ada
            ]);
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil diupdate.');
    }

    // -------------------------
    // Hapus pembelian
    // -------------------------
    public function destroy(PembelianBahan $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            // hapus detail dulu kalau ada relasi
            if($pembelian->detailPembelian()->count() > 0){
                $pembelian->detailPembelian()->delete();
            }
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dihapus.');
    }
}
