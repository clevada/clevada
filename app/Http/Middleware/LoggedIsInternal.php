<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class LoggedIsInternal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user() ?? null) {
            if (!(Auth::user()->role == 'admin' || Auth::user()->role == 'internal'))
                return redirect('home');
        } else return redirect('home');

        return $next($request);
    }
}
