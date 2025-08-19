<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::channel('fe_api')->info("API Request: {$request->method()}, {$request->fullUrl()}", [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        $response = $next($request);

        Log::channel('fe_api')->info("API Response: {$response->getStatusCode()}, {$request->fullUrl()}", [
            'user' => Auth::user()?->id,
            'headers' => $response->headers->all(),
            'body' => json_decode($response->getContent(), true),
        ]);

        return $response;
    }
}
