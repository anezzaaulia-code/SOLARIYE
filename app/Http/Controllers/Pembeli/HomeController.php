<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::where('status', 'ready')->get();
        return view('pembeli.home', compact('menus'));
    }
}
