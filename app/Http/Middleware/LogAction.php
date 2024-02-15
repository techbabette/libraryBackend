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

        $routesAndActions = File::json(storage_path('/json/routeMap.json'));
        $requestedRoute = $request->route()->getName();

        if(!array_key_exists($requestedRoute, $routesAndActions)){
            return $response;
        }

        if(!array_key_exists('text', $routesAndActions[$requestedRoute])){
            return $response;
        }

        $action = $routesAndActions[$requestedRoute]['text'];
        $actionIssuer = "Anonymous";

        if(auth()->user()){
            $actionIssuer = auth()->user()->email;
        }

        Log::create(["issuer" => $actionIssuer, "action" => $action]);

        return $response;
    }
}
