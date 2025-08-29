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
        Log::channel('api_log')->info("API Request: {$request->method()}, {$request->fullUrl()}", [
            'body' => $request->all(),
        ]);

        $response = $next($request);

        $status = $response->getStatusCode();

        if ($status >= 400) {

            $content = $response->getContent();
            $decoded = json_decode($content, true);

            $errorMessage = $decoded['message'] ?? $content;

            Log::channel('api_log')->error("API Error: {$status}, {$request->fullUrl()}", [
                'user'    => Auth::user()?->id,
                'error'   => $errorMessage,
                'body'    => $decoded ?? $content,
            ]);
        }
        else{
            Log::channel('api_log')->info("API Response: {$status}, {$request->fullUrl()}", [
                'body' => json_decode($response->getContent(), true),
            ]);
        }

        

        return $response;
    }
}
