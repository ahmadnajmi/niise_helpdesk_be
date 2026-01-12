<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use DB;

class WebTokenkMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user());
        // if (!$request->user() || !$request->user()->is_admin) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // return $next($request);


        $clientId = $request->header('Client-Id');
        $clientSecret = $request->header('Client-Secret');
        $headers = $request->headers->all();

        $client = DB::table('oauth_clients')
                    ->where('id', $clientId)
                    ->where('secret', $clientSecret)
                    ->where('revoked', false)
                    ->where('name','admin_helpdesk')
                    ->first();

        $client = true;
        if ($client) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
