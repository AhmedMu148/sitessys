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
            $table->boolean('show_in_nav')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpl_pages', function (Blueprint $table) {
            $table->dropColumn('show_in_nav');
        });
    }
};
