<?php

namespace Illuminate\Session\Middleware;

use Closure;

class StartRedisMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Store the session data...
    }
}
