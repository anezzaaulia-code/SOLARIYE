<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KategoriMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index()
    {
        $kategori = KategoriMenu::orderBy('nama')->paginate(20);
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $data['slug'] = Str::slug($data['nama']);
        $data['status'] = $data['status'] ?? 'aktif';

        KategoriMenu::create($data);

        return redirect()->route('kategori-menu.index')->with('success','Kategori dibuat.');
    }

    public function edit(KategoriMenu $kategoriMenu)
    {
        return view('kategori.edit', ['kategori' => $kategoriMenu]);
    }

    public function update(Request $request, KategoriMenu $kategoriMenu)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $data['slug'] = Str::slug($data['nama']);
        $kategoriMenu->update($data);

        return redirect()->route('kategori-menu.index')->with('success','Kategori diupdate.');
    }

    public function destroy(KategoriMenu $kategoriMenu)
    {
        $kategoriMenu->delete();
        return back()->with('success','Kategori dihapus.');
    }
}
