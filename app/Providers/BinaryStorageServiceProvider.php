<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;

class BinaryStorageServiceProvider extends ServiceProvider
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
        // Mengatur PDO attribute untuk menangani data binary
        $this->configurePdoForBinaryData();
    }
    
    /**
     * Mengatur PDO attribute untuk menangani data binary
     */
    protected function configurePdoForBinaryData(): void
    {
        if (DB::connection() instanceof Connection) {
            $pdo = DB::connection()->getPdo();
            
            // Mengatur PDO attribute untuk menangani data binary
            // PDO::ATTR_EMULATE_PREPARES = false memastikan prepared statements digunakan
            // PDO::ATTR_STRINGIFY_FETCHES = false mencegah konversi data binary ke string
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
        }
    }
}