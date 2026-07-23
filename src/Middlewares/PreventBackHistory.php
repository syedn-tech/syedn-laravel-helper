<?php

namespace Syedn\Helper\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip attaching no-cache headers if no.back.skip middleware was applied
        if ($request->attributes->get('_skip_prevent_back_history')) {
            return $response;
        }

        // Instruct the browser to wipe cache parameters for this response
        return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}