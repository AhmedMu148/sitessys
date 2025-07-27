<?php

namespace App\Services;

use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NavigationService
{
    private const HEADER_LINK_LIMIT = 5;
    private const FOOTER_LINK_LIMIT = 10;
    private const CACHE_TTL = 1800; // 30 minutes
    
    /**
     * Get navigation configuration for a site
     */
    public function getNavigationConfig(int $siteId): array
    {
        $cacheKey = 'navigation_config_' . $siteId;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($siteId) {
            $site = Site::with('config')->find($siteId);
            
            if (!$site || !$site->config) {
                return $this->getDefaultNavigationConfig();
            }
            
            $settings = $site->config->settings ?? [];
            
            return [
                'header_links' => $settings['navigation']['header_links'] ?? [],
                'footer_links' => $settings['navigation']['footer_links'] ?? [],
                'show_auth_in_header' => $settings['navigation']['show_auth_in_header'] ?? true,
                'show_auth_in_footer' => $settings['navigation']['show_auth_in_footer'] ?? true,
                'limits' => [
                    'header' => self::HEADER_LINK_LIMIT,
                    'footer' => self::FOOTER_LINK_LIMIT
                ]
            ];
        });
    }
    
    /**
     * Update navigation configuration
     */
    public function updateNavigationConfig(int $siteId, array $config): bool
    {
        try {
            $site = Site::with('config')->findOrFail($siteId);
            
            // Ensure we don't exceed limits
            $headerLinks = array_slice($config['header_links'] ?? [], 0, self::HEADER_LINK_LIMIT);
            $footerLinks = array_slice($config['footer_links'] ?? [], 0, self::FOOTER_LINK_LIMIT);
            
            // Validate links
            $headerLinks = $this->validateLinks($headerLinks);
            $footerLinks = $this->validateLinks($footerLinks);
            
            $navigationConfig = [
                'header_links' => $headerLinks,
                'footer_links' => $footerLinks,
                'show_auth_in_header' => $config['show_auth_in_header'] ?? true,
                'show_auth_in_footer' => $config['show_auth_in_footer'] ?? true
            ];
            
            // Create or update site config
            if (!$site->config) {
                SiteConfig::create([
                    'site_id' => $siteId,
                    'settings' => ['navigation' => $navigationConfig],
                    'data' => [],
                    'language_code' => ['primary_language' => 'en'],
                    'tpl_name' => 'business',
                    'tpl_colors' => []
                ]);
            } else {
                $settings = $site->config->settings ?? [];
                $settings['navigation'] = $navigationConfig;
                $site->config->update(['settings' => $settings]);
            }
            
            $this->clearCache($siteId);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update navigation config: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get available pages for navigation
     */
    public function getAvailablePages(int $siteId): array
    {
        $cacheKey = 'available_pages_' . $siteId;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($siteId) {
            return TplPage::where('site_id', $siteId)
                         ->where('status', 'published')
                         ->select('id', 'name', 'slug', 'show_in_nav')
                         ->orderBy('name')
                         ->get()
                         ->toArray();
        });
    }
    
    /**
     * Add a navigation link
     */
    public function addNavigationLink(int $siteId, string $type, array $linkData): bool
    {
        if (!in_array($type, ['header', 'footer'])) {
            return false;
        }
        
        $config = $this->getNavigationConfig($siteId);
        $currentLinks = $config[$type . '_links'];
        
        // Check limits
        $limit = $type === 'header' ? self::HEADER_LINK_LIMIT : self::FOOTER_LINK_LIMIT;
        if (count($currentLinks) >= $limit) {
            return false;
        }
        
        // Validate and add link
        $validatedLink = $this->validateLink($linkData);
        if (!$validatedLink) {
            return false;
        }
        
        $currentLinks[] = $validatedLink;
        $config[$type . '_links'] = $currentLinks;
        
        return $this->updateNavigationConfig($siteId, $config);
    }
    
    /**
     * Remove a navigation link
     */
    public function removeNavigationLink(int $siteId, string $type, int $index): bool
    {
        if (!in_array($type, ['header', 'footer'])) {
            return false;
        }
        
        $config = $this->getNavigationConfig($siteId);
        $currentLinks = $config[$type . '_links'];
        
        if (!isset($currentLinks[$index])) {
            return false;
        }
        
        unset($currentLinks[$index]);
        $currentLinks = array_values($currentLinks); // Re-index array
        $config[$type . '_links'] = $currentLinks;
        
        return $this->updateNavigationConfig($siteId, $config);
    }
    
    /**
     * Update navigation link status (active/inactive)
     */
    public function updateLinkStatus(int $siteId, string $type, int $index, bool $active): bool
    {
        if (!in_array($type, ['header', 'footer'])) {
            return false;
        }
        
        $config = $this->getNavigationConfig($siteId);
        $currentLinks = $config[$type . '_links'];
        
        if (!isset($currentLinks[$index])) {
            return false;
        }
        
        $currentLinks[$index]['active'] = $active;
        $config[$type . '_links'] = $currentLinks;
        
        return $this->updateNavigationConfig($siteId, $config);
    }
    
    /**
     * Render navigation menu HTML
     */
    public function renderNavigationMenu(int $siteId, string $type = 'header'): string
    {
        $config = $this->getNavigationConfig($siteId);
        $links = $config[$type . '_links'] ?? [];
        $activeLinks = array_filter($links, function($link) {
            return $link['active'] ?? true;
        });
        
        if (empty($activeLinks)) {
            return '';
        }
        
        $html = '<ul class="nav-list ' . $type . '-nav">';
        
        foreach ($activeLinks as $link) {
            $url = $this->generateLinkUrl($link);
            $target = ($link['external'] ?? false) ? ' target="_blank"' : '';
            $rel = ($link['external'] ?? false) ? ' rel="noopener noreferrer"' : '';
            
            $html .= '<li class="nav-item">';
            $html .= '<a href="' . htmlspecialchars($url) . '" class="nav-link"' . $target . $rel . '>';
            $html .= htmlspecialchars($link['title']);
            $html .= '</a></li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
    
    /**
     * Render authentication section
     */
    public function renderAuthSection(): string
    {
        if (Auth::check()) {
            $user = Auth::user();
            return '<div class="auth-section authenticated">
                        <div class="dropdown">
                            <a href="#" class="auth-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="feather-user"></i> ' . htmlspecialchars($user->name) . '
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="' . route('admin.dashboard') . '"><i class="feather-settings"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="' . route('profile.show') . '"><i class="feather-user"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="' . route('logout') . '" class="d-inline">
                                        ' . csrf_field() . '
                                        <button type="submit" class="dropdown-item"><i class="feather-log-out"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>';
        } else {
            return '<div class="auth-section unauthenticated">
                        <a href="' . route('login') . '" class="auth-link login-link">
                            <i class="feather-log-in"></i> Login
                        </a>
                        <a href="' . route('register') . '" class="auth-link register-link">
                            <i class="feather-user-plus"></i> Register
                        </a>
                    </div>';
        }
    }
    
    /**
     * Get social media links from site config
     */
    public function getSocialMediaLinks(int $siteId): array
    {
        $site = Site::with('config')->find($siteId);
        
        if (!$site || !$site->config) {
            return [];
        }
        
        $data = $site->config->data ?? [];
        return $data['social_media'] ?? [];
    }
    
    /**
     * Render social media links HTML
     */
    public function renderSocialMediaLinks(int $siteId): string
    {
        $socialLinks = $this->getSocialMediaLinks($siteId);
        
        if (empty($socialLinks)) {
            return '';
        }
        
        $html = '<div class="social-media-links">';
        
        foreach ($socialLinks as $platform => $url) {
            if (!empty($url)) {
                $html .= '<a href="' . htmlspecialchars($url) . '" class="social-link social-' . $platform . '" target="_blank" rel="noopener noreferrer">';
                $html .= '<i class="feather-' . $this->getSocialIcon($platform) . '"></i>';
                $html .= '</a>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Clear navigation cache
     */
    public function clearCache(int $siteId): void
    {
        Cache::forget('navigation_config_' . $siteId);
        Cache::forget('available_pages_' . $siteId);
    }
    
    /**
     * Get default navigation configuration
     */
    private function getDefaultNavigationConfig(): array
    {
        return [
            'header_links' => [
                [
                    'title' => 'Home',
                    'url' => '/',
                    'type' => 'internal',
                    'active' => true,
                    'external' => false
                ]
            ],
            'footer_links' => [
                [
                    'title' => 'Home',
                    'url' => '/',
                    'type' => 'internal',
                    'active' => true,
                    'external' => false
                ],
                [
                    'title' => 'Privacy Policy',
                    'url' => '/privacy',
                    'type' => 'internal',
                    'active' => true,
                    'external' => false
                ],
                [
                    'title' => 'Terms of Service',
                    'url' => '/terms',
                    'type' => 'internal',
                    'active' => true,
                    'external' => false
                ]
            ],
            'show_auth_in_header' => true,
            'show_auth_in_footer' => true,
            'limits' => [
                'header' => self::HEADER_LINK_LIMIT,
                'footer' => self::FOOTER_LINK_LIMIT
            ]
        ];
    }
    
    /**
     * Validate navigation links array
     */
    private function validateLinks(array $links): array
    {
        $validatedLinks = [];
        
        foreach ($links as $link) {
            $validatedLink = $this->validateLink($link);
            if ($validatedLink) {
                $validatedLinks[] = $validatedLink;
            }
        }
        
        return $validatedLinks;
    }
    
    /**
     * Validate a single navigation link
     */
    private function validateLink(array $link): ?array
    {
        if (empty($link['title']) || empty($link['url'])) {
            return null;
        }
        
        return [
            'title' => trim($link['title']),
            'url' => trim($link['url']),
            'type' => $link['type'] ?? 'internal',
            'active' => $link['active'] ?? true,
            'external' => $link['external'] ?? false,
            'page_id' => $link['page_id'] ?? null
        ];
    }
    
    /**
     * Generate URL for a navigation link
     */
    private function generateLinkUrl(array $link): string
    {
        if ($link['external'] ?? false) {
            return $link['url'];
        }
        
        if ($link['type'] === 'page' && !empty($link['page_id'])) {
            $page = TplPage::find($link['page_id']);
            if ($page) {
                return url('/' . $page->slug);
            }
        }
        
        return url($link['url']);
    }
    
    /**
     * Get social media icon name
     */
    private function getSocialIcon(string $platform): string
    {
        $icons = [
            'facebook' => 'facebook',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'linkedin' => 'linkedin',
            'youtube' => 'youtube',
            'github' => 'github',
            'discord' => 'message-circle',
            'tiktok' => 'video',
            'pinterest' => 'image'
        ];
        
        return $icons[$platform] ?? 'link';
    }
}
