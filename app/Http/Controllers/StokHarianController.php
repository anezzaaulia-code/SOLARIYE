<?php

namespace App\Http\Controllers;

use App\Models\StokHarian;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class StokHarianController extends Controller
{
    public function index()
    {
        $stok = StokHarian::with('bahan')->orderBy('tanggal','desc')->paginate(30);
        return view('stok.index', compact('stok'));
    }

    public function create()
    {
        return view('stok.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'stok_awal' => 'required|integer|min:0',
            'stok_akhir' => 'required|integer|min:0',
        ]);

        // determine status
        $status = 'aman';
        if ($data['stok_akhir'] <= 0) $status = 'habis';
        elseif ($data['stok_akhir'] <= 10) $status = 'menipis';

        $data['status_warna'] = $status;

        StokHarian::create($data);

        // optional: sync bahan stok_akhir to latest
        $b = BahanBaku::find($data['bahan_id']);
        if ($b) {
            $b->stok_awal = $data['stok_awal'];
            $b->stok_akhir = $data['stok_akhir'];
            $b->status_warna = $status;
            $b->save();
        }

        return redirect()->route('stok-harian.index')->with('success','Stok harian disimpan.');
    }

    public function destroy(StokHarian $stokHarian)
    {
        $stokHarian->delete();
        return back()->with('success','Catatan stok harian dihapus.');
    }
}
