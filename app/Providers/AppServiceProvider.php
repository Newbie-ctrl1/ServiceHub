<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Hanya mendaftarkan Laravel Pail Service Provider jika kelas tersedia
        // Ini mencegah error saat deploy di production dengan --no-dev
        if (class_exists('Laravel\\Pail\\PailServiceProvider')) {
            $this->app->register('Laravel\\Pail\\PailServiceProvider');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
