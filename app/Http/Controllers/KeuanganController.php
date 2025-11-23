<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('admin.keuangan.index', [
            'keuangan' => Keuangan::orderBy('tanggal', 'desc')->get(),
            'pendapatan' => Keuangan::where('jenis', 'pemasukan')->sum('nominal'),
            'pengeluaran' => Keuangan::where('jenis', 'pengeluaran')->sum('nominal'),
            'laba_rugi' => Keuangan::where('jenis', 'pemasukan')->sum('nominal')
                         - Keuangan::where('jenis', 'pengeluaran')->sum('nominal')
        ]);
    }
}
