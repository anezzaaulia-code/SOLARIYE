<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesananDetail;
use App\Models\BahanBaku;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // 1. Cek Role: Kasir langsung lempar ke POS
        if ($user->role === 'kasir') {
            return redirect()->route('kasir.pos');
        }

        // 2. Logic Dashboard Admin
        $today = now()->toDateString();

        // Hitung Pemasukan & Pengeluaran Hari Ini
        $pemasukanHari  = Keuangan::where('jenis', 'pemasukan')->whereDate('tanggal', $today)->sum('nominal');
        $pengeluaranHari = Keuangan::where('jenis', 'pengeluaran')->whereDate('tanggal', $today)->sum('nominal');

        // 5 Menu Terlaris (7 Hari Terakhir)
        // PERBAIKAN: Gunakan SUM(jumlah) karena kolom di db adalah 'jumlah'
        $topMenus = PesananDetail::select('menu_id', DB::raw('SUM(jumlah) as total_qty')) 
            ->whereHas('pesanan', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->limit(5)
            ->get();

        // Stok Bahan Menipis (Batas <= 10)
        $stokMenipis = BahanBaku::whereColumn('stok', '<=', 'batas_merah')
            ->orWhere('stok', '<=', 10) 
            ->orderBy('stok')
            ->limit(10)
            ->get();

        // Grafik Keuangan (7 Hari Terakhir)
        $chart = Keuangan::selectRaw("
                DATE(tanggal) as tgl,
                SUM(CASE WHEN jenis='pemasukan' THEN nominal ELSE 0 END) as total_masuk,
                SUM(CASE WHEN jenis='pengeluaran' THEN nominal ELSE 0 END) as total_keluar
            ")
            ->where('tanggal', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tgl')
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