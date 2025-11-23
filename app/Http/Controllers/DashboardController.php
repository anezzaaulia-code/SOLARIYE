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

    public function index(Request $request)
    {
        // ==========================
        // Redirect jika kasir
        // ==========================
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('pos.index');
        }

        // ==========================
        // Dashboard Admin
        // ==========================
        $today = now()->toDateString();

        // Pendapatan hari ini
        $pemasukanHari = Keuangan::where('jenis', 'pemasukan')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        // Pengeluaran hari ini
        $pengeluaranHari = Keuangan::where('jenis', 'pengeluaran')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        // 5 menu terlaris minggu ini
        $topMenus = PesananDetail::selectRaw('menu_id, SUM(jumlah) as total_qty')
            ->whereHas('pesanan', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->limit(5)
            ->get();

        // ==========================
        // Stok menipis
        // karena tabelmu TIDAK ada stok_akhir
        // jadi aku pake kolom stok
        // ==========================
        $stokMenipis = BahanBaku::where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get();

        // ==========================
        // Grafik pemasukan 7 hari
        // ==========================
        $chart = Keuangan::selectRaw("
                DATE(tanggal) as tanggal,
                SUM(CASE WHEN jenis='pemasukan' THEN nominal ELSE 0 END) as pemasukan
            ")
            ->where('tanggal', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();

        // ==========================
        // RETURN VIEW ADMIN
        // pastikan file: resources/views/admin/dashboard.blade.php
        // ==========================
        return view('admin.dashboard', compact(
            'pemasukanHari',
            'pengeluaranHari',
            'topMenus',
            'stokMenipis',
            'chart'
        ));
    }
}
