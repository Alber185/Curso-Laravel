<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
            'timestamp' => now()->toDateTimeString(),
        ];

        /*
        dd($data); // Aquí puedes reemplazar Log::info() por dd() para mostrar la información en la pantalla
        */
        Log::info('Request data', $data); // Con Log::info() se guarda la información en el log de Laravel

        //La diferencia entre dd() y Log::info() es que dd() detiene la ejecución del programa y muestra la información en la pantalla,
        //mientras que Log::info() guarda la información en el archivo de log de Laravel sin detener la ejecución.
        
        return $next($request);
    }
}
