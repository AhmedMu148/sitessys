<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ConfigurationService;

class SiteConfig extends Model
{
    use HasFactory;
    
    protected $table = 'site_config';
    
    protected $fillable = [
        'site_id',
        'settings',
        'data',
        'language_code',
        'tpl_name',
        'tpl_colors'
    ];
    
    protected $casts = [
        'settings' => 'array',
        'data' => 'array',
        'language_code' => 'array',
        'tpl_colors' => 'array'
    ];

    /**
     * Boot method to clear cache when model is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            app(ConfigurationService::class)->clearCache($model->site_id);
        });

        static::deleted(function ($model) {
            app(ConfigurationService::class)->clearCache($model->site_id);
        });
    }
    
    /**
     * Get the site this config belongs to
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get configuration by type using ConfigurationService
     */
    public function getConfig(string $type, $default = null)
    {
        return app(ConfigurationService::class)->get($this->site_id, $type, $default);
    }

    /**
     * Set configuration by type using ConfigurationService
     */
    public function setConfig(string $type, array $data, bool $merge = false): bool
    {
        return app(ConfigurationService::class)->set($this->site_id, $type, $data, $merge);
    }

    /**
     * Get theme configuration
     */
    public function getThemeConfig()
    {
        return $this->getConfig('theme', [
            'theme' => $this->tpl_name ?? 'business',
            'header_theme' => 'modern-header',
            'footer_theme' => 'simple-footer'
        ]);
    }

    /**
     * Get language configuration
     */
    public function getLanguageConfig()
    {
        return $this->getConfig('language', [
            'languages' => ['en'],
            'primary_language' => 'en',
            'rtl_languages' => ['ar']
        ]);
    }

    /**
     * Get navigation configuration
     */
    public function getNavigationConfig()
    {
        return $this->getConfig('navigation', [
            'header' => [
                'theme' => 'modern-header',
                'links' => []
            ],
            'footer' => [
                'theme' => 'simple-footer', 
                'links' => []
            ]
        ]);
    }

    /**
     * Get colors configuration
     */
    public function getColorsConfig()
    {
        return $this->getConfig('colors', [
            'primary' => '#007bff',
            'secondary' => '#6c757d'
        ]);
    }

    /**
     * Get sections configuration
     */
    public function getSectionsConfig()
    {
        return $this->getConfig('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);
    }

    /**
     * Get media configuration
     */
    public function getMediaConfig()
    {
        return $this->getConfig('media', [
            'max_file_size' => 2048,
            'allowed_types' => ['image/*'],
            'image_quality' => 85
        ]);
    }

    /**
     * Get all settings as array
     */
    public function getSettingsAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []);
    }

    /**
     * Set settings as JSON
     */
    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get all data as array
     */
    public function getDataAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []);
    }

    /**
     * Set data as JSON
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get language code as array
     */
    public function getLanguageCodeAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []);
    }

    /**
     * Set language code as JSON
     */
    public function setLanguageCodeAttribute($value)
    {
        $this->attributes['language_code'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get tpl colors as array
     */
    public function getTplColorsAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []);
    }

    /**
     * Set tpl colors as JSON
     */
    public function setTplColorsAttribute($value)
    {
        $this->attributes['tpl_colors'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        $config = $this->getLanguageConfig();
        return $config['languages'] ?? ['en'];
    }

    /**
     * Get primary language
     */
    public function getPrimaryLanguage()
    {
        $config = $this->getLanguageConfig();
        return $config['primary_language'] ?? 'en';
    }

    /**
     * Check if language is RTL
     */
    public function isRtlLanguage(string $language): bool
    {
        $config = $this->getLanguageConfig();
        return in_array($language, $config['rtl_languages'] ?? ['ar']);
    }

    /**
     * Get current theme
     */
    public function getCurrentTheme(): string
    {
        $config = $this->getThemeConfig();
        return $config['theme'] ?? $this->tpl_name ?? 'business';
    }

    /**
     * Get header theme
     */
    public function getHeaderTheme(): string
    {
        $config = $this->getThemeConfig();
        return $config['header_theme'] ?? 'modern-header';
    }

    /**
     * Get footer theme
     */
    public function getFooterTheme(): string
    {
        $config = $this->getThemeConfig();
        return $config['footer_theme'] ?? 'simple-footer';
    }

    /**
     * Get navigation links for header
     */
    public function getHeaderLinks(): array
    {
        $config = $this->getNavigationConfig();
        return $config['header']['links'] ?? [];
    }

    /**
     * Get navigation links for footer
     */
    public function getFooterLinks(): array
    {
        $config = $this->getNavigationConfig();
        return $config['footer']['links'] ?? [];
    }

    /**
     * Get active sections
     */
    public function getActiveSections(): array
    {
        $config = $this->getSectionsConfig();
        return array_filter($config['active_sections'] ?? [], function($section) {
            return $section['is_active'] ?? false;
        });
    }

    /**
     * Check if a section is active
     */
    public function isSectionActive(string $sectionId): bool
    {
        $activeSections = $this->getActiveSections();
        foreach ($activeSections as $section) {
            if ($section['section_id'] === $sectionId) {
                return $section['is_active'] ?? false;
            }
        }
        return false;
    }

    /**
     * Scope for searching JSON fields
     */
    public function scopeWhereJsonContains($query, string $field, string $key, $value)
    {
        return $query->whereRaw("JSON_EXTRACT({$field}, '$.{$key}') = ?", [$value]);
    }

    /**
     * Scope for searching in language configurations
     */
    public function scopeByLanguage($query, string $language)
    {
        return $query->whereJsonContains('language_code->languages', $language);
    }

    /**
     * Scope for searching by theme
     */
    public function scopeByTheme($query, string $theme)
    {
        return $query->where('tpl_name', $theme)
                    ->orWhereJsonContains('tpl_colors->theme', $theme);
    }
}
