<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\ExternalService\ApiService;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiService::class, function ($app) {
            $url = config('services.api.url');
            return new ApiService($url);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::get("/api/posts", function(ApiService $apiService) {
            $data = $apiService->getData();
            return response()->json($data);
        });
    }
}
