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
        $response = $next($request);

        if($request->method() == 'GET'){
            return $response;
        }

        $status = $response->getStatusCode();

        if ($status >= 400) {

            $content = $response->getContent();
            $decoded = json_decode($content, true);

            $errorMessage = $decoded['message'] ?? $content;

            Log::channel('api_log')->error("API Response: {$status}, {$request->fullUrl()}", [
                'user'    => Auth::user()?->id,
                'error'   => $errorMessage,
                'payload' => $request->all(),
                'body'    => $decoded ?? $content,
            ]);
        }
        else{
            Log::channel('api_log')->info("API Response: {$status}, {$request->fullUrl()}", [
                'payload' => $request->all(),
                'body' => json_decode($response->getContent(), true),
            ]);
        }

        

        return $response;
    }
}
