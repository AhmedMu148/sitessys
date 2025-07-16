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
        Schema::table('sites', function (Blueprint $table) {
            // Add fields for active header and footer layouts
            $table->foreignId('active_header_id')->nullable()->constrained('tpl_layouts')->onDelete('set null');
            $table->foreignId('active_footer_id')->nullable()->constrained('tpl_layouts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropForeign(['active_header_id']);
            $table->dropForeign(['active_footer_id']);
            $table->dropColumn(['active_header_id', 'active_footer_id']);
        });
    }
};
