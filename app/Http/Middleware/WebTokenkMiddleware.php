<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class WebTokenkMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $clientId = $request->header('Client-Id');
        $clientSecret = $request->header('Client-Secret');
        $headers = $request->headers->all();

        $client = DB::table('oauth_clients')
                    ->where('id', $clientId)
                    ->where('secret', $clientSecret)
                    ->where('revoked', false)
                    ->where('name','admin_helpdesk')
                    ->first();


        if ($client) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
