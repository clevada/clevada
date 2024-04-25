<?php

namespace App\Http\Auth;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {

        $role = Auth::user()->role ?? null;

        if ($role == 'admin') {
            return redirect(route('admin'));
        }

        if ($role == 'user') {
            return redirect(route('user'));
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }
}
