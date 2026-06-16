<?php

namespace App\ExternalService;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getData()
    {
        $response = Http::withoutVerifying()->get($this->url);

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => 'Error al obtener datos de la API'];
    }
}
