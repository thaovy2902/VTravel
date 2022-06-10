<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // $user = JWTAuth::parseToken()->authenticate();
        // if ($user && in_array($user->role->slug, $roles)) {
            
            return $next($request);
        // }

        // return response()->json([
        //     'message' => 'You are unauthorized to access this resource'
        // ], Response::HTTP_BAD_REQUEST);
    }
}
