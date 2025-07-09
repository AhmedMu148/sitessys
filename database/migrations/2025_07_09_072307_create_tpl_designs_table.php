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
        Schema::create('tpl_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignId('page_id')->constrained('tpl_pages')->onDelete('cascade');
            $table->foreignId('layout_id')->constrained('tpl_layouts')->onDelete('cascade');
            $table->foreignId('layout_type_id')->constrained('tpl_layout_types')->onDelete('cascade');
            $table->string('lang_code', 2)->default('en');
            $table->integer('sort_order')->default(0);
            $table->json('data')->nullable(); // JSON override data
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['page_id', 'lang_code', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpl_designs');
    }
};
