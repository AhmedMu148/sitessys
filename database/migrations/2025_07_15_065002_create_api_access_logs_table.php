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
        Schema::create('api_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('token_name')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('endpoint');
            $table->string('method', 10);
            $table->json('request_data')->nullable();
            $table->integer('response_status');
            $table->timestamp('accessed_at');
            
            $table->index(['user_id', 'accessed_at']);
            $table->index(['ip_address', 'accessed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_access_logs');
    }
};
