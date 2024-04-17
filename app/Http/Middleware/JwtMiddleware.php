<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try
        {
            JWTAuth::parseToken()->authenticate();
        }
        catch (\Exception $e)
        {
            if($e instanceof TokenInvalidException)
            {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Token is Invalid'
                ], 401);
            }

            if($e instanceof TokenExpiredException)
            {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Token is Expired'
                ], 401);
            }

            return response()->json([
                'status' => 'error',
                'data' => 'Token not found'
            ], 401);
        }

        return $next($request);
    }
}
