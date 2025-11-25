<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    public function index()
    {
        $menus = Menu::with('kategori')->orderBy('nama')->paginate(20);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategories = KategoriMenu::where('status','aktif')->orderBy('nama')->get();
        return view('admin.menu.create', compact('kategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori_menu,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:4096',
            'status' => 'nullable|in:tersedia,habis,nonaktif',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $data['status'] = $data['status'] ?? 'tersedia';

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success','Menu dibuat.');
    }

    public function edit(Menu $menu)
    {
        $kategories = KategoriMenu::where('status','aktif')->orderBy('nama')->get();
        return view('admin.menu.edit', compact('menu','kategories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori_menu,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:4096',
            'status' => 'nullable|in:tersedia,habis,nonaktif',
        ]);

        if ($request->hasFile('foto')) {
            if ($menu->foto) Storage::disk('public')->delete($menu->foto);
            $data['foto'] = $request->file('foto')->store('menu','public');
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success','Menu diupdate.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->foto) Storage::disk('public')->delete($menu->foto);
        $menu->delete();
        return back()->with('success','Menu dihapus.');
    }
}
