<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards){
        if ($this->auth->guard('api')->guest()) {
            return response()->json([
            'success' => false,
            'message' => 'You are not authenticated. Please log in again.',
            'error_code' => 401
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    protected function redirectTo(Request $request): ?string{
        return null;
    }
}
