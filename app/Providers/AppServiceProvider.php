<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @OA\Info(
 *     title="Translation Management Service API",
 *     version="1.0.0",
 *     description="API documentation for the Translation Management Service",
 *     @OA\Contact(
 *         email="your-email@example.com",
 *         name="Ahmad Fayyaz"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearer_token",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

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
        //
    }
}
