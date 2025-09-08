<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebTokenkMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $token_access = $request->header('token-access');

        if ($token_access === config('app.admin_token')) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
