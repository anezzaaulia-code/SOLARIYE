<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.supplier.index', [
            'supplier' => Supplier::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['nama_supplier' => 'required']);

        Supplier::create($request->all());

        return back()->with('success', 'Supplier berhasil ditambah');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($request->all());
        return back()->with('success', 'Supplier diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Supplier dihapus');
    }
}
