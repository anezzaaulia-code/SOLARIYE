<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    // HALAMAN UTAMA KEUANGAN
    public function index()
    {
        return view('admin.keuangan.index');
    }

    // FORM INPUT
    public function create(Request $request)
    {
        $jenis = $request->jenis ?? null; // pemasukan / pengeluaran
        return view('admin.keuangan.create', compact('jenis'));
    }

    // SIMPAN DATA KEUANGAN
    public function store(Request $request)
    {
        $request->validate([
            'jenis'      => 'required|in:pemasukan,pengeluaran',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'sumber'     => 'nullable|string'
        ]);

        // tentukan sumber berdasarkan jenis transaksi
        if ($request->jenis === 'pemasukan') {
            // pemasukan selalu dari penjualan
            $sumber = 'penjualan';
        } else {
            // pengeluaran → sumber harus sesuai enum di tabel
            $sumber = in_array($request->sumber, ['suppliers', 'lainnya'])
                ? $request->sumber
                : 'lainnya';
        }

        Keuangan::create([
            'tanggal'    => $request->tanggal,
            'jenis'      => $request->jenis,
            'sumber'     => $sumber,               // FIX ENUM 100%
            'nominal'    => $request->nominal,
            'keterangan' => $request->keterangan,
            'ref_id'     => null,
            'ref_table'  => 'none',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route(
            $request->jenis === 'pengeluaran'
                ? 'pengeluaran.index'
                : 'pendapatan.index'
        )->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    // PENDAPATAN
    // PENDAPATAN (Update agar memuat nama pelanggan)
    public function pendapatan(Request $request)
    {
        // Tambahkan with('pesanan') di sini
        $q = Keuangan::with('pesanan')->where('jenis', 'pemasukan');

        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);

        // Gunakan clone untuk menghitung total agar query paginate tidak terganggu
        $totalPendapatan = (clone $q)->sum('nominal');

        $data = $q->orderBy('tanggal', 'desc')->paginate(15); // Ubah 25 jadi 15 agar pas di layar

        $pendapatanHariIni = Keuangan::where('jenis','pemasukan')
            ->whereDate('tanggal', today())->sum('nominal');

        $pendapatanMinggu = Keuangan::where('jenis','pemasukan')
            ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('nominal');

        // Pastikan nama view sesuai dengan lokasi file blade Anda
        // Jika file blade Anda ada di resources/views/admin/laporan/index.blade.php
        // Maka ganti 'admin.keuangan.pendapatan' menjadi 'admin.laporan.index'
        return view('admin.keuangan.pendapatan', compact(
            'data','pendapatanHariIni','pendapatanMinggu','totalPendapatan'
        ));
    }

    // PENGELUARAN
    public function pengeluaran(Request $request)
    {
        $q = Keuangan::where('jenis', 'pengeluaran');

        // Filter Tanggal
        if ($request->filled('from')) {
            $q->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('tanggal', '<=', $request->to);
        }

        // Hitung Statistik
        $totalPengeluaran = (clone $q)->sum('nominal'); // Total sesuai filter yang dipilih

        $pengeluaranHariIni = Keuangan::where('jenis', 'pengeluaran')
            ->whereDate('tanggal', now())
            ->sum('nominal');

        $pengeluaranMingguIni = Keuangan::where('jenis', 'pengeluaran')
            ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('nominal');

        // Ambil Data Tabel
        $pengeluaran = $q->orderBy('tanggal', 'desc')->paginate(15);

        return view('admin.keuangan.pengeluaran', compact(
            'pengeluaran', 
            'totalPengeluaran', 
            'pengeluaranHariIni', 
            'pengeluaranMingguIni'
        ));
    }

    // LAPORAN KEUANGAN
    public function laporan(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        $pendapatan  = Keuangan::where('jenis','pemasukan')->whereBetween('tanggal',[$from,$to])->sum('nominal');
        $pengeluaran = Keuangan::where('jenis','pengeluaran')->whereBetween('tanggal',[$from,$to])->sum('nominal');
        $list        = Keuangan::whereBetween('tanggal',[$from,$to])->orderBy('tanggal','desc')->get();

        return view('admin.keuangan.laporan', compact('from','to','pendapatan','pengeluaran','list'));
    }

    // DETAIL CATATAN
    public function show(Keuangan $keuangan)
    {
        return view('admin.keuangan.show', compact('keuangan'));
    }

    // DELETE
    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();
        return back()->with('success', 'Catatan keuangan dihapus.');
    }

    public function exportPendapatan(Request $request)
    {
        $q = Keuangan::with('pesanan')->where('jenis', 'pemasukan');

        if ($request->filled('from')) {
            $q->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('tanggal', '<=', $request->to);
        }

        $data = $q->orderBy('tanggal', 'desc')->get();

        // 👉 TAMBAHKAN TOTAL
        $totalPendapatan = $data->sum('nominal');

        // 👉 TAMBAHKAN PERIODE
        $from = $request->from;
        $to   = $request->to;

        $pdf = Pdf::loadView('admin.keuangan.pdf.pendapatan', [
            'data'             => $data,
            'totalPendapatan'  => $totalPendapatan,
            'from'             => $from,
            'to'               => $to,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Laporan-Pendapatan.pdf');
    }

    public function exportPengeluaranPDF(Request $request)
    {
        $q = Keuangan::where('jenis', 'pengeluaran');

        if ($request->filled('sumber')) {
            $q->where('sumber', $request->sumber);
        }
        if ($request->filled('from')) {
            $q->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('tanggal', '<=', $request->to);
        }

        $data = $q->orderBy('tanggal', 'desc')->get();

        // 👉 INI YANG KURANG
        $totalPengeluaran = $data->sum('nominal');

        $from = $request->from;
        $to   = $request->to;

        $pdf = Pdf::loadView('admin.keuangan.pdf.pengeluaran', [
            'data'             => $data,
            'totalPengeluaran' => $totalPengeluaran,
            'from'             => $from,
            'to'               => $to,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Laporan-Pengeluaran.pdf');
    }
}
