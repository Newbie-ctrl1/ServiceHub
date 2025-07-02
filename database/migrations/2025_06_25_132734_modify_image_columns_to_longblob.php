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
        // Mengubah kolom profile_photo di tabel users menjadi LONGBLOB
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('profile_photo');
        });
        
        Schema::table('users', function (Blueprint $table) {
            // Buat kolom baru dengan tipe LONGBLOB
            $table->binary('profile_photo')->nullable()->after('role');
        });
        
        // Mengubah kolom main_image dan additional_images di tabel services
        Schema::table('services', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['main_image', 'additional_images']);
        });
        
        Schema::table('services', function (Blueprint $table) {
            // Buat kolom baru dengan tipe LONGBLOB untuk main_image
            $table->binary('main_image')->nullable();
            // Untuk additional_images, kita akan menggunakan tabel terpisah
        });
        
        // Buat tabel baru untuk menyimpan additional_images
        Schema::create('service_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->binary('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel service_images
        Schema::dropIfExists('service_images');
        
        // Kembalikan kolom di tabel services
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('main_image');
        });
        
        Schema::table('services', function (Blueprint $table) {
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
        });
        
        // Kembalikan kolom di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('role');
        });
    }
};
