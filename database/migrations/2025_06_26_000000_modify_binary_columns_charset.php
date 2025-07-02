<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah charset kolom profile_photo di tabel users menjadi binary
        DB::statement("ALTER TABLE users MODIFY profile_photo LONGBLOB");
        
        // Mengubah charset kolom main_image di tabel services menjadi binary
        DB::statement("ALTER TABLE services MODIFY main_image LONGBLOB");
        
        // Mengubah charset kolom image di tabel service_images menjadi binary
        DB::statement("ALTER TABLE service_images MODIFY image LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan charset kolom profile_photo di tabel users menjadi utf8mb4
        DB::statement("ALTER TABLE users MODIFY profile_photo LONGBLOB");
        
        // Mengembalikan charset kolom main_image di tabel services menjadi utf8mb4
        DB::statement("ALTER TABLE services MODIFY main_image LONGBLOB");
        
        // Mengembalikan charset kolom image di tabel service_images menjadi utf8mb4
        DB::statement("ALTER TABLE service_images MODIFY image LONGBLOB");
    }
};