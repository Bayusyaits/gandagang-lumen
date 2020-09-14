<?php

namespace Modules\GlobalParamModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GlobalParamModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
