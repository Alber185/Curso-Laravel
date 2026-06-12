<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Business\Interfaces\MessageServiceInterface;
use App\Business\Services\MessageService;
use App\Business\Services\EncryptService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageServiceInterface::class, MessageService::class);
        $this->app->bind(EncryptService::class, function () {
            return new EncryptService(env('KEY_ENCRYPT'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
