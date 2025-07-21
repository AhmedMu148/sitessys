<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class PerformanceOptimizationService
{
    /**
     * Cache configuration
     */
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const SHORT_CACHE_TTL = 300;    // 5 minutes
    const LONG_CACHE_TTL = 86400;   // 24 hours

    /**
     * Enable or disable performance optimizations globally
     */
    protected bool $optimizationsEnabled;

    public function __construct()
    {
        $this->optimizationsEnabled = config('app.performance_optimizations', true);
    }

    /**
     * Cache rendered content with intelligent expiration
     */
    public function cacheContent(string $key, string $content, ?int $ttl = null): bool
    {
        if (!$this->optimizationsEnabled) {
            return false;
        }

        try {
            $ttl = $ttl ?? self::DEFAULT_CACHE_TTL;
            
            // Add cache metadata
            $cacheData = [
                'content' => $content,
                'cached_at' => now()->toISOString(),
                'size' => strlen($content),
                'hash' => md5($content)
            ];

            // Use Redis if available, fallback to default cache
            if ($this->isRedisAvailable()) {
                Redis::setex($this->getCacheKey($key), $ttl, json_encode($cacheData));
            } else {
                Cache::put($this->getCacheKey($key), $cacheData, $ttl);
            }

            // Log cache operation for monitoring
            $this->logCacheOperation('store', $key, strlen($content));

            return true;

        } catch (\Exception $e) {
            Log::error('Content caching failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Retrieve cached content
     */
    public function getCachedContent(string $key): ?string
    {
        if (!$this->optimizationsEnabled) {
            return null;
        }

        try {
            $cacheKey = $this->getCacheKey($key);
            
            // Try Redis first
            if ($this->isRedisAvailable()) {
                $data = Redis::get($cacheKey);
                if ($data) {
                    $cacheData = json_decode($data, true);
                    $this->logCacheOperation('hit', $key, $cacheData['size'] ?? 0);
                    return $cacheData['content'] ?? null;
                }
            } else {
                $cacheData = Cache::get($cacheKey);
                if ($cacheData && is_array($cacheData)) {
                    $this->logCacheOperation('hit', $key, $cacheData['size'] ?? 0);
                    return $cacheData['content'] ?? null;
                }
            }

            // Cache miss
            $this->logCacheOperation('miss', $key, 0);
            return null;

        } catch (\Exception $e) {
            Log::error('Content cache retrieval failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Invalidate cached content
     */
    public function invalidateCache(string $pattern = '*'): bool
    {
        try {
            if ($this->isRedisAvailable()) {
                $keys = Redis::keys($this->getCacheKey($pattern));
                if (!empty($keys)) {
                    Redis::del($keys);
                }
            } else {
                // For file/database cache, use tags if available
                Cache::tags(['content'])->flush();
            }

            Log::info('Cache invalidated', ['pattern' => $pattern]);
            return true;

        } catch (\Exception $e) {
            Log::error('Cache invalidation failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Optimize images for web delivery
     */
    public function optimizeImage(string $imagePath, array $options = []): ?string
    {
        if (!$this->optimizationsEnabled) {
            return $imagePath;
        }

        try {
            $options = array_merge([
                'quality' => 85,
                'width' => null,
                'height' => null,
                'format' => 'webp',
                'progressive' => true
            ], $options);

            // Generate optimized version cache key
            $cacheKey = 'optimized_image_' . md5($imagePath . serialize($options));
            
            // Check if optimized version exists
            $optimizedPath = $this->getCachedContent($cacheKey);
            if ($optimizedPath && file_exists(public_path($optimizedPath))) {
                return $optimizedPath;
            }

            // Create optimized version (would integrate with image processing library)
            $optimizedPath = $this->processImageOptimization($imagePath, $options);
            
            if ($optimizedPath) {
                $this->cacheContent($cacheKey, $optimizedPath, self::LONG_CACHE_TTL);
                return $optimizedPath;
            }

            return $imagePath;

        } catch (\Exception $e) {
            Log::error('Image optimization failed', [
                'image_path' => $imagePath,
                'error' => $e->getMessage()
            ]);
            
            return $imagePath;
        }
    }

    /**
     * Minify HTML content
     */
    public function minifyHtml(string $html): string
    {
        if (!$this->optimizationsEnabled) {
            return $html;
        }

        try {
            // Remove HTML comments (except conditional comments)
            $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
            
            // Remove unnecessary whitespace
            $html = preg_replace('/\s+/', ' ', $html);
            $html = preg_replace('/>\s+</', '><', $html);
            
            // Remove whitespace around block elements
            $blockElements = 'div|p|h1|h2|h3|h4|h5|h6|article|section|nav|aside|header|footer|main|figure|figcaption';
            $html = preg_replace('/\s*(<\/?(?:' . $blockElements . ')[^>]*>)\s*/', '$1', $html);
            
            return trim($html);

        } catch (\Exception $e) {
            Log::error('HTML minification failed', [
                'error' => $e->getMessage()
            ]);
            
            return $html;
        }
    }

    /**
     * Minify CSS content
     */
    public function minifyCss(string $css): string
    {
        if (!$this->optimizationsEnabled) {
            return $css;
        }

        try {
            // Remove comments
            $css = preg_replace('/\/\*.*?\*\//s', '', $css);
            
            // Remove unnecessary whitespace
            $css = preg_replace('/\s+/', ' ', $css);
            $css = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ', '], [';', '{', '{', '}', '}', ':', ','], $css);
            
            // Remove trailing semicolon before closing brace
            $css = str_replace(';}', '}', $css);
            
            return trim($css);

        } catch (\Exception $e) {
            Log::error('CSS minification failed', [
                'error' => $e->getMessage()
            ]);
            
            return $css;
        }
    }

    /**
     * Minify JavaScript content
     */
    public function minifyJs(string $js): string
    {
        if (!$this->optimizationsEnabled) {
            return $js;
        }

        try {
            // Remove single-line comments (but preserve URLs)
            $js = preg_replace('/(?<!:)\/\/.*$/m', '', $js);
            
            // Remove multi-line comments
            $js = preg_replace('/\/\*.*?\*\//s', '', $js);
            
            // Remove unnecessary whitespace
            $js = preg_replace('/\s+/', ' ', $js);
            $js = str_replace(['; ', ' {', '{ ', ' }', '} ', ' (', '( ', ' )', ') ', ' =', '= ', ' +', '+ ', ' -', '- '], [';', '{', '{', '}', '}', '(', '(', ')', ')', '=', '=', '+', '+', '-', '-'], $js);
            
            return trim($js);

        } catch (\Exception $e) {
            Log::error('JavaScript minification failed', [
                'error' => $e->getMessage()
            ]);
            
            return $js;
        }
    }

    /**
     * Generate critical CSS for above-the-fold content
     */
    public function generateCriticalCss(string $html, string $css): string
    {
        if (!$this->optimizationsEnabled) {
            return '';
        }

        try {
            // Extract CSS selectors used in the HTML
            $usedSelectors = [];
            
            // Find all class attributes
            preg_match_all('/class=["\']([^"\']+)["\']/', $html, $classMatches);
            foreach ($classMatches[1] as $classes) {
                $classList = explode(' ', $classes);
                foreach ($classList as $class) {
                    $usedSelectors[] = '.' . trim($class);
                }
            }
            
            // Find all ID attributes
            preg_match_all('/id=["\']([^"\']+)["\']/', $html, $idMatches);
            foreach ($idMatches[1] as $id) {
                $usedSelectors[] = '#' . trim($id);
            }
            
            // Extract critical CSS rules
            $criticalCss = '';
            foreach ($usedSelectors as $selector) {
                $pattern = '/([^{}]*' . preg_quote($selector, '/') . '[^{}]*\{[^}]*\})/';
                preg_match_all($pattern, $css, $matches);
                foreach ($matches[1] as $rule) {
                    $criticalCss .= $rule . "\n";
                }
            }
            
            return $this->minifyCss($criticalCss);

        } catch (\Exception $e) {
            Log::error('Critical CSS generation failed', [
                'error' => $e->getMessage()
            ]);
            
            return '';
        }
    }

    /**
     * Lazy load images in HTML content
     */
    public function addLazyLoading(string $html): string
    {
        if (!$this->optimizationsEnabled) {
            return $html;
        }

        try {
            // Add loading="lazy" to img tags
            $html = preg_replace('/<img([^>]*?)src=/', '<img$1loading="lazy" src=', $html);
            
            // Add lazy loading for iframe (for videos, maps, etc.)
            $html = preg_replace('/<iframe([^>]*?)src=/', '<iframe$1loading="lazy" src=', $html);
            
            return $html;

        } catch (\Exception $e) {
            Log::error('Lazy loading implementation failed', [
                'error' => $e->getMessage()
            ]);
            
            return $html;
        }
    }

    /**
     * Compress content using gzip
     */
    public function compressContent(string $content): string
    {
        if (!$this->optimizationsEnabled || !function_exists('gzencode')) {
            return $content;
        }

        try {
            $compressed = gzencode($content, 9);
            
            // Only return compressed if it's actually smaller
            if (strlen($compressed) < strlen($content)) {
                return $compressed;
            }
            
            return $content;

        } catch (\Exception $e) {
            Log::error('Content compression failed', [
                'error' => $e->getMessage()
            ]);
            
            return $content;
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        try {
            $metrics = [
                'cache_hits' => $this->getCacheHits(),
                'cache_misses' => $this->getCacheMisses(),
                'cache_hit_ratio' => $this->getCacheHitRatio(),
                'average_response_time' => $this->getAverageResponseTime(),
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true),
                'redis_status' => $this->isRedisAvailable() ? 'connected' : 'disconnected',
                'optimizations_enabled' => $this->optimizationsEnabled
            ];

            return $metrics;

        } catch (\Exception $e) {
            Log::error('Performance metrics collection failed', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Clear all performance caches
     */
    public function clearAllCaches(): bool
    {
        try {
            // Clear application cache
            Cache::flush();
            
            // Clear Redis cache
            if ($this->isRedisAvailable()) {
                Redis::flushall();
            }
            
            // Clear view cache
            if (function_exists('view')) {
                Artisan::call('view:clear');
            }
            
            // Clear route cache
            Artisan::call('route:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            Log::info('All caches cleared successfully');
            return true;

        } catch (\Exception $e) {
            Log::error('Cache clearing failed', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Process image optimization (placeholder for actual implementation)
     */
    protected function processImageOptimization(string $imagePath, array $options): ?string
    {
        // This would integrate with libraries like Intervention Image or Imagick
        // For now, return the original path
        return $imagePath;
    }

    /**
     * Check if Redis is available
     */
    protected function isRedisAvailable(): bool
    {
        try {
            return Redis::ping() === '+PONG';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cache key with prefix
     */
    protected function getCacheKey(string $key): string
    {
        $prefix = config('app.name', 'SPS');
        return "{$prefix}:performance:{$key}";
    }

    /**
     * Log cache operations for monitoring
     */
    protected function logCacheOperation(string $operation, string $key, int $size): void
    {
        try {
            $logKey = "cache_stats_{$operation}";
            
            if ($this->isRedisAvailable()) {
                Redis::incr($logKey);
                Redis::expire($logKey, 86400); // Expire after 24 hours
            } else {
                $current = Cache::get($logKey, 0);
                Cache::put($logKey, $current + 1, 86400);
            }

        } catch (\Exception $e) {
            // Silently fail logging to prevent disrupting main functionality
        }
    }

    /**
     * Get cache hit count
     */
    protected function getCacheHits(): int
    {
        try {
            if ($this->isRedisAvailable()) {
                return (int) Redis::get('cache_stats_hit') ?? 0;
            }
            
            return (int) Cache::get('cache_stats_hit', 0);
            
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get cache miss count
     */
    protected function getCacheMisses(): int
    {
        try {
            if ($this->isRedisAvailable()) {
                return (int) Redis::get('cache_stats_miss') ?? 0;
            }
            
            return (int) Cache::get('cache_stats_miss', 0);
            
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculate cache hit ratio
     */
    protected function getCacheHitRatio(): float
    {
        $hits = $this->getCacheHits();
        $misses = $this->getCacheMisses();
        $total = $hits + $misses;
        
        return $total > 0 ? round(($hits / $total) * 100, 2) : 0.0;
    }

    /**
     * Get average response time (placeholder)
     */
    protected function getAverageResponseTime(): float
    {
        // This would require implementing response time tracking
        return 0.0;
    }
}
