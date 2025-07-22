<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ConfigurationService;
use App\Models\Site;

class ConfigurationCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'config:manage 
                            {action : Action to perform (get|set|initialize|export|import|validate|clear-cache)}
                            {--site= : Site ID to manage configurations for}
                            {--type= : Configuration type (theme|language|navigation|colors|sections|media|tenant)}
                            {--data= : JSON data for set action}
                            {--file= : File path for export/import actions}
                            {--merge : Merge with existing configuration when setting}';

    /**
     * The console command description.
     */
    protected $description = 'Manage JSON configurations for sites';

    protected ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        parent::__construct();
        $this->configService = $configService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $siteId = $this->option('site');
        $type = $this->option('type');

        // Validate site ID for most actions
        if (in_array($action, ['get', 'set', 'export', 'import', 'initialize', 'clear-cache'])) {
            if (!$siteId) {
                $this->error('Site ID is required for this action.');
                return 1;
            }

            $site = Site::find($siteId);
            if (!$site) {
                $this->error("Site with ID {$siteId} not found.");
                return 1;
            }
        }

        switch ($action) {
            case 'get':
                return $this->getConfiguration($siteId, $type);

            case 'set':
                return $this->setConfiguration($siteId, $type);

            case 'initialize':
                return $this->initializeConfigurations($siteId);

            case 'export':
                return $this->exportConfigurations($siteId);

            case 'import':
                return $this->importConfigurations($siteId);

            case 'validate':
                return $this->validateConfiguration();

            case 'clear-cache':
                return $this->clearCache($siteId, $type);

            default:
                $this->error('Invalid action. Available actions: get, set, initialize, export, import, validate, clear-cache');
                return 1;
        }
    }

    /**
     * Get configuration
     */
    protected function getConfiguration(int $siteId, ?string $type): int
    {
        if ($type) {
            $config = $this->configService->get($siteId, $type);
            $this->info("Configuration for site {$siteId}, type {$type}:");
            $this->line(json_encode($config, JSON_PRETTY_PRINT));
        } else {
            $configs = $this->configService->getAll($siteId);
            $this->info("All configurations for site {$siteId}:");
            $this->line(json_encode($configs, JSON_PRETTY_PRINT));
        }

        return 0;
    }

    /**
     * Set configuration
     */
    protected function setConfiguration(int $siteId, string $type): int
    {
        if (!$type) {
            $this->error('Configuration type is required for set action.');
            return 1;
        }

        $dataOption = $this->option('data');
        if (!$dataOption) {
            $this->error('Data is required for set action. Use --data="json_string"');
            return 1;
        }

        $data = json_decode($dataOption, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON data provided.');
            return 1;
        }

        $merge = $this->option('merge');
        $success = $this->configService->set($siteId, $type, $data, $merge);

        if ($success) {
            $this->info("Configuration set successfully for site {$siteId}, type {$type}.");
            return 0;
        } else {
            $this->error("Failed to set configuration for site {$siteId}, type {$type}.");
            return 1;
        }
    }

    /**
     * Initialize default configurations
     */
    protected function initializeConfigurations(int $siteId): int
    {
        $success = $this->configService->initializeDefaults($siteId);

        if ($success) {
            $this->info("Default configurations initialized successfully for site {$siteId}.");
            return 0;
        } else {
            $this->error("Failed to initialize default configurations for site {$siteId}.");
            return 1;
        }
    }

    /**
     * Export configurations
     */
    protected function exportConfigurations(int $siteId): int
    {
        $file = $this->option('file');
        if (!$file) {
            $file = storage_path("app/configurations/site_{$siteId}_" . date('Y-m-d_H-i-s') . '.json');
        }

        $backup = $this->configService->export($siteId);

        // Ensure directory exists
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $result = file_put_contents($file, json_encode($backup, JSON_PRETTY_PRINT));

        if ($result !== false) {
            $this->info("Configurations exported successfully to: {$file}");
            return 0;
        } else {
            $this->error("Failed to export configurations to: {$file}");
            return 1;
        }
    }

    /**
     * Import configurations
     */
    protected function importConfigurations(int $siteId): int
    {
        $file = $this->option('file');
        if (!$file) {
            $this->error('File path is required for import action. Use --file="path/to/backup.json"');
            return 1;
        }

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $content = file_get_contents($file);
        $backup = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON file provided.');
            return 1;
        }

        $success = $this->configService->import($siteId, $backup);

        if ($success) {
            $this->info("Configurations imported successfully for site {$siteId}.");
            return 0;
        } else {
            $this->error("Failed to import configurations for site {$siteId}.");
            return 1;
        }
    }

    /**
     * Validate configuration data
     */
    protected function validateConfiguration(): int
    {
        $type = $this->option('type');
        $dataOption = $this->option('data');

        if (!$type || !$dataOption) {
            $this->error('Both type and data are required for validate action.');
            return 1;
        }

        $data = json_decode($dataOption, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON data provided.');
            return 1;
        }

        $valid = $this->configService->validate($type, $data);

        if ($valid) {
            $this->info("Configuration data is valid for type {$type}.");
            return 0;
        } else {
            $this->error("Configuration data is invalid for type {$type}.");
            return 1;
        }
    }

    /**
     * Clear configuration cache
     */
    protected function clearCache(int $siteId, ?string $type): int
    {
        $this->configService->clearCache($siteId, $type);

        if ($type) {
            $this->info("Cache cleared for site {$siteId}, type {$type}.");
        } else {
            $this->info("All configuration cache cleared for site {$siteId}.");
        }

        return 0;
    }
}
