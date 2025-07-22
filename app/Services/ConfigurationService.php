<?php

namespace App\Services;

use App\Models\Site;
use App\Models\SiteConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ConfigurationService
{
    /**
     * Cache key prefix for configurations
     */
    const CACHE_PREFIX = 'config_';
    
    /**
     * Cache TTL in minutes
     */
    const CACHE_TTL = 60;

    /**
     * Configuration schema definitions for validation
     */
    protected array $schemas = [
        'theme' => [
            'theme' => 'required|string|max:50',
            'header_theme' => 'nullable|string|max:50',
            'footer_theme' => 'nullable|string|max:50',
            'page_themes' => 'nullable|array',
            'page_themes.*.page_id' => 'required|integer',
            'page_themes.*.theme' => 'required|string|max:50',
        ],
        'language' => [
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|string|size:2',
            'primary_language' => 'required|string|size:2',
            'rtl_languages' => 'nullable|array',
            'rtl_languages.*' => 'string|size:2',
        ],
        'navigation' => [
            'header' => 'required|array',
            'header.theme' => 'required|string|max:50',
            'header.links' => 'required|array|max:5',
            'header.links.*.url' => 'required|string|max:255',
            'header.links.*.label' => 'required|string|max:100',
            'header.links.*.target' => 'nullable|string|in:_self,_blank',
            'footer' => 'required|array',
            'footer.theme' => 'required|string|max:50',
            'footer.links' => 'required|array|max:10',
            'footer.links.*.url' => 'required|string|max:255',
            'footer.links.*.label' => 'required|string|max:100',
            'footer.links.*.target' => 'nullable|string|in:_self,_blank',
        ],
        'colors' => [
            'primary' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'nav' => 'nullable|array',
            'nav.background' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'nav.text' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'footer' => 'nullable|array',
            'footer.background' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'footer.text' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
        ],
        'sections' => [
            'active_sections' => 'required|array',
            'active_sections.*' => 'required|array',
            'active_sections.*.section_id' => 'required|string|max:50',
            'active_sections.*.is_active' => 'required|boolean',
            'active_sections.*.sort_order' => 'required|integer|min:0',
            'section_content' => 'nullable|array',
        ],
        'media' => [
            'max_file_size' => 'nullable|integer|min:1|max:10240', // KB
            'allowed_types' => 'nullable|array',
            'allowed_types.*' => 'string',
            'image_quality' => 'nullable|integer|min:1|max:100',
            'thumbnail_sizes' => 'nullable|array',
        ],
        'tenant' => [
            'tenant_id' => 'required|string|max:100',
            'domain' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]
    ];

    /**
     * Get configuration by type and site ID
     */
    public function get(int $siteId, string $type, $default = null)
    {
        $cacheKey = $this->getCacheKey($siteId, $type);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($siteId, $type, $default) {
            $siteConfig = SiteConfig::where('site_id', $siteId)->first();
            
            if (!$siteConfig) {
                return $default;
            }

            return $this->extractConfigByType($siteConfig, $type) ?? $default;
        });
    }

    /**
     * Set configuration by type and site ID
     */
    public function set(int $siteId, string $type, array $data, bool $merge = false): bool
    {
        try {
            // Validate the configuration data
            if (!$this->validate($type, $data)) {
                return false;
            }

            $siteConfig = SiteConfig::firstOrCreate(['site_id' => $siteId]);
            
            // Add versioning info
            $data['_meta'] = [
                'updated_at' => Carbon::now()->toISOString(),
                'version' => $this->getNextVersion($siteConfig, $type),
            ];

            if ($merge && $existing = $this->extractConfigByType($siteConfig, $type)) {
                $data = array_merge_recursive($existing, $data);
            }

            $this->storeConfigByType($siteConfig, $type, $data);
            $siteConfig->save();

            // Clear cache
            $this->clearCache($siteId, $type);

            Log::info("Configuration updated", [
                'site_id' => $siteId,
                'type' => $type,
                'version' => $data['_meta']['version']
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to set configuration", [
                'site_id' => $siteId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Validate configuration data against schema
     */
    public function validate(string $type, array $data): bool
    {
        if (!isset($this->schemas[$type])) {
            Log::warning("No validation schema found for type: {$type}");
            return true; // Allow unknown types for flexibility
        }

        $validator = Validator::make($data, $this->schemas[$type]);
        
        if ($validator->fails()) {
            Log::error("Configuration validation failed", [
                'type' => $type,
                'errors' => $validator->errors()->toArray()
            ]);
            return false;
        }

        return true;
    }

    /**
     * Get configuration schema for a specific type
     */
    public function getSchema(string $type): array
    {
        return $this->schemas[$type] ?? [];
    }

    /**
     * Get all configurations for a site
     */
    public function getAll(int $siteId): array
    {
        $siteConfig = SiteConfig::where('site_id', $siteId)->first();
        
        if (!$siteConfig) {
            return [];
        }

        return [
            'theme' => $this->extractConfigByType($siteConfig, 'theme'),
            'language' => $this->extractConfigByType($siteConfig, 'language'),
            'navigation' => $this->extractConfigByType($siteConfig, 'navigation'),
            'colors' => $this->extractConfigByType($siteConfig, 'colors'),
            'sections' => $this->extractConfigByType($siteConfig, 'sections'),
            'media' => $this->extractConfigByType($siteConfig, 'media'),
            'tenant' => $this->extractConfigByType($siteConfig, 'tenant'),
            'general' => $siteConfig->data ?? [],
            'settings' => $siteConfig->settings ?? [],
        ];
    }

    /**
     * Clear configuration cache
     */
    public function clearCache(int $siteId, ?string $type = null): void
    {
        if ($type) {
            Cache::forget($this->getCacheKey($siteId, $type));
        } else {
            // Clear all cache for the site
            $types = array_keys($this->schemas);
            foreach ($types as $configType) {
                Cache::forget($this->getCacheKey($siteId, $configType));
            }
        }
    }

    /**
     * Get configuration history/versions
     */
    public function getVersions(int $siteId, string $type): array
    {
        $siteConfig = SiteConfig::where('site_id', $siteId)->first();
        
        if (!$siteConfig) {
            return [];
        }

        $config = $this->extractConfigByType($siteConfig, $type);
        
        if (!$config || !isset($config['_versions'])) {
            return [];
        }

        return $config['_versions'];
    }

    /**
     * Rollback to a specific version
     */
    public function rollback(int $siteId, string $type, int $version): bool
    {
        $versions = $this->getVersions($siteId, $type);
        
        if (!isset($versions[$version])) {
            return false;
        }

        $data = $versions[$version];
        unset($data['_meta'], $data['_versions']); // Remove metadata
        
        return $this->set($siteId, $type, $data);
    }

    /**
     * Extract configuration by type from SiteConfig model
     */
    protected function extractConfigByType(SiteConfig $siteConfig, string $type)
    {
        switch ($type) {
            case 'theme':
                return $siteConfig->tpl_colors ?? null;
            case 'language':
                return $siteConfig->language_code ?? null;
            case 'navigation':
            case 'colors':
            case 'sections':
            case 'media':
            case 'tenant':
                $data = $siteConfig->data ?? [];
                return $data[$type] ?? null;
            default:
                $settings = $siteConfig->settings ?? [];
                return $settings[$type] ?? null;
        }
    }

    /**
     * Store configuration by type in SiteConfig model
     */
    protected function storeConfigByType(SiteConfig $siteConfig, string $type, array $data): void
    {
        switch ($type) {
            case 'theme':
                $siteConfig->tpl_colors = $data;
                break;
            case 'language':
                $siteConfig->language_code = $data;
                break;
            case 'navigation':
            case 'colors':
            case 'sections':
            case 'media':
            case 'tenant':
                $currentData = $siteConfig->data ?? [];
                $currentData[$type] = $data;
                $siteConfig->data = $currentData;
                break;
            default:
                $currentSettings = $siteConfig->settings ?? [];
                $currentSettings[$type] = $data;
                $siteConfig->settings = $currentSettings;
                break;
        }
    }

    /**
     * Get cache key for configuration
     */
    protected function getCacheKey(int $siteId, string $type): string
    {
        return self::CACHE_PREFIX . $siteId . '_' . $type;
    }

    /**
     * Get next version number for configuration type
     */
    protected function getNextVersion(SiteConfig $siteConfig, string $type): int
    {
        $config = $this->extractConfigByType($siteConfig, $type);
        
        if (!$config || !isset($config['_meta']['version'])) {
            return 1;
        }

        return $config['_meta']['version'] + 1;
    }

    /**
     * Get default configuration for a type
     */
    public function getDefaults(string $type): array
    {
        $defaults = [
            'theme' => [
                'theme' => 'business',
                'header_theme' => 'modern-header',
                'footer_theme' => 'simple-footer',
                'page_themes' => []
            ],
            'language' => [
                'languages' => ['en'],
                'primary_language' => 'en',
                'rtl_languages' => ['ar']
            ],
            'navigation' => [
                'header' => [
                    'theme' => 'modern-header',
                    'links' => [
                        ['url' => '/', 'label' => 'Home', 'target' => '_self'],
                        ['url' => '/about', 'label' => 'About', 'target' => '_self'],
                        ['url' => '/services', 'label' => 'Services', 'target' => '_self']
                    ]
                ],
                'footer' => [
                    'theme' => 'simple-footer',
                    'links' => [
                        ['url' => '/privacy', 'label' => 'Privacy Policy', 'target' => '_self'],
                        ['url' => '/terms', 'label' => 'Terms of Service', 'target' => '_self']
                    ]
                ]
            ],
            'colors' => [
                'primary' => '#007bff',
                'secondary' => '#6c757d',
                'nav' => [
                    'background' => '#ffffff',
                    'text' => '#000000'
                ],
                'footer' => [
                    'background' => '#f8f9fa',
                    'text' => '#000000'
                ]
            ],
            'sections' => [
                'active_sections' => [],
                'section_content' => []
            ],
            'media' => [
                'max_file_size' => 2048, // 2MB
                'allowed_types' => ['image/*'],
                'image_quality' => 85,
                'thumbnail_sizes' => [
                    'small' => ['width' => 150, 'height' => 150],
                    'medium' => ['width' => 300, 'height' => 300],
                    'large' => ['width' => 800, 'height' => 600]
                ]
            ],
            'tenant' => [
                'tenant_id' => 'default',
                'domain' => null,
                'custom_css' => '',
                'custom_js' => ''
            ]
        ];

        return $defaults[$type] ?? [];
    }

    /**
     * Initialize default configurations for a site
     */
    public function initializeDefaults(int $siteId): bool
    {
        try {
            $types = array_keys($this->schemas);
            
            foreach ($types as $type) {
                $defaults = $this->getDefaults($type);
                if (!empty($defaults)) {
                    $this->set($siteId, $type, $defaults);
                }
            }

            Log::info("Default configurations initialized for site", ['site_id' => $siteId]);
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to initialize default configurations", [
                'site_id' => $siteId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Export configurations for backup
     */
    public function export(int $siteId): array
    {
        return [
            'site_id' => $siteId,
            'exported_at' => Carbon::now()->toISOString(),
            'configurations' => $this->getAll($siteId)
        ];
    }

    /**
     * Import configurations from backup
     */
    public function import(int $siteId, array $backup): bool
    {
        if (!isset($backup['configurations'])) {
            return false;
        }

        try {
            foreach ($backup['configurations'] as $type => $data) {
                if (in_array($type, ['general', 'settings'])) {
                    continue; // Skip these as they're handled differently
                }
                
                if (!empty($data)) {
                    $this->set($siteId, $type, $data);
                }
            }

            Log::info("Configurations imported for site", ['site_id' => $siteId]);
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to import configurations", [
                'site_id' => $siteId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
