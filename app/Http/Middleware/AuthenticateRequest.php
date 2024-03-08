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

        if(!isset($routeMap[$requestedRoute])){
            return $next($request);
        }

        $userAccessLevel = -1;
        $userLoggedIn = auth()->user();
        if($userLoggedIn){
            $userAccessLevel = $userLoggedIn->access_level->access_level;
        }

        $requestedRouteObject = $routeMap[$requestedRoute];
        $routeAccessLevelRequired = -1;

        //If no level requirements defined, allow access
        if(!isset($requestedRouteObject['access_level']) && !isset($requestedRouteObject['subroute_options'])){
            return $next($request);
        }

        //Set required access level if the route object defines it
        if(isset($requestedRouteObject['access_level'])){
            $routeAccessLevelRequired = $requestedRouteObject['access_level'];
        }

        //Override required access level if option in list used
        if(isset($requestedRouteObject['subroute_options'])){
            foreach($requestedRouteObject['subroute_options'] as $key => $option){
                if($request->get($key) && isset($option['access_level'])){
                    $routeAccessLevelRequired = $option['access_level'];
                    break;
                }
            }
        }

        if($routeAccessLevelRequired > $userAccessLevel){
            if($userAccessLevel < 1){
                return response()->json(['error' => "You must be logged in to do this"], 401);
            }
            return response()->json(['error' => "You're not allowed this action"], 403);
        }

        return $next($request);
    }
}
