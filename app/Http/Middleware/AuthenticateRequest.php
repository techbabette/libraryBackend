<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        $routeMap = File::json(storage_path('/json/routeMap.json'));
        $requestedRoute = $request->route()->getName();

        if(!array_key_exists($requestedRoute, $routeMap)){
            return $next($request);
        }

        if(!array_key_exists('access_level', $routeMap[$requestedRoute])){
            return $next($request);
        }

        $userAccessLevel = -1;
        $userLoggedIn = auth()->user();
        if($userLoggedIn){
            $userAccessLevel = $userLoggedIn->access_level->access_level;
        }

        if($routeMap[$requestedRoute]['access_level'] > $userAccessLevel){
            return response()->json(['error' => 'Action not allowed'], 403);
        }

        return $next($request);
    }
}
