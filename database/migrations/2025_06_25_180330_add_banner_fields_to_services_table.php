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
        Schema::table('services', function (Blueprint $table) {
            $table->binary('banner_image')->nullable()->after('main_image');
            $table->string('banner_title')->nullable()->after('banner_image');
            $table->text('banner_text')->nullable()->after('banner_title');
        });
        
        // Mengubah tipe data banner_image menjadi LONGBLOB
        DB::statement("ALTER TABLE services MODIFY banner_image LONGBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'banner_title', 'banner_text']);
        });
    }
};
