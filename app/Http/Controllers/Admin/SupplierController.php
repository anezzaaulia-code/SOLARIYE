<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->paginate(20);
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        Supplier::create($data);
        return redirect()->route('admin.supplier.index')->with('success','Supplier dibuat.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            // ... validasi lainnya sama ...
        ]);

        $supplier->update($request->all());
        return redirect()->route('admin.supplier.index')->with('success','Supplier diupdate.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success','Supplier dihapus.');
    }
}