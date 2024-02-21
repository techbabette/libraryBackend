<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class LogAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response =  $next($request);

        if(!$response->isSuccessful()){
            return $response;
        };

        $routesAndActions = File::json(storage_path('/json/routeMap.json'));
        $requestedRoute = $request->route()->getName();

        if(!isset($routesAndActions[$requestedRoute])){
            return $response;
        }

        $requestedRouteObject = $routesAndActions[$requestedRoute];
        $action = "";

        //Set action to base route text if it exists
        if(isset($requestedRouteObject['text'])){
            $action = $requestedRouteObject['text'];
        }

        //Override text action if option in list used
        if(isset($requestedRouteObject['subroute_options'])){
            $alternateOptions = $requestedRouteObject['subroute_options'];
            foreach($alternateOptions as $key => $option){
                if($request->get($key)){
                    if(!isset($option['text'])){
                        $action = "";
                    }
                    else{
                        $action = $option['text'];
                    }
                    break;
                }
            }
        }

        if(!$action){
            return $response;
        }


        $actionIssuer = "Anonymous";
        if(auth()->user()){
            $actionIssuer = auth()->user()->email;
        }

        Log::create(["issuer" => $actionIssuer, "action" => $action]);

        return $response;
    }
}
