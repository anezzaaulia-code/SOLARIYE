<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('pembeli.detail_menu', compact('menu'));
    }
}
