<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try 
        {
            JWTAuth::parseToken()->authenticate();
            //inject role and id into request
            $request->role = auth()->user()->role;
            $request->user_id = auth()->user()->id;
            return $next($request);
        } 
        catch (\Throwable $th) 
        {
            return response()->json(['errors' =>['authorization' => ['Access Not Allowd']]], 401);
        }
        
    }
}
