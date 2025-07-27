<?php

namespace App\Services;

use App\Models\TplLayout;
use App\Models\Site;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class GlobalTemplateService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const GLOBAL_TEMPLATE_PREFIX = 'global-';
    
    /**
     * Get all global templates available to all users
     */
    public function getGlobalTemplates(string $type = null): array
    {
        $cacheKey = 'global_templates_' . ($type ?? 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($type) {
            $query = TplLayout::where('tpl_id', 'like', self::GLOBAL_TEMPLATE_PREFIX . '%')
                             ->where('status', true)
                             ->orderBy('sort_order');
            
            if ($type) {
                $query->where('layout_type', $type);
            }
            
            return $query->get()->toArray();
        });
    }
    
    /**
     * Get global headers
     */
    public function getGlobalHeaders(): array
    {
        return $this->getGlobalTemplates('header');
    }
    
    /**
     * Get global footers
     */
    public function getGlobalFooters(): array
    {
        return $this->getGlobalTemplates('footer');
    }
    
    /**
     * Create a user-specific copy of a global template
     */
    public function createUserCopy(int $globalTemplateId, int $userId, int $siteId): ?TplLayout
    {
        $globalTemplate = TplLayout::find($globalTemplateId);
        
        if (!$globalTemplate || !str_starts_with($globalTemplate->tpl_id, self::GLOBAL_TEMPLATE_PREFIX)) {
            return null;
        }
        
        // Generate user-specific template ID
        $userTplId = 'user-' . $userId . '-' . $siteId . '-' . time() . '-' . $globalTemplate->layout_type;
        
        // Create user copy
        $userTemplate = TplLayout::create([
            'tpl_id' => $userTplId,
            'layout_type' => $globalTemplate->layout_type,
            'name' => $globalTemplate->name . ' (Custom)',
            'description' => $globalTemplate->description . ' - Customized by user',
            'preview_image' => $globalTemplate->preview_image,
            'path' => $globalTemplate->path,
            'content' => $globalTemplate->content, // This will be customized
            'default_config' => array_merge(
                is_array($globalTemplate->default_config) ? $globalTemplate->default_config : [],
                [
                    'based_on_global' => $globalTemplate->id,
                    'user_id' => $userId,
                    'site_id' => $siteId,
                    'created_at' => now()->toISOString()
                ]
            ),
            'configurable_fields' => $globalTemplate->configurable_fields,
            'status' => true,
            'sort_order' => $this->getNextUserSortOrder($globalTemplate->layout_type, $siteId)
        ]);
        
        $this->clearCache();
        
        return $userTemplate;
    }
    
    /**
     * Get user-specific templates for a site
     */
    public function getUserTemplates(int $siteId, string $type = null): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }
        
        $cacheKey = 'user_templates_' . $user->id . '_' . $siteId . '_' . ($type ?? 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $siteId, $type) {
            $query = TplLayout::where(function ($q) use ($user, $siteId) {
                $q->where('tpl_id', 'like', 'user-' . $user->id . '-' . $siteId . '-%')
                  ->orWhere('tpl_id', 'like', 'custom-' . '%');
            })->where('status', true)->orderBy('sort_order');
            
            if ($type) {
                $query->where('layout_type', $type);
            }
            
            return $query->get()->toArray();
        });
    }
    
    /**
     * Check if a template belongs to a specific user/site
     */
    public function isUserTemplate(int $templateId, int $userId, int $siteId): bool
    {
        $template = TplLayout::find($templateId);
        
        if (!$template) {
            return false;
        }
        
        // Check if it's a user-specific template
        if (str_starts_with($template->tpl_id, 'user-' . $userId . '-' . $siteId . '-')) {
            return true;
        }
        
        // Check if it's a custom template created by this user
        if (str_starts_with($template->tpl_id, 'custom-')) {
            $config = is_array($template->default_config) ? $template->default_config : [];
            return isset($config['created_by']) && $config['created_by'] == $userId;
        }
        
        return false;
    }
    
    /**
     * Get all available templates for a user (global + user-specific)
     */
    public function getAvailableTemplates(int $siteId, string $type = null): array
    {
        $globalTemplates = $this->getGlobalTemplates($type);
        $userTemplates = $this->getUserTemplates($siteId, $type);
        
        return [
            'global' => $globalTemplates,
            'user' => $userTemplates
        ];
    }
    
    /**
     * Clear template cache
     */
    public function clearCache(): void
    {
        Cache::forget('global_templates_all');
        Cache::forget('global_templates_header');
        Cache::forget('global_templates_footer');
        
        if (Auth::check()) {
            $user = Auth::user();
            $sites = $user->sites()->pluck('id');
            
            foreach ($sites as $siteId) {
                Cache::forget('user_templates_' . $user->id . '_' . $siteId . '_all');
                Cache::forget('user_templates_' . $user->id . '_' . $siteId . '_header');
                Cache::forget('user_templates_' . $user->id . '_' . $siteId . '_footer');
            }
        }
    }
    
    /**
     * Get next sort order for user templates
     */
    private function getNextUserSortOrder(string $type, int $siteId): int
    {
        $user = Auth::user();
        if (!$user) {
            return 100;
        }
        
        $maxOrder = TplLayout::where('layout_type', $type)
                            ->where(function ($q) use ($user, $siteId) {
                                $q->where('tpl_id', 'like', 'user-' . $user->id . '-' . $siteId . '-%')
                                  ->orWhere('tpl_id', 'like', 'custom-%');
                            })
                            ->max('sort_order');
        
        return ($maxOrder ?? 99) + 1;
    }
    
    /**
     * Seed global templates (5 headers and 5 footers)
     */
    public function seedGlobalTemplates(): void
    {
        $this->seedGlobalHeaders();
        $this->seedGlobalFooters();
        $this->clearCache();
    }
    
    /**
     * Seed 5 global header templates
     */
    private function seedGlobalHeaders(): void
    {
        $headers = [
            [
                'tpl_id' => 'global-header-business',
                'name' => 'Business Header',
                'description' => 'Professional business header with navigation and call-to-action',
                'content' => [
                    'html' => '<header class="business-header">
                        <nav class="navbar navbar-expand-lg">
                            <div class="container">
                                <a class="navbar-brand" href="{{home_url}}">{{site_name}}</a>
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    {{navigation_menu}}
                                    <div class="navbar-nav ms-auto">
                                        {{auth_section}}
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </header>',
                    'css' => '.business-header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                             .business-header .navbar-brand { font-weight: bold; color: #333; }
                             .business-header .nav-link { color: #666; transition: color 0.3s; }
                             .business-header .nav-link:hover { color: #007bff; }'
                ]
            ],
            [
                'tpl_id' => 'global-header-creative',
                'name' => 'Creative Header',
                'description' => 'Modern creative header with artistic design elements',
                'content' => [
                    'html' => '<header class="creative-header">
                        <div class="header-overlay"></div>
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h1 class="site-title">{{site_name}}</h1>
                                </div>
                                <div class="col-md-6">
                                    <nav class="main-nav">
                                        {{navigation_menu}}
                                        {{auth_section}}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </header>',
                    'css' => '.creative-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; position: relative; }
                             .creative-header .header-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); }
                             .creative-header .container { position: relative; z-index: 2; }
                             .creative-header .site-title { font-size: 2.5rem; font-weight: bold; margin: 0; }'
                ]
            ],
            [
                'tpl_id' => 'global-header-minimal',
                'name' => 'Minimal Header',
                'description' => 'Clean minimal header with essential elements only',
                'content' => [
                    'html' => '<header class="minimal-header">
                        <div class="container">
                            <div class="header-content">
                                <div class="logo-section">
                                    <a href="{{home_url}}" class="logo">{{site_name}}</a>
                                </div>
                                <div class="nav-section">
                                    {{navigation_menu}}
                                </div>
                                <div class="auth-section">
                                    {{auth_section}}
                                </div>
                            </div>
                        </div>
                    </header>',
                    'css' => '.minimal-header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
                             .minimal-header .header-content { display: flex; justify-content: space-between; align-items: center; }
                             .minimal-header .logo { font-size: 1.5rem; font-weight: 600; text-decoration: none; color: #333; }
                             .minimal-header .nav-section ul { display: flex; list-style: none; margin: 0; padding: 0; }
                             .minimal-header .nav-section li { margin: 0 15px; }'
                ]
            ],
            [
                'tpl_id' => 'global-header-ecommerce',
                'name' => 'E-commerce Header',
                'description' => 'Feature-rich header for online stores with search and cart',
                'content' => [
                    'html' => '<header class="ecommerce-header">
                        <div class="top-bar">
                            <div class="container">
                                <div class="top-bar-content">
                                    <div class="contact-info">{{contact_info}}</div>
                                    <div class="social-links">{{social_media}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-header">
                            <div class="container">
                                <div class="header-row">
                                    <div class="logo-section">
                                        <a href="{{home_url}}">{{site_name}}</a>
                                    </div>
                                    <div class="search-section">
                                        <div class="search-box">{{search_form}}</div>
                                    </div>
                                    <div class="user-section">
                                        {{auth_section}}
                                    </div>
                                </div>
                                <nav class="main-navigation">
                                    {{navigation_menu}}
                                </nav>
                            </div>
                        </div>
                    </header>',
                    'css' => '.ecommerce-header .top-bar { background: #333; color: white; padding: 8px 0; font-size: 0.9rem; }
                             .ecommerce-header .top-bar-content { display: flex; justify-content: space-between; }
                             .ecommerce-header .main-header { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
                             .ecommerce-header .header-row { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; }
                             .ecommerce-header .search-box { width: 400px; }'
                ]
            ],
            [
                'tpl_id' => 'global-header-portfolio',
                'name' => 'Portfolio Header',
                'description' => 'Elegant header designed for portfolio and showcase websites',
                'content' => [
                    'html' => '<header class="portfolio-header">
                        <div class="container">
                            <div class="header-wrapper">
                                <div class="brand-section">
                                    <h1 class="brand-name">{{site_name}}</h1>
                                    <p class="brand-tagline">{{site_tagline}}</p>
                                </div>
                                <nav class="portfolio-nav">
                                    {{navigation_menu}}
                                    <div class="auth-wrapper">
                                        {{auth_section}}
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </header>',
                    'css' => '.portfolio-header { background: #f8f9fa; padding: 30px 0; border-bottom: 3px solid #007bff; }
                             .portfolio-header .header-wrapper { display: flex; justify-content: space-between; align-items: center; }
                             .portfolio-header .brand-name { font-size: 2rem; margin: 0; color: #333; }
                             .portfolio-header .brand-tagline { margin: 0; color: #666; font-style: italic; }
                             .portfolio-header .portfolio-nav { display: flex; align-items: center; gap: 30px; }'
                ]
            ]
        ];
        
        foreach ($headers as $index => $headerData) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $headerData['tpl_id']],
                [
                    'layout_type' => 'header',
                    'name' => $headerData['name'],
                    'description' => $headerData['description'],
                    'path' => 'frontend.templates.headers.global',
                    'content' => $headerData['content'],
                    'default_config' => [
                        'type' => 'global',
                        'category' => 'header',
                        'customizable' => true,
                        'supports_navigation' => true,
                        'supports_auth' => true,
                        'supports_social' => true
                    ],
                    'configurable_fields' => [
                        'navigation_menu',
                        'auth_section',
                        'social_media',
                        'contact_info',
                        'site_name',
                        'site_tagline',
                        'search_form'
                    ],
                    'status' => true,
                    'sort_order' => $index + 1
                ]
            );
        }
    }
    
    /**
     * Seed 5 global footer templates
     */
    private function seedGlobalFooters(): void
    {
        $footers = [
            [
                'tpl_id' => 'global-footer-business',
                'name' => 'Business Footer',
                'description' => 'Professional business footer with multiple sections',
                'content' => [
                    'html' => '<footer class="business-footer">
                        <div class="footer-main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5>{{site_name}}</h5>
                                        <p>{{site_description}}</p>
                                        <div class="social-links">{{social_media}}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Quick Links</h5>
                                        {{navigation_menu}}
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Contact Info</h5>
                                        {{contact_info}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-bottom">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>&copy; {{current_year}} {{site_name}}. All rights reserved.</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        {{auth_section}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>',
                    'css' => '.business-footer { background: #333; color: white; }
                             .business-footer .footer-main { padding: 40px 0; }
                             .business-footer .footer-bottom { background: #222; padding: 20px 0; border-top: 1px solid #444; }
                             .business-footer h5 { color: #fff; margin-bottom: 20px; }
                             .business-footer a { color: #ccc; text-decoration: none; }
                             .business-footer a:hover { color: #fff; }'
                ]
            ],
            [
                'tpl_id' => 'global-footer-creative',
                'name' => 'Creative Footer',
                'description' => 'Modern creative footer with gradient design',
                'content' => [
                    'html' => '<footer class="creative-footer">
                        <div class="footer-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="footer-title">{{site_name}}</h3>
                                        <p class="footer-subtitle">{{site_description}}</p>
                                        <div class="footer-nav">{{navigation_menu}}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="social-section">
                                            <h4>Connect With Us</h4>
                                            {{social_media}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-auth">
                            <div class="container">
                                <div class="auth-links">{{auth_section}}</div>
                            </div>
                        </div>
                    </footer>',
                    'css' => '.creative-footer { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: white; }
                             .creative-footer .footer-content { padding: 50px 0; }
                             .creative-footer .footer-title { font-size: 2.5rem; font-weight: bold; margin-bottom: 10px; }
                             .creative-footer .footer-auth { background: rgba(0,0,0,0.2); padding: 20px 0; text-align: center; }
                             .creative-footer .social-section h4 { margin-bottom: 20px; }'
                ]
            ],
            [
                'tpl_id' => 'global-footer-minimal',
                'name' => 'Minimal Footer',
                'description' => 'Clean minimal footer with essential information',
                'content' => [
                    'html' => '<footer class="minimal-footer">
                        <div class="container">
                            <div class="footer-content">
                                <div class="footer-left">
                                    <span class="site-name">{{site_name}}</span>
                                    <span class="copyright">&copy; {{current_year}} All rights reserved</span>
                                </div>
                                <div class="footer-center">
                                    {{navigation_menu}}
                                </div>
                                <div class="footer-right">
                                    {{auth_section}}
                                </div>
                            </div>
                        </div>
                    </footer>',
                    'css' => '.minimal-footer { background: #f8f9fa; border-top: 1px solid #eee; padding: 30px 0; }
                             .minimal-footer .footer-content { display: flex; justify-content: space-between; align-items: center; }
                             .minimal-footer .site-name { font-weight: 600; margin-right: 20px; }
                             .minimal-footer .copyright { color: #666; font-size: 0.9rem; }
                             .minimal-footer ul { display: flex; list-style: none; margin: 0; padding: 0; }
                             .minimal-footer li { margin: 0 15px; }'
                ]
            ],
            [
                'tpl_id' => 'global-footer-ecommerce',
                'name' => 'E-commerce Footer',
                'description' => 'Comprehensive footer for online stores',
                'content' => [
                    'html' => '<footer class="ecommerce-footer">
                        <div class="footer-main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>{{site_name}}</h5>
                                        <p>{{site_description}}</p>
                                        {{social_media}}
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Customer Service</h5>
                                        {{navigation_menu}}
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Company</h5>
                                        {{company_links}}
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Contact</h5>
                                        {{contact_info}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-bottom">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>&copy; {{current_year}} {{site_name}}. All rights reserved.</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        {{auth_section}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>',
                    'css' => '.ecommerce-footer { background: #2c3e50; color: white; }
                             .ecommerce-footer .footer-main { padding: 50px 0; }
                             .ecommerce-footer .footer-bottom { background: #1a252f; padding: 20px 0; }
                             .ecommerce-footer h5 { color: #ecf0f1; margin-bottom: 20px; font-weight: 600; }
                             .ecommerce-footer a { color: #bdc3c7; transition: color 0.3s; }
                             .ecommerce-footer a:hover { color: #3498db; }'
                ]
            ],
            [
                'tpl_id' => 'global-footer-portfolio',
                'name' => 'Portfolio Footer',
                'description' => 'Elegant footer for portfolio websites',
                'content' => [
                    'html' => '<footer class="portfolio-footer">
                        <div class="footer-content">
                            <div class="container">
                                <div class="footer-grid">
                                    <div class="footer-brand">
                                        <h3>{{site_name}}</h3>
                                        <p>{{site_tagline}}</p>
                                    </div>
                                    <div class="footer-nav">
                                        <h4>Navigation</h4>
                                        {{navigation_menu}}
                                    </div>
                                    <div class="footer-social">
                                        <h4>Follow Me</h4>
                                        {{social_media}}
                                    </div>
                                    <div class="footer-auth">
                                        <h4>Account</h4>
                                        {{auth_section}}
                                    </div>
                                </div>
                                <div class="footer-divider"></div>
                                <div class="footer-copyright">
                                    <p>&copy; {{current_year}} {{site_name}}. Crafted with passion.</p>
                                </div>
                            </div>
                        </div>
                    </footer>',
                    'css' => '.portfolio-footer { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 40px 0; }
                             .portfolio-footer .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-bottom: 30px; }
                             .portfolio-footer .footer-divider { height: 1px; background: rgba(255,255,255,0.2); margin: 30px 0; }
                             .portfolio-footer .footer-copyright { text-align: center; color: #bdc3c7; }
                             .portfolio-footer h3, .portfolio-footer h4 { margin-bottom: 15px; }'
                ]
            ]
        ];
        
        foreach ($footers as $index => $footerData) {
            TplLayout::updateOrCreate(
                ['tpl_id' => $footerData['tpl_id']],
                [
                    'layout_type' => 'footer',
                    'name' => $footerData['name'],
                    'description' => $footerData['description'],
                    'path' => 'frontend.templates.footers.global',
                    'content' => $footerData['content'],
                    'default_config' => [
                        'type' => 'global',
                        'category' => 'footer',
                        'customizable' => true,
                        'supports_navigation' => true,
                        'supports_auth' => true,
                        'supports_social' => true
                    ],
                    'configurable_fields' => [
                        'navigation_menu',
                        'auth_section',
                        'social_media',
                        'contact_info',
                        'site_name',
                        'site_description',
                        'site_tagline',
                        'company_links'
                    ],
                    'status' => true,
                    'sort_order' => $index + 1
                ]
            );
        }
    }
}
