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
        Schema::table('tpl_pages', function (Blueprint $table) {
            // Enhance pages for better management
            $table->string('slug')->nullable(); // URL slug for the page
            $table->text('description')->nullable(); // Page description
            $table->boolean('is_active')->default(true); // Page activation
            $table->boolean('show_in_nav')->default(true); // Show in navigation
            $table->integer('sort_order')->default(0); // Page ordering
            $table->json('meta_data')->nullable(); // SEO and other metadata
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpl_pages', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'is_active', 'show_in_nav', 'sort_order', 'meta_data']);
        });
    }
};
