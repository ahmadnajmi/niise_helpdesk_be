<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = User::getUserRole(Auth::user()->id);

        if($role?->role == Role::SUPER_ADMIN){
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'You are not authorized to access this api.',
            'status_code' => 403
        ], 
        Response::HTTP_FORBIDDEN);
        
    }
}
