<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SecretaryMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'secretary') {
            return redirect('/');
        }

        return $next($request);
    }
}
