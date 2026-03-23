<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('seller.login');
        }

        if (! auth()->user()->hasRole('seller')) {
            abort(403);
        }

        return $next($request);
    }
}
