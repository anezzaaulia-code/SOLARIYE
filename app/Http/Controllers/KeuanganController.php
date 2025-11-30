<?php 

namespace App\Http\Controllers;

use App\Models\Keuangan;
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
        // jenis bisa: pemasukan / pengeluaran
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

        Keuangan::create([
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'sumber' => $request->sumber,
        ]);

        return redirect()->route(
            $request->jenis === 'pengeluaran' 
                ? 'pengeluaran.index' 
                : 'pendapatan.index'
        )->with('success', 'Data keuangan berhasil ditambahkan.');
        }  // <-- ini yang kurang!

    // PENDAPATAN
    public function pendapatan(Request $request)
    {
        $q = Keuangan::where('jenis', 'pemasukan');

        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);

        $data = $q->orderBy('tanggal', 'desc')->paginate(25);

        $pendapatanHariIni = Keuangan::where('jenis','pemasukan')
            ->whereDate('tanggal', today())->sum('nominal');

        $pendapatanMinggu = Keuangan::where('jenis','pemasukan')
            ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('nominal');

        $totalPendapatan = $q->sum('nominal');

        return view('admin.keuangan.pendapatan', compact(
            'data','pendapatanHariIni','pendapatanMinggu','totalPendapatan'
        ));
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

    // LAPORAN LABA RUGI
    public function labaRugi(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $pemasukan = Keuangan::where('jenis', 'pemasukan')->whereBetween('tanggal', [$from, $to])->sum('nominal');
        $pengeluaran = Keuangan::where('jenis', 'pengeluaran')->whereBetween('tanggal', [$from, $to])->sum('nominal');

        return view('admin.keuangan.labarugi', compact('from','to','pemasukan','pengeluaran'));
    }

    // EXPORT PENGELUARAN
    public function exportPengeluaran(Request $request)
    {
        $q = Keuangan::where('jenis', 'pengeluaran');

        if ($request->filled('from')) $q->whereDate('tanggal', '>=', $request->from);
        if ($request->filled('to')) $q->whereDate('tanggal', '<=', $request->to);

        $data = $q->orderBy('tanggal','desc')->get();

        return response()->json([
            'status' => 'ok',
            'exported' => $data
        ]);
    }
}
