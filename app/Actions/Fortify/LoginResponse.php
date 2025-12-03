<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = Auth::user()->role;

        // 1. Jika KASIR -> Arahkan langsung ke POS
        if ($role === 'kasir') {
            return redirect()->route('kasir.pos');
        }

        // 2. Jika ADMIN -> Arahkan ke Dashboard
        if ($role === 'admin') {
            return redirect()->intended('dashboard');
        }

        // Default (jaga-jaga)
        return redirect('/dashboard');
    }
}