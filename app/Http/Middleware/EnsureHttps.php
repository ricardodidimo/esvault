<?php

namespace App\Http\Middleware;

use Closure;

class EnsureHttps
{

    public function handle($request, Closure $next)
    {
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
