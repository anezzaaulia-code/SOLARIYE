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
        // Jika kasir membuka dashboard → langsung ke halaman POS.
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('kasir.pos.index');
        }

        // ==== Dashboard Admin ====
        $today = now()->toDateString();

        // Pemasukan hari ini
        $pemasukanHari = Keuangan::where('jenis', 'pemasukan')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        // Pengeluaran hari ini
        $pengeluaranHari = Keuangan::where('jenis', 'pengeluaran')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        // 5 Menu Terlaris Minggu Ini
        $topMenus = PesananDetail::selectRaw('menu_id, SUM(jumlah) as total_qty')
            ->whereHas('pesanan', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->limit(5)
            ->get();

        // Stok Menipis (≤ 10)
        $stokMenipis = BahanBaku::where('stok', '<=', 10)
            ->orderBy('stok')
            ->limit(10)
            ->get();

        // Grafik Pemasukan 7 Hari
        $chart = Keuangan::selectRaw("
                DATE(tanggal) as tanggal,
                SUM(CASE WHEN jenis='pemasukan' THEN nominal ELSE 0 END) as pemasukan
            ")
            ->where('tanggal', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();

        return view('admin.dashboard', compact(
            'pemasukanHari',
            'pengeluaranHari',
            'topMenus',
            'stokMenipis',
            'chart'
        ));
    }
}
