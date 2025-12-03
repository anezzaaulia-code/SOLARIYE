<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    // FORM INPUT
    public function create(Request $request)
    {
        $jenis = $request->jenis ?? null;
        return view('admin.keuangan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'sumber' => 'nullable|string',
        ]);

        Keuangan::create($request->all());

        // FIX REDIRECT ROUTE NAME
        return redirect()->route(
            $request->jenis === 'pengeluaran' 
                ? 'keuangan.pengeluaran' 
                : 'keuangan.pendapatan'
        )->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    // PENDAPATAN
    public function pendapatan(Request $request)
    {
        $q = Keuangan::where('jenis', 'pemasukan');

        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);

        $data = $q->orderBy('tanggal', 'desc')->paginate(25);

        $pendapatanHariIni = Keuangan::where('jenis','pemasukan')->whereDate('tanggal', today())->sum('nominal');
        $pendapatanMinggu = Keuangan::where('jenis','pemasukan')->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->sum('nominal');
        $totalPendapatan = $q->sum('nominal');

        return view('admin.keuangan.pendapatan', compact('data','pendapatanHariIni','pendapatanMinggu','totalPendapatan'));
    }

    // PENGELUARAN
    public function pengeluaran(Request $request)
    {
        $q = Keuangan::where('jenis', 'pengeluaran');

        if ($request->filled('sumber')) $q->where('sumber', $request->sumber);
        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);

        $pengeluaran = $q->orderBy('tanggal','desc')->paginate(20);
        $totalPengeluaran = $q->sum('nominal');

        return view('admin.keuangan.pengeluaran', compact('pengeluaran','totalPengeluaran'));
    }

    // LAPORAN KEUANGAN
    public function laporan(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $pendapatan = Keuangan::where('jenis','pemasukan')->whereBetween('tanggal',[$from,$to])->sum('nominal');
        $pengeluaran = Keuangan::where('jenis','pengeluaran')->whereBetween('tanggal',[$from,$to])->sum('nominal');
        $list = Keuangan::whereBetween('tanggal',[$from,$to])->orderBy('tanggal','desc')->get();

        return view('admin.keuangan.laporan', compact('from','to','pendapatan','pengeluaran','list'));
    }

    public function labaRugi(Request $request)
    {
        // Default: Bulan ini
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        // Hitung Total
        $pemasukan = Keuangan::where('jenis', 'pemasukan')
            ->whereBetween('tanggal', [$from, $to])
            ->sum('nominal');

        $pengeluaran = Keuangan::where('jenis', 'pengeluaran')
            ->whereBetween('tanggal', [$from, $to])
            ->sum('nominal');

        $labaRugi = $pemasukan - $pengeluaran;

        return view('admin.keuangan.labarugi', compact('from', 'to', 'pemasukan', 'pengeluaran', 'labaRugi'));
    }

    // EXPORT
    public function exportPengeluaran(Request $request)
    {
        $q = Keuangan::where('jenis', 'pengeluaran');
        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);
        
        return response()->json(['status' => 'ok', 'exported' => $q->get()]);
    }
}