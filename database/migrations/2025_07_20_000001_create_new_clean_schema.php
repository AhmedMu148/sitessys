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
        // Create user_status table first
        Schema::create('user_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['super-admin', 'admin', 'user'])->default('user');
            $table->boolean('status_id')->default(true);
            $table->string('preferred_language', 5)->default('en');
            $table->timestamps();
            
            $table->index(['role'], 'idx_role_tenant');
            $table->index('preferred_language', 'idx_preferred_language');
        });

        // Create site_status table
        Schema::create('site_status', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });

        // Create tpl_layouts table (needed for foreign keys)
        Schema::create('tpl_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('tpl_id', 50)->unique();
            $table->enum('layout_type', ['header', 'section', 'footer']);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('path');
            $table->json('default_config')->nullable();
            $table->json('content')->nullable();
            $table->json('configurable_fields')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Create theme_categories table
        Schema::create('theme_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['status', 'sort_order'], 'idx_active_sort');
        });

        // Create theme_pages table
        Schema::create('theme_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('theme_categories')->onDelete('cascade');
            $table->string('theme_id', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('path');
            $table->json('css_variables')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category_id', 'status'], 'idx_category_active');
            $table->index('theme_id', 'idx_theme_key');
        });

        // Create sites table
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('site_name');
            $table->string('url')->nullable();
            $table->boolean('status_id')->default(true);
            $table->foreignId('active_header_id')->nullable()->constrained('tpl_layouts')->onDelete('set null');
            $table->foreignId('active_footer_id')->nullable()->constrained('tpl_layouts')->onDelete('set null');
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Create site_config table
        Schema::create('site_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->json('settings')->nullable()->comment('{"timezone": "UTC"}');
            $table->json('data')->nullable()->comment('Stores meta, logo, tenant_site_id if needed');
            $table->json('language_code')->nullable()->comment('{"languages": ["en", "ar"], "primary": "en"}');
            $table->string('tpl_name', 50)->default('business');
            $table->json('tpl_colors')->nullable();
            $table->timestamps();
        });

        // Create tpl_pages table
        Schema::create('tpl_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('name');
            $table->string('link');
            $table->string('slug');
            $table->json('data')->nullable()->comment('Multilingual title/meta');
            $table->boolean('show_in_nav')->default(false);
            $table->boolean('status')->default(true);
            $table->foreignId('page_theme_id')->nullable()->constrained('theme_pages')->onDelete('set null');
            $table->timestamps();
            
            $table->index('page_theme_id', 'idx_theme');
            $table->index(['site_id', 'status'], 'idx_pages_site_active');
        });

        // Create tpl_page_sections table
        Schema::create('tpl_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('tpl_pages')->onDelete('cascade');
            $table->foreignId('tpl_layouts_id')->nullable()->constrained('tpl_layouts')->onDelete('set null');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('name');
            $table->json('content')->nullable()->comment('Multilingual content');
            $table->text('custom_styles')->nullable();
            $table->text('custom_scripts')->nullable();
            $table->boolean('status')->default(true)->comment('Used as is_active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('tpl_layouts_id', 'idx_template');
            $table->index(['page_id', 'status', 'sort_order'], 'idx_sections_page_active_order');
        });

        // Create site_img_media table
        Schema::create('site_img_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('tpl_page_sections')->onDelete('set null');
            $table->integer('max_files')->default(10);
            $table->json('allowed_types')->nullable()->comment('["image/*"]');
            $table->timestamps();
        });

        // Create tpl_site table
        Schema::create('tpl_site', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->json('nav_data')->nullable()->comment('{"links": [{"url": "/home", "label": "Home"}]}');
            $table->json('footer_data')->nullable()->comment('{"links": [{"url": "/about", "label": "About"}]}');
            $table->timestamps();
        });

        // Create tpl_langs table
        Schema::create('tpl_langs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name', 50);
            $table->enum('dir', ['ltr', 'rtl'])->default('ltr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_img_media');
        Schema::dropIfExists('tpl_page_sections');
        Schema::dropIfExists('tpl_site');
        Schema::dropIfExists('tpl_pages');
        Schema::dropIfExists('site_config');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('theme_pages');
        Schema::dropIfExists('theme_categories');
        Schema::dropIfExists('tpl_layouts');
        Schema::dropIfExists('tpl_langs');
        Schema::dropIfExists('site_status');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_status');
    }
};
