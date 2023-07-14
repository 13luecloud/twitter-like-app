<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class APIResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($message = '', $data = []) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ]);
        });

        Response::macro('error', function ($message = '', $errors = [], $status) {
            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $status);
        });
    }
}
