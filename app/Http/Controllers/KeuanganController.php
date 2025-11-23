<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $q = Keuangan::query();

        if ($request->filled('jenis')) {
            $q->where('jenis', $request->jenis);
        }

        if ($request->filled('sumber')) {
            $q->where('sumber', $request->sumber);
        }

        if ($request->filled('from')) {
            $q->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('tanggal', '<=', $request->to);
        }

        $keuangan = $q->orderBy('tanggal','desc')->paginate(30);
        return view('keuangan.index', compact('keuangan'));
    }

    public function show(Keuangan $keuangan)
    {
        return view('keuangan.show', compact('keuangan'));
    }

    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();
        return back()->with('success','Catatan keuangan dihapus.');
    }
}
