<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeMap = ["StoreBook" => 2];
        $requestedRoute = $request->route()->getName();

        if(!array_key_exists($requestedRoute, $routeMap)){
            return $next($request);
        }

        $userAccessLevel = -1;
        $userLoggedIn = auth()->user();
        if($userLoggedIn){
            $userAccessLevel = $userLoggedIn->role->access_level->access_level;
        }

        if($routeMap[$requestedRoute] > $userAccessLevel){
            return response()->json(['error' => 'Action not allowed'], 403);
        }

        return $next($request);
    }
}
