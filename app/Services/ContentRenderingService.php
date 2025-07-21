<?php

namespace App\Services;

use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPageSection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ContentRenderingService
{
    protected ConfigurationService $configService;
    
    /**
     * Cache TTL for rendered content in minutes
     */
    const CONTENT_CACHE_TTL = 30;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Render a section with content and configuration
     */
    public function renderSection(TplLayout $sectionTemplate, array $content = [], string $language = 'en', Site $site = null): string
    {
        $cacheKey = $this->getSectionCacheKey($sectionTemplate->id, $content, $language, $site?->id);
        
        return Cache::remember($cacheKey, self::CONTENT_CACHE_TTL, function () use ($sectionTemplate, $content, $language, $site) {
            try {
                // Get section configuration
                $sectionConfig = $this->getSectionConfiguration($sectionTemplate, $site);
                
                // Merge default config with provided content
                $mergedContent = $this->mergeContentWithDefaults($sectionTemplate, $content, $language);
                
                // Get site-specific styling
                $siteStyles = $this->getSiteStyles($site, $language);
                
                // Render HTML template
                $html = $this->processTemplate(
                    $sectionTemplate->content['html'] ?? '',
                    $mergedContent,
                    $sectionConfig,
                    $siteStyles,
                    $language
                );
                
                // Apply responsive optimizations
                $html = $this->applyResponsiveOptimizations($html, $sectionConfig);
                
                // Apply custom CSS and JS
                $html = $this->applySectionAssets($html, $sectionTemplate, $siteStyles);
                
                return $html;
                
            } catch (\Exception $e) {
                Log::error('Section rendering failed', [
                    'section_id' => $sectionTemplate->id,
                    'error' => $e->getMessage()
                ]);
                
                return $this->renderErrorFallback($sectionTemplate, $e);
            }
        });
    }

    /**
     * Render multiple sections for a page
     */
    public function renderPageSections(array $sections, string $language = 'en', Site $site = null): string
    {
        $renderedSections = [];
        
        // Sort sections by sort_order
        usort($sections, function ($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });
        
        foreach ($sections as $sectionData) {
            if (!($sectionData['is_active'] ?? true)) {
                continue;
            }
            
            $sectionTemplate = TplLayout::find($sectionData['template_id'] ?? null);
            if (!$sectionTemplate || $sectionTemplate->layout_type !== 'section') {
                continue;
            }
            
            $content = $sectionData['content'][$language] ?? $sectionData['content']['en'] ?? [];
            
            $renderedSections[] = $this->renderSection($sectionTemplate, $content, $language, $site);
        }
        
        return implode("\n", $renderedSections);
    }

    /**
     * Get section configuration including site-specific overrides
     */
    protected function getSectionConfiguration(TplLayout $sectionTemplate, ?Site $site): array
    {
        $baseConfig = $sectionTemplate->default_config ?? [];
        
        if (!$site) {
            return $baseConfig;
        }
        
        // Get site-specific section configuration
        $siteConfig = $site->getConfiguration('sections', ['section_content' => []]);
        $siteSpecificConfig = $siteConfig['section_content'][$sectionTemplate->tpl_id] ?? [];
        
        return array_merge_recursive($baseConfig, $siteSpecificConfig);
    }

    /**
     * Merge content with default values
     */
    protected function mergeContentWithDefaults(TplLayout $sectionTemplate, array $content, string $language): array
    {
        $defaultContent = $sectionTemplate->default_config['content'] ?? [];
        $configurableFields = $sectionTemplate->configurable_fields ?? [];
        
        $mergedContent = [];
        
        // Apply configurable field defaults
        foreach ($configurableFields as $field) {
            $fieldName = $field['name'] ?? '';
            $defaultValue = $field['default_value'] ?? '';
            
            if ($fieldName) {
                $mergedContent[$fieldName] = $content[$fieldName] ?? $defaultValue;
            }
        }
        
        // Merge with provided content
        $mergedContent = array_merge($mergedContent, $content);
        
        // Handle multilingual content fallback
        if (!empty($defaultContent[$language])) {
            $mergedContent = array_merge($defaultContent[$language], $mergedContent);
        } elseif (!empty($defaultContent['en'])) {
            $mergedContent = array_merge($defaultContent['en'], $mergedContent);
        }
        
        return $mergedContent;
    }

    /**
     * Get site-specific styles and colors
     */
    protected function getSiteStyles(?Site $site, string $language): array
    {
        if (!$site) {
            return [
                'direction' => 'ltr',
                'colors' => [
                    'primary' => '#007bff',
                    'secondary' => '#6c757d'
                ]
            ];
        }
        
        $colorsConfig = $site->getConfiguration('colors', [
            'primary' => '#007bff',
            'secondary' => '#6c757d'
        ]);
        
        $languageConfig = $site->getConfiguration('language', [
            'rtl_languages' => ['ar']
        ]);
        
        $isRtl = in_array($language, $languageConfig['rtl_languages'] ?? []);
        
        return [
            'direction' => $isRtl ? 'rtl' : 'ltr',
            'colors' => $colorsConfig,
            'is_rtl' => $isRtl
        ];
    }

    /**
     * Process template with content and configuration
     */
    protected function processTemplate(string $template, array $content, array $config, array $styles, string $language): string
    {
        // Replace content placeholders
        $html = $this->replacePlaceholders($template, $content);
        
        // Replace configuration placeholders
        $html = $this->replaceConfigPlaceholders($html, $config);
        
        // Replace style placeholders
        $html = $this->replaceStylePlaceholders($html, $styles);
        
        // Apply language-specific transformations
        $html = $this->applyLanguageTransformations($html, $language, $styles['is_rtl']);
        
        return $html;
    }

    /**
     * Replace content placeholders in template
     */
    protected function replacePlaceholders(string $template, array $content): string
    {
        foreach ($content as $key => $value) {
            if (is_string($value)) {
                $template = str_replace('{{' . $key . '}}', $value, $template);
                $template = str_replace('{{ ' . $key . ' }}', $value, $template);
            } elseif (is_array($value)) {
                // Handle nested content
                foreach ($value as $subKey => $subValue) {
                    if (is_string($subValue)) {
                        $template = str_replace('{{' . $key . '.' . $subKey . '}}', $subValue, $template);
                        $template = str_replace('{{ ' . $key . '.' . $subKey . ' }}', $subValue, $template);
                    }
                }
            }
        }
        
        return $template;
    }

    /**
     * Replace configuration placeholders
     */
    protected function replaceConfigPlaceholders(string $template, array $config): string
    {
        foreach ($config as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $template = str_replace('{{config.' . $key . '}}', $value, $template);
                $template = str_replace('{{ config.' . $key . ' }}', $value, $template);
            }
        }
        
        return $template;
    }

    /**
     * Replace style placeholders
     */
    protected function replaceStylePlaceholders(string $template, array $styles): string
    {
        // Replace direction
        $template = str_replace('{{direction}}', $styles['direction'], $template);
        $template = str_replace('{{ direction }}', $styles['direction'], $template);
        
        // Replace colors
        foreach ($styles['colors'] as $colorKey => $colorValue) {
            $template = str_replace('{{color.' . $colorKey . '}}', $colorValue, $template);
            $template = str_replace('{{ color.' . $colorKey . ' }}', $colorValue, $template);
        }
        
        return $template;
    }

    /**
     * Apply language-specific transformations
     */
    protected function applyLanguageTransformations(string $html, string $language, bool $isRtl): string
    {
        // Add language attributes
        $html = preg_replace('/<(div|section|article)([^>]*)>/', '<$1$2 lang="' . $language . '">', $html);
        
        // Add RTL direction if needed
        if ($isRtl) {
            $html = preg_replace('/<(div|section|article)([^>]*?)(\s+dir="[^"]*")?([^>]*)>/', '<$1$2 dir="rtl"$4>', $html);
        }
        
        return $html;
    }

    /**
     * Apply responsive optimizations
     */
    protected function applyResponsiveOptimizations(string $html, array $config): string
    {
        // Add responsive classes based on configuration
        $responsiveConfig = $config['responsive'] ?? [];
        
        if (!empty($responsiveConfig['mobile_breakpoint'])) {
            $breakpoint = $responsiveConfig['mobile_breakpoint'];
            $html = str_replace('class="', 'class="responsive-' . $breakpoint . ' ', $html);
        }
        
        // Add lazy loading for images
        $html = preg_replace('/<img([^>]*?)src="([^"]*)"([^>]*?)>/', '<img$1src="$2" loading="lazy"$3>', $html);
        
        return $html;
    }

    /**
     * Apply section assets (CSS and JS)
     */
    protected function applySectionAssets(string $html, TplLayout $sectionTemplate, array $styles): string
    {
        $css = $sectionTemplate->content['css'] ?? '';
        $js = $sectionTemplate->content['js'] ?? '';
        
        // Process CSS with color variables
        if ($css) {
            $css = $this->processAssetVariables($css, $styles);
            $html = "<style>\n{$css}\n</style>\n" . $html;
        }
        
        // Add JS if present
        if ($js) {
            $js = $this->processAssetVariables($js, $styles);
            $html .= "\n<script>\n{$js}\n</script>";
        }
        
        return $html;
    }

    /**
     * Process asset variables (CSS/JS)
     */
    protected function processAssetVariables(string $asset, array $styles): string
    {
        // Replace color variables
        foreach ($styles['colors'] as $colorKey => $colorValue) {
            $asset = str_replace('var(--color-' . $colorKey . ')', $colorValue, $asset);
            $asset = str_replace('{{color.' . $colorKey . '}}', $colorValue, $asset);
        }
        
        // Replace direction variable
        $asset = str_replace('{{direction}}', $styles['direction'], $asset);
        
        return $asset;
    }

    /**
     * Render error fallback
     */
    protected function renderErrorFallback(TplLayout $sectionTemplate, \Exception $e): string
    {
        if (app()->environment('production')) {
            return '<div class="section-error" style="display: none;"><!-- Section rendering error --></div>';
        }
        
        return '<div class="alert alert-danger">
            <h5>Section Rendering Error</h5>
            <p><strong>Section:</strong> ' . e($sectionTemplate->name) . '</p>
            <p><strong>Error:</strong> ' . e($e->getMessage()) . '</p>
        </div>';
    }

    /**
     * Get cache key for section rendering
     */
    protected function getSectionCacheKey(int $sectionId, array $content, string $language, ?int $siteId): string
    {
        $contentHash = md5(json_encode($content));
        return "section_render_{$sectionId}_{$language}_{$siteId}_{$contentHash}";
    }

    /**
     * Clear section rendering cache
     */
    public function clearSectionCache(int $sectionId, ?int $siteId = null): void
    {
        $pattern = "section_render_{$sectionId}_*";
        if ($siteId) {
            $pattern = "section_render_{$sectionId}_*_{$siteId}_*";
        }
        
        // Note: This is a simplified cache clearing - in production, you might need
        // a more sophisticated cache tagging system
        Cache::tags(['sections', "section_{$sectionId}"])->flush();
    }

    /**
     * Preload section assets for performance
     */
    public function preloadSectionAssets(array $sectionIds): array
    {
        $assets = [
            'css' => [],
            'js' => [],
            'images' => []
        ];
        
        $sections = TplLayout::whereIn('id', $sectionIds)
            ->where('layout_type', 'section')
            ->get();
        
        foreach ($sections as $section) {
            if (!empty($section->content['css'])) {
                $assets['css'][] = $section->content['css'];
            }
            
            if (!empty($section->content['js'])) {
                $assets['js'][] = $section->content['js'];
            }
            
            // Extract image URLs from content
            if (!empty($section->preview_image)) {
                $assets['images'][] = $section->preview_image;
            }
        }
        
        return $assets;
    }

    /**
     * Generate section schema for structured data
     */
    public function generateSectionSchema(TplLayout $sectionTemplate, array $content): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPageElement',
            'name' => $sectionTemplate->name,
            'description' => $sectionTemplate->description,
        ];
        
        // Add content-specific schema
        if (!empty($content['title'])) {
            $schema['headline'] = $content['title'];
        }
        
        if (!empty($content['description'])) {
            $schema['text'] = $content['description'];
        }
        
        return $schema;
    }
}
