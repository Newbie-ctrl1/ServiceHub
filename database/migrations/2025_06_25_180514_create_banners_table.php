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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->binary('image')->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
        
        // Mengubah tipe data image menjadi LONGBLOB
        DB::statement("ALTER TABLE banners MODIFY image LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
