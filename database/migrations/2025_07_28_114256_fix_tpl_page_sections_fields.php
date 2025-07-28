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
        Schema::table('tpl_page_sections', function (Blueprint $table) {
            // Add missing fields that the PageController expects
            $table->json('content_data')->nullable()->after('content')->comment('Section content with multilingual support');
            $table->json('settings')->nullable()->after('content_data')->comment('Section display settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpl_page_sections', function (Blueprint $table) {
            $table->dropColumn(['content_data', 'settings']);
        });
    }
};
