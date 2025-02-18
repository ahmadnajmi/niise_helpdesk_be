<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate extends Middleware

{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated. Please log in again.',
                'error_code' => 401
            ], Response::HTTP_UNAUTHORIZED);
        }

        abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
    }
}
