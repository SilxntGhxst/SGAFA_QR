<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inyecta automáticamente el API Key a todas las solicitudes HTTP salientes hacia FastAPI
        Http::globalRequestMiddleware(function ($request) {
            $apiKey = env('FASTAPI_KEY', 'sgafa_qr_api_key_2024_secure');
            return $request->withHeader('X-API-Key', $apiKey);
        });
    }
}
