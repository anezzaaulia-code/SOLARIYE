<?php
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
    return redirect()->intended('/dashboard');
        }
        if ($user->role === 'kasir') {
            return redirect()->intended('/pos');
        }

        // default fallback
        return redirect()->intended('/dashboard');

    }
}
