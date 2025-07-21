<?php

namespace App\Services;

use App\Models\Site;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TenantConfigService
{
    protected ConfigurationService $configService;
    
    /**
     * Cache TTL for tenant configurations in minutes
     */
    const TENANT_CACHE_TTL = 60;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Get tenant configuration with caching and fallback
     */
    public function getTenantConfiguration(Site $tenant, string $key, $default = null)
    {
        $cacheKey = $this->getTenantCacheKey($tenant->id, $key);
        
        return Cache::remember($cacheKey, self::TENANT_CACHE_TTL, function () use ($tenant, $key, $default) {
            try {
                return $tenant->getConfiguration($key, $default);
            } catch (\Exception $e) {
                Log::error('Tenant configuration retrieval failed', [
                    'tenant_id' => $tenant->id,
                    'key' => $key,
                    'error' => $e->getMessage()
                ]);
                
                return $default;
            }
        });
    }

    /**
     * Set tenant configuration with cache invalidation
     */
    public function setTenantConfiguration(Site $tenant, string $key, $value): bool
    {
        try {
            $result = $tenant->setConfiguration($key, $value);
            
            if ($result) {
                // Clear cache for this specific configuration
                $this->clearTenantConfigCache($tenant->id, $key);
                
                // Log configuration change
                Log::info('Tenant configuration updated', [
                    'tenant_id' => $tenant->id,
                    'key' => $key,
                    'value_type' => gettype($value)
                ]);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Tenant configuration update failed', [
                'tenant_id' => $tenant->id,
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get multiple tenant configurations at once
     */
    public function getTenantConfigurations(Site $tenant, array $keys): array
    {
        $configurations = [];
        
        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                // If key is numeric, the value is the key name with no default
                $configKey = $default;
                $defaultValue = null;
            } else {
                // If key is string, use it as key and value as default
                $configKey = $key;
                $defaultValue = $default;
            }
            
            $configurations[$configKey] = $this->getTenantConfiguration($tenant, $configKey, $defaultValue);
        }
        
        return $configurations;
    }

    /**
     * Set multiple tenant configurations
     */
    public function setTenantConfigurations(Site $tenant, array $configurations): bool
    {
        $results = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($configurations as $key => $value) {
                $results[] = $this->setTenantConfiguration($tenant, $key, $value);
            }
            
            // Check if all updates were successful
            $allSuccessful = !in_array(false, $results, true);
            
            if ($allSuccessful) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Bulk tenant configuration update failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Initialize default configurations for a new tenant
     */
    public function initializeTenantDefaults(Site $tenant): bool
    {
        $defaultConfigurations = $this->getDefaultTenantConfigurations();
        
        return $this->setTenantConfigurations($tenant, $defaultConfigurations);
    }

    /**
     * Get default configuration template for new tenants
     */
    protected function getDefaultTenantConfigurations(): array
    {
        return [
            'theme' => [
                'name' => 'default',
                'version' => '1.0',
                'custom_css' => '',
                'custom_js' => ''
            ],
            'language' => [
                'default' => 'en',
                'available' => ['en'],
                'rtl_languages' => ['ar'],
                'fallback_language' => 'en'
            ],
            'colors' => [
                'primary' => '#007bff',
                'secondary' => '#6c757d',
                'success' => '#28a745',
                'danger' => '#dc3545',
                'warning' => '#ffc107',
                'info' => '#17a2b8',
                'light' => '#f8f9fa',
                'dark' => '#343a40'
            ],
            'navigation' => [
                'menu_items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                        'target' => '_self',
                        'is_active' => true
                    ]
                ],
                'show_breadcrumbs' => true,
                'mobile_menu_enabled' => true
            ],
            'seo' => [
                'site_title' => 'New Site',
                'meta_description' => 'Welcome to our website',
                'meta_keywords' => '',
                'og_image' => '',
                'twitter_card' => 'summary',
                'robots' => 'index,follow'
            ],
            'media' => [
                'max_upload_size' => 10, // MB
                'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
                'image_quality' => 85,
                'generate_thumbnails' => true,
                'thumbnail_sizes' => [
                    'small' => [150, 150],
                    'medium' => [300, 300],
                    'large' => [800, 600]
                ]
            ],
            'performance' => [
                'enable_caching' => true,
                'cache_duration' => 3600,
                'enable_compression' => true,
                'lazy_loading' => true,
                'critical_css' => false
            ],
            'security' => [
                'enable_csrf' => true,
                'enable_rate_limiting' => true,
                'max_requests_per_minute' => 60,
                'enable_https_redirect' => false
            ],
            'analytics' => [
                'google_analytics_id' => '',
                'facebook_pixel_id' => '',
                'enable_tracking' => false
            ],
            'contact' => [
                'email' => '',
                'phone' => '',
                'address' => '',
                'social_links' => [
                    'facebook' => '',
                    'twitter' => '',
                    'instagram' => '',
                    'linkedin' => ''
                ]
            ]
        ];
    }

    /**
     * Copy configuration from one tenant to another
     */
    public function copyTenantConfiguration(Site $sourceTenant, Site $targetTenant, array $configKeys = []): bool
    {
        try {
            if (empty($configKeys)) {
                // Copy all configurations - get from site config data directly
                $sourceConfig = $sourceTenant->config;
                if ($sourceConfig && !empty($sourceConfig->data)) {
                    $allConfig = $sourceConfig->data;
                    return $this->setTenantConfigurations($targetTenant, $allConfig);
                }
                return true;
            } else {
                // Copy specific configurations
                $configurations = [];
                foreach ($configKeys as $key) {
                    $configurations[$key] = $sourceTenant->getConfiguration($key);
                }
                return $this->setTenantConfigurations($targetTenant, $configurations);
            }
            
        } catch (\Exception $e) {
            Log::error('Tenant configuration copy failed', [
                'source_tenant_id' => $sourceTenant->id,
                'target_tenant_id' => $targetTenant->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Export tenant configuration
     */
    public function exportTenantConfiguration(Site $tenant, array $configKeys = []): array
    {
        try {
            $export = [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'export_date' => now()->toISOString(),
                'configurations' => []
            ];
            
            if (empty($configKeys)) {
                // Get all configurations from site config data
                $siteConfig = $tenant->config;
                $export['configurations'] = $siteConfig ? ($siteConfig->data ?? []) : [];
            } else {
                foreach ($configKeys as $key) {
                    $export['configurations'][$key] = $tenant->getConfiguration($key);
                }
            }
            
            return $export;
            
        } catch (\Exception $e) {
            Log::error('Tenant configuration export failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Import tenant configuration
     */
    public function importTenantConfiguration(Site $tenant, array $configData, bool $overwrite = false): bool
    {
        try {
            $configurations = $configData['configurations'] ?? $configData;
            
            if (!$overwrite) {
                // Merge with existing configuration - get from site config data
                $siteConfig = $tenant->config;
                $existingConfig = $siteConfig ? ($siteConfig->data ?? []) : [];
                $configurations = array_merge_recursive($existingConfig, $configurations);
            }
            
            return $this->setTenantConfigurations($tenant, $configurations);
            
        } catch (\Exception $e) {
            Log::error('Tenant configuration import failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Validate tenant configuration
     */
    public function validateTenantConfiguration(Site $tenant, string $key, $value): array
    {
        $errors = [];
        
        try {
            switch ($key) {
                case 'language':
                    $errors = $this->validateLanguageConfig($value);
                    break;
                    
                case 'colors':
                    $errors = $this->validateColorsConfig($value);
                    break;
                    
                case 'navigation':
                    $errors = $this->validateNavigationConfig($value);
                    break;
                    
                case 'seo':
                    $errors = $this->validateSeoConfig($value);
                    break;
                    
                case 'media':
                    $errors = $this->validateMediaConfig($value);
                    break;
                    
                default:
                    // Generic validation
                    if (!is_array($value) && !is_string($value) && !is_numeric($value) && !is_bool($value)) {
                        $errors[] = "Invalid data type for configuration key: {$key}";
                    }
            }
            
        } catch (\Exception $e) {
            $errors[] = "Validation error: " . $e->getMessage();
        }
        
        return $errors;
    }

    /**
     * Validate language configuration
     */
    protected function validateLanguageConfig($config): array
    {
        $errors = [];
        
        if (!is_array($config)) {
            $errors[] = 'Language configuration must be an array';
            return $errors;
        }
        
        if (empty($config['default'])) {
            $errors[] = 'Default language is required';
        }
        
        if (empty($config['available']) || !is_array($config['available'])) {
            $errors[] = 'Available languages must be a non-empty array';
        } elseif (!in_array($config['default'], $config['available'])) {
            $errors[] = 'Default language must be in available languages list';
        }
        
        return $errors;
    }

    /**
     * Validate colors configuration
     */
    protected function validateColorsConfig($config): array
    {
        $errors = [];
        
        if (!is_array($config)) {
            $errors[] = 'Colors configuration must be an array';
            return $errors;
        }
        
        $requiredColors = ['primary', 'secondary'];
        foreach ($requiredColors as $color) {
            if (empty($config[$color])) {
                $errors[] = "Color '{$color}' is required";
            } elseif (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $config[$color])) {
                $errors[] = "Color '{$color}' must be a valid hex color";
            }
        }
        
        return $errors;
    }

    /**
     * Validate navigation configuration
     */
    protected function validateNavigationConfig($config): array
    {
        $errors = [];
        
        if (!is_array($config)) {
            $errors[] = 'Navigation configuration must be an array';
            return $errors;
        }
        
        if (isset($config['menu_items']) && !is_array($config['menu_items'])) {
            $errors[] = 'Menu items must be an array';
        }
        
        return $errors;
    }

    /**
     * Validate SEO configuration
     */
    protected function validateSeoConfig($config): array
    {
        $errors = [];
        
        if (!is_array($config)) {
            $errors[] = 'SEO configuration must be an array';
            return $errors;
        }
        
        if (isset($config['meta_description']) && strlen($config['meta_description']) > 160) {
            $errors[] = 'Meta description should not exceed 160 characters';
        }
        
        return $errors;
    }

    /**
     * Validate media configuration
     */
    protected function validateMediaConfig($config): array
    {
        $errors = [];
        
        if (!is_array($config)) {
            $errors[] = 'Media configuration must be an array';
            return $errors;
        }
        
        if (isset($config['max_upload_size']) && (!is_numeric($config['max_upload_size']) || $config['max_upload_size'] <= 0)) {
            $errors[] = 'Max upload size must be a positive number';
        }
        
        if (isset($config['image_quality']) && (!is_numeric($config['image_quality']) || $config['image_quality'] < 1 || $config['image_quality'] > 100)) {
            $errors[] = 'Image quality must be between 1 and 100';
        }
        
        return $errors;
    }

    /**
     * Get tenant cache key
     */
    protected function getTenantCacheKey(int $tenantId, string $key): string
    {
        return "tenant_{$tenantId}_config_{$key}";
    }

    /**
     * Clear tenant configuration cache
     */
    public function clearTenantConfigCache(int $tenantId, ?string $key = null): void
    {
        if ($key) {
            Cache::forget($this->getTenantCacheKey($tenantId, $key));
        } else {
            // Clear all tenant configuration cache
            $pattern = "tenant_{$tenantId}_config_*";
            Cache::tags(["tenant_{$tenantId}"])->flush();
        }
    }

    /**
     * Get tenant statistics
     */
    public function getTenantStatistics(Site $tenant): array
    {
        try {
            $siteConfig = $tenant->config;
            $configData = $siteConfig ? ($siteConfig->data ?? []) : [];
            
            return [
                'total_configurations' => count($configData),
                'last_updated' => $tenant->updated_at,
                'cache_hits' => Cache::get("tenant_{$tenant->id}_cache_hits", 0),
                'active_domains' => collect([$tenant->domain, $tenant->subdomain])->filter()->count(),
                'theme' => $tenant->getConfiguration('theme.name', 'default'),
                'languages' => count($tenant->getConfiguration('language.available', ['en']))
            ];
            
        } catch (\Exception $e) {
            Log::error('Tenant statistics generation failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Health check for tenant configuration
     */
    public function healthCheck(Site $tenant): array
    {
        $issues = [];
        
        try {
            // Check required configurations
            $requiredConfigs = ['theme', 'language', 'colors'];
            foreach ($requiredConfigs as $config) {
                if (!$tenant->getConfiguration($config)) {
                    $issues[] = "Missing required configuration: {$config}";
                }
            }
            
            // Validate configuration integrity
            $siteConfig = $tenant->config;
            $allConfig = $siteConfig ? ($siteConfig->data ?? []) : [];
            foreach ($allConfig as $key => $value) {
                $validationErrors = $this->validateTenantConfiguration($tenant, $key, $value);
                if (!empty($validationErrors)) {
                    $issues = array_merge($issues, $validationErrors);
                }
            }
            
        } catch (\Exception $e) {
            $issues[] = "Health check failed: " . $e->getMessage();
        }
        
        return [
            'status' => empty($issues) ? 'healthy' : 'issues',
            'issues' => $issues,
            'checked_at' => now()->toISOString()
        ];
    }
}
