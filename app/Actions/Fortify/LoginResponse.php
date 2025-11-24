<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class KasirMiddleware
{
    public function handle($request, Closure $next)
    {

        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->intended('/users');
        }

        if ($user->role === 'kasir') {
            return redirect()->intended('/dashboard/sales');
        }

        return redirect()->intended('/dashboard');

    }
}
