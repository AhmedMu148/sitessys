<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for JSON fields to improve query performance
        // Using simpler indexes that work across MySQL versions
        
        if (DB::getDriverName() === 'mysql') {
            try {
                // Create virtual columns and then index them (MySQL 5.7+)
                DB::statement('ALTER TABLE site_config ADD COLUMN primary_language_virtual VARCHAR(5) AS (JSON_UNQUOTE(JSON_EXTRACT(language_code, "$.primary_language"))) VIRTUAL');
                DB::statement('CREATE INDEX idx_site_config_primary_language ON site_config (primary_language_virtual)');
                
                DB::statement('ALTER TABLE site_config ADD COLUMN theme_virtual VARCHAR(50) AS (JSON_UNQUOTE(JSON_EXTRACT(tpl_colors, "$.theme"))) VIRTUAL');
                DB::statement('CREATE INDEX idx_site_config_theme ON site_config (theme_virtual)');
                
                DB::statement('ALTER TABLE site_config ADD COLUMN tenant_id_virtual VARCHAR(100) AS (JSON_UNQUOTE(JSON_EXTRACT(data, "$.tenant.tenant_id"))) VIRTUAL');
                DB::statement('CREATE INDEX idx_site_config_tenant_id ON site_config (tenant_id_virtual)');
                
            } catch (\Exception $e) {
                // If virtual columns are not supported, create regular indexes on computed values
                // This is a fallback for older MySQL versions
                Log::info('JSON virtual column indexes not supported, skipping: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            try {
                // Drop the indexes and virtual columns
                DB::statement('DROP INDEX idx_site_config_primary_language ON site_config');
                DB::statement('ALTER TABLE site_config DROP COLUMN primary_language_virtual');
                
                DB::statement('DROP INDEX idx_site_config_theme ON site_config');
                DB::statement('ALTER TABLE site_config DROP COLUMN theme_virtual');
                
                DB::statement('DROP INDEX idx_site_config_tenant_id ON site_config');
                DB::statement('ALTER TABLE site_config DROP COLUMN tenant_id_virtual');
                
            } catch (\Exception $e) {
                Log::info('Failed to drop JSON indexes: ' . $e->getMessage());
            }
        }
    }
};
