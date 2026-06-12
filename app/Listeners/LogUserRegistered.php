<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserRegistered implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->release(5); // Reintentar después de 10 segundos
        Log::info("Nuevo usuario registrado", ['id' => $event->user->id]);
    }

    public function failed(UserRegistered $event, $exception): void
    {
        Log::critical("El registro del usuario {$event->user->id} falló");
    }
}
