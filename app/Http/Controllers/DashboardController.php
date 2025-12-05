<?php

namespace App\Http\Controllers;

use App\Models\PesananDetail;
use App\Models\BahanBaku;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Jika kasir membuka dashboard → diarahkan ke halaman POS
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('kasir.pos.index');
        }

        // ==== Dashboard Admin ====
        $today = now()->toDateString();

        // --------------------------------------------------------
        // PEMASUKAN & PENGELUARAN HARI INI
        // --------------------------------------------------------
        $pemasukanHari = Keuangan::where('jenis', 'pemasukan')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        $pengeluaranHari = Keuangan::where('jenis', 'pengeluaran')
            ->whereDate('tanggal', $today)
            ->sum('nominal');


        // --------------------------------------------------------
        // MENU TERLARIS (7 HARI)
        // --------------------------------------------------------
        $topMenus = PesananDetail::selectRaw('menu_id, SUM(jumlah) as total_qty')
            ->whereHas('pesanan', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();


        // --------------------------------------------------------
        // STATUS STOK BAHAN BAKU (SESUAI HALAMAN BAHAN BAKU)
        // --------------------------------------------------------

        // stok <= batas merah → KRITIS
        $stokKritis = BahanBaku::whereColumn('stok', '<=', 'batas_merah')
            ->orderBy('stok', 'asc')
            ->get();

        // stok > batas merah & stok <= batas kuning → MENIPIS
        $stokMenipis = BahanBaku::whereColumn('stok', '>', 'batas_merah')
            ->whereColumn('stok', '<=', 'batas_kuning')
            ->orderBy('stok', 'asc')
            ->get();

        // stok > batas kuning → AMAN
        $stokAman = BahanBaku::whereColumn('stok', '>', 'batas_kuning')
            ->orderBy('stok', 'asc')
            ->get();


        // --------------------------------------------------------
        // CHART PEMASUKAN 7 HARI
        // --------------------------------------------------------
        $chart = Keuangan::selectRaw("
                DATE(tanggal) as tanggal,
                SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as pemasukan,
                SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as pengeluaran
            ")
            ->where('tanggal', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();


        // --------------------------------------------------------
        // RETURN VIEW
        // --------------------------------------------------------
        return view('admin.dashboard', compact(
            'pemasukanHari',
            'pengeluaranHari',
            'topMenus',

            // stok status
            'stokKritis',
            'stokMenipis',
            'stokAman',

            'chart'
        ));
    }
}
