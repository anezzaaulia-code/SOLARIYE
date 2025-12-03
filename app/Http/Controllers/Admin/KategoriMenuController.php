<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriMenuController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Tambahkan 'admin.' di depan nama view
        $kategori = KategoriMenu::orderBy('nama')->paginate(20);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        // PERBAIKAN: Lokasi view create
        return view('admin.kategori.create');
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

        // PERBAIKAN: Nama route disesuaikan dengan routes/web.php (kategori-menu)
        return redirect()->route('kategori-menu.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(KategoriMenu $kategori_menu)
    {
        // Note: Karena Route Model Binding di web.php pakai resource 'kategori-menu', 
        // parameter otomatis jadi $kategori_menu. 
        // Pastikan variabel di view disesuaikan.
        
        return view('admin.kategori.edit', ['kategori' => $kategori_menu]);
    }

    public function update(Request $request, KategoriMenu $kategori_menu)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $data['slug'] = Str::slug($data['nama']);
        
        $kategori_menu->update($data);

        return redirect()->route('kategori-menu.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(KategoriMenu $kategori_menu)
    {
        $kategori_menu->delete();
        return redirect()->route('kategori-menu.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}