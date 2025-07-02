<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_description');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('min_time')->comment('Minimum time in hours');
            $table->integer('max_time')->comment('Maximum time in hours');
            $table->json('highlights')->nullable();
            $table->enum('badge', ['Tersedia', 'Terbatas', 'Promo'])->default('Tersedia');
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};