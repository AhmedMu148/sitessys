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
        Schema::create('tpl_lang', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // English, Arabic
            $table->string('code', 2)->unique(); // en, ar
            $table->enum('dir', ['ltr', 'rtl'])->default('ltr');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpl_lang');
    }
};
