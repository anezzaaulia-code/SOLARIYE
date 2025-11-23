<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\PesananController as _PesananController;

class POSController extends Controller
{
    public function index()
    {
        $menus = Menu::where('status','tersedia')->orderBy('nama')->get();
        return view('pos.index', compact('menus'));
    }

    // store delegates to PesananController logic (but we implement similar here)
    public function store(Request $request)
    {
        // use the same validation as PesananController@store
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'metode_bayar' => 'required|in:tunai,qris,transfer',
        ]);

        // We'll reuse PesananController store logic manually to avoid coupling
        $pesananCtrl = app()->make(\App\Http\Controllers\PesananController::class);
        return $pesananCtrl->store($request);
    }
}
