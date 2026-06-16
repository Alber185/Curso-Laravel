<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

// La ejecución sería así: php artisan app:hi "Juan" "Pérez" --segundoApellido="García"
// El nombre es obligatorio, el primer apellido no es obligatorio y el segundo apellido es una opción secundaria.
#[Signature('app:hi {name : Nombre de la persona}
                    {primerApellido? : Primer apellido de la persona}         
                    {--segundoApellido= : Segundo apellido de la persona}')]
                    
#[Description('Devuelve un mensaje de saludo')]
class Hi extends Command
{
    /**
     * Execute the console command.     
     */
    public function handle()
    {
        $name = $this->argument('name') ?? 'Mundo';
        $lastname = $this->argument('primerApellido') ?? '';
        $secondLastname = $this->option('segundoApellido') ?? '';
        $this->info("¡Hola, {$name} {$lastname} {$secondLastname}!");
    }
}
