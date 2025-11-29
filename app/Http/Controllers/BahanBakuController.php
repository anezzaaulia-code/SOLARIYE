<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahan = BahanBaku::orderBy('nama_bahan')->get();
        return view('admin.bahanbaku.index', compact('bahan'));
    }

    public function create()
    {
        return view('admin.bahanbaku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'batas_kuning' => 'required|numeric|min:0',
            'batas_merah' => 'required|numeric|min:0',
        ]);

        BahanBaku::create($request->all());

        return redirect()->route('bahanbaku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bahan = BahanBaku::findOrFail($id);
        return view('admin.bahanbaku.edit', compact('bahan'));
    }


    public function update(Request $request, BahanBaku $bahanbaku)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'batas_kuning' => 'required|numeric|min:0',
            'batas_merah' => 'required|numeric|min:0',
        ]);

        $bahanbaku->update($request->all());

        return redirect()->route('bahanbaku.index')
            ->with('success', 'Bahan baku berhasil diperbarui');
    }

    public function destroy(BahanBaku $bahanbaku)
    {
        $bahanbaku->delete();
        return redirect()->route('bahanbaku.index')
            ->with('success', 'Bahan baku berhasil dihapus');
    }
}
