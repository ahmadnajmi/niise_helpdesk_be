<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogViewerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('log_viewer_authorized') && session('log_viewer_expires') > now()) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized access to log viewer');
    }
}
