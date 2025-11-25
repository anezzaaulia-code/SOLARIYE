<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->intended('/dashboard');
        }

        if ($user->role === 'kasir') {
            return redirect()->intended('/pos');
        }

        return redirect()->intended('/dashboard');
    }
}
