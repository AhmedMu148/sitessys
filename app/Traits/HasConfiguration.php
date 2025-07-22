<?php

namespace App\Traits;

use App\Services\ConfigurationService;

trait HasConfiguration
{
    /**
     * Get configuration service instance
     */
    protected function configService(): ConfigurationService
    {
        return app(ConfigurationService::class);
    }

    /**
     * Get configuration by type
     */
    public function getConfiguration(string $type, $default = null)
    {
        return $this->configService()->get($this->id, $type, $default);
    }

    /**
     * Set configuration by type
     */
    public function setConfiguration(string $type, array $data, bool $merge = false): bool
    {
        return $this->configService()->set($this->id, $type, $data, $merge);
    }

    /**
     * Get all configurations
     */
    public function getAllConfigurations(): array
    {
        return $this->configService()->getAll($this->id);
    }

    /**
     * Clear configuration cache
     */
    public function clearConfigurationCache(?string $type = null): void
    {
        $this->configService()->clearCache($this->id, $type);
    }

    /**
     * Initialize default configurations
     */
    public function initializeDefaultConfigurations(): bool
    {
        return $this->configService()->initializeDefaults($this->id);
    }

    /**
     * Export configurations for backup
     */
    public function exportConfigurations(): array
    {
        return $this->configService()->export($this->id);
    }

    /**
     * Import configurations from backup
     */
    public function importConfigurations(array $backup): bool
    {
        return $this->configService()->import($this->id, $backup);
    }

    /**
     * Get configuration versions
     */
    public function getConfigurationVersions(string $type): array
    {
        return $this->configService()->getVersions($this->id, $type);
    }

    /**
     * Rollback configuration to specific version
     */
    public function rollbackConfiguration(string $type, int $version): bool
    {
        return $this->configService()->rollback($this->id, $type, $version);
    }

    /**
     * Validate configuration data
     */
    public function validateConfiguration(string $type, array $data): bool
    {
        return $this->configService()->validate($type, $data);
    }

    /**
     * Get default configuration for type
     */
    public function getDefaultConfiguration(string $type): array
    {
        return $this->configService()->getDefaults($type);
    }
}
