<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Keuangan;
use App\Models\StokHarian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        return view('admin.dashboard', [
            'pesanan_hari_ini' => Pesanan::whereDate('created_at', $hariIni)->count(),
            'pendapatan_hari_ini' => Keuangan::where('jenis', 'pemasukan')
                ->whereDate('tanggal', $hariIni)
                ->sum('nominal'),

            'pengeluaran_bulan_ini' => Keuangan::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal', now()->month)
                ->sum('nominal'),

            'stok_habis' => StokBahan::where('status_warna', 'habis')->get(),
            'stok_menipis' => StokBahan::where('status_warna', 'menipis')->get(),

            // grafik sederhana
            'grafik_pendapatan' => Keuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', now()->month)
                ->selectRaw('DATE(tanggal) as tgl, SUM(nominal) as total')
                ->groupBy('tgl')
                ->get(),
        ]);
    }
}
