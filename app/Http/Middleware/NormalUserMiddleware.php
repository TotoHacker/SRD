<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NormalUserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect('login'); 
        }

        return $next($request);
    }
}
