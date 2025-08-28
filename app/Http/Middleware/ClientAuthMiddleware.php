<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class ClientAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response{
        $clientId = $request->header('Client-Id');
        $clientSecret = $request->header('Client-Secret');

        if (!$clientId || !$clientSecret) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - missing credentials',
                'error_code' => 401
            ], Response::HTTP_UNAUTHORIZED);
        }

        $client = DB::table('oauth_clients')
                    ->where('id', $clientId)
                    ->where('secret', $clientSecret)
                    ->where('revoked', false)
                    ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated. Please log in again.',
                'error_code' => 401
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
