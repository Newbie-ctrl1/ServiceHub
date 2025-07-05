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
        // Hanya konfigurasi PDO jika aplikasi tidak sedang menjalankan perintah console tertentu
        // seperti package:discover yang tidak memerlukan koneksi database
        if (!$this->isRunningConsoleCommandThatDoesntNeedDatabase()) {
            // Mengatur PDO attribute untuk menangani data binary
            $this->configurePdoForBinaryData();
        }
    }
    
    /**
     * Memeriksa apakah aplikasi sedang menjalankan perintah console yang tidak memerlukan database
     */
    protected function isRunningConsoleCommandThatDoesntNeedDatabase(): bool
    {
        if (!app()->runningInConsole()) {
            return false;
        }
        
        // Daftar perintah yang tidak memerlukan koneksi database
        $commands = [
            'package:discover',
            'key:generate',
            'config:cache',
            'route:cache',
            'view:cache',
            'clear-compiled',
            'optimize',
            'vendor:publish',
        ];
        
        // Periksa argumen command line
        $input = new \Symfony\Component\Console\Input\ArgvInput();
        $command = $input->getFirstArgument();
        
        return $command && in_array($command, $commands);
    }
    
    /**
     * Mengatur PDO attribute untuk menangani data binary
     */
    protected function configurePdoForBinaryData(): void
    {
        try {
            if (DB::connection() instanceof Connection) {
                $pdo = DB::connection()->getPdo();
                
                // Mengatur PDO attribute untuk menangani data binary
                // PDO::ATTR_EMULATE_PREPARES = false memastikan prepared statements digunakan
                // PDO::ATTR_STRINGIFY_FETCHES = false mencegah konversi data binary ke string
                $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                $pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
            }
        } catch (\Exception $e) {
            // Log error atau tangani dengan cara lain
            // Tidak perlu throw exception agar tidak mengganggu proses package:discover
            if (app()->environment('local')) {
                // Hanya tampilkan pesan error di lingkungan local
                // echo 'Database connection error: ' . $e->getMessage();
            }
        }
    }
}