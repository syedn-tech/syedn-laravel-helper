<?php

namespace Syedn\Helper\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkipPreventBackHistory
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('_skip_prevent_back_history', true);

        return $next($request);
    }
}
