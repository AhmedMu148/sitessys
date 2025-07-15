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
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('tpl_pages')->onDelete('cascade');
            $table->foreignId('layout_id')->constrained('tpl_layouts')->onDelete('cascade');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('name'); // Section name (e.g., "Hero", "About Us")
            $table->boolean('is_active')->default(true); // Section activation
            $table->integer('sort_order')->default(0); // Custom ordering within page
            $table->json('content_data')->nullable(); // Dynamic content for the section
            $table->json('settings')->nullable(); // Section-specific settings
            $table->timestamps();
            
            // Index for performance
            $table->index(['page_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
