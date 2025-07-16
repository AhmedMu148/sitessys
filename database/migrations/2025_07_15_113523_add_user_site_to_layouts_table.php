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
        Schema::table('tpl_layouts', function (Blueprint $table) {
            // Add user and site ownership to layouts
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('cascade');
            $table->string('name')->nullable(); // Layout name for admin management
            $table->text('description')->nullable(); // Layout description
            $table->boolean('is_active')->default(true); // For activation/deactivation
            $table->integer('sort_order')->default(0); // For custom ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpl_layouts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['site_id']);
            $table->dropColumn(['user_id', 'site_id', 'name', 'description', 'is_active', 'sort_order']);
        });
    }
};
