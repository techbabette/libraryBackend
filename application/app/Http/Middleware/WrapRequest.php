<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WrapRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        DB::beginTransaction();

        try {
            $response = $next($request);
        } catch (Exception|\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error communicating with database'], 500);
        }

        if ($response instanceof Response && $response->getStatusCode() > 499) {
            DB::rollBack();
            return response()->json(['message' => 'Error communicating with database'], 500);
        } else {
            DB::commit();
        }

        return $response;
    }
}
