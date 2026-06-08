<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckValueInHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->header("token") !== '123456') {
            Log::warning('Acceso denegado: token incorrecto', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Acceso denegado'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
