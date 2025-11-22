<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Menu;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('menu')->get();
        return view('pembeli.keranjang', compact('items'));
    }

    public function tambah(Request $request, $menu_id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);

        Keranjang::create([
            'menu_id' => $menu_id,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('keranjang.index');
    }

    public function hapus($id)
    {
        Keranjang::findOrFail($id)->delete();
        return back();
    }
}
