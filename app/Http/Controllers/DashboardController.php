<?php

namespace App\Http\Controllers;

use App\Models\PesananDetail;
use App\Models\BahanBaku;
use App\Models\Pesanan;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // today's summary
        $today = now()->toDateString();

        $pemasukanHari = Keuangan::where('jenis','pemasukan')->whereDate('tanggal', $today)->sum('nominal');
        $pengeluaranHari = Keuangan::where('jenis','pengeluaran')->whereDate('tanggal', $today)->sum('nominal');

        // top selling menus (by qty) - last 7 days
        $topMenus = PesananDetail::selectRaw('menu_id, SUM(jumlah) as total_qty')
            ->whereHas('pesanan', function($q) {
                $q->where('created_at','>=', now()->subDays(7));
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->limit(5)
            ->get();

        // stok menipis
        $stokMenipis = BahanBaku::where('stok_akhir','<=', 10)->orderBy('stok_akhir','asc')->limit(10)->get();

        // chart data (7 days income)
        $chart = Keuangan::selectRaw("DATE(tanggal) as tanggal, SUM(CASE WHEN jenis='pemasukan' THEN nominal ELSE 0 END) as pemasukan")
            ->where('tanggal','>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();

        return view('dashboard.index', compact('pemasukanHari','pengeluaranHari','topMenus','stokMenipis','chart'));
    }
}
