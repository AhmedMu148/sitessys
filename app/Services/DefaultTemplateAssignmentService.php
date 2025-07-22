<?php

namespace App\Services;

use App\Models\User;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Log;

class DefaultTemplateAssignmentService
{
    protected ConfigurationService $configService;
    
    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }
    
    /**
     * Assign default SEO templates to a new admin user
     */
    public function assignDefaultTemplates(User $user, array $domains = [], array $subdomains = []): Site
    {
        Log::info('Assigning default templates to new admin user', ['user_id' => $user->id, 'email' => $user->email]);
        
        // Create site for the user
        $site = Site::create([
            'user_id' => $user->id,
            'site_name' => $this->generateSiteName($user),
            'url' => $this->generateDefaultUrl($user),
            'status_id' => true,
            'active_header_id' => $this->getDefaultHeaderId(),
            'active_footer_id' => $this->getDefaultFooterId()
        ]);
        
        // Set development domains if provided, otherwise use defaults
        if (empty($domains)) {
            $domains = $this->getDefaultDomains();
        }
        if (empty($subdomains)) {
            $subdomains = $this->getDefaultSubdomains($user);
        }
        
        $site->setDomainData($domains, $subdomains);
        
        // Initialize site configuration
        $this->configService->initializeDefaults($site->id);
        
        // Create default pages with sections
        $this->createDefaultPagesForSite($site);
        
        Log::info('Default templates assigned successfully', [
            'user_id' => $user->id,
            'site_id' => $site->id,
            'site_name' => $site->site_name
        ]);
        
        return $site;
    }
    
    /**
     * Get default header template ID
     */
    protected function getDefaultHeaderId(): ?int
    {
        $header = TplLayout::where('tpl_id', 'default-seo-header')
            ->where('layout_type', 'header')
            ->where('status', true)
            ->first();
            
        return $header?->id;
    }
    
    /**
     * Get default footer template ID
     */
    protected function getDefaultFooterId(): ?int
    {
        $footer = TplLayout::where('tpl_id', 'default-seo-footer')
            ->where('layout_type', 'footer')
            ->where('status', true)
            ->first();
            
        return $footer?->id;
    }
    
    /**
     * Generate site name for user
     */
    protected function generateSiteName(User $user): string
    {
        $name = $user->name ?: 'SEO Business';
        return trim(str_replace(['@', '.com', '.net', '.org'], '', $name)) . ' SEO';
    }
    
    /**
     * Generate default URL for user
     */
    protected function generateDefaultUrl(User $user): string
    {
        // For development, use localhost
        return 'http://localhost:8000';
    }
    
    /**
     * Get default domains for development
     */
    protected function getDefaultDomains(): array
    {
        return [
            'localhost',
            '127.0.0.1:8000',
            'phplaravel-1399496-5687062.cloudwaysapps.com'
        ];
    }
    
    /**
     * Get default subdomains for user
     */
    protected function getDefaultSubdomains(User $user): array
    {
        $userSlug = strtolower(str_replace(['@', '.', ' '], '', explode('@', $user->email)[0]));
        return [
            $userSlug,
            'admin',
            'dev'
        ];
    }
    
    /**
     * Create default pages with sections for a site
     */
    protected function createDefaultPagesForSite(Site $site): void
    {
        $defaultPages = [
            [
                'name' => 'Home',
                'slug' => 'home',
                'link' => '/',
                'sections' => ['default-seo-hero', 'default-seo-services', 'default-seo-about'],
                'show_in_nav' => true
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'link' => '/about',
                'sections' => ['default-seo-about'],
                'show_in_nav' => true
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'link' => '/services',
                'sections' => ['default-seo-services'],
                'show_in_nav' => true
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'link' => '/contact',
                'sections' => ['default-seo-contact'],
                'show_in_nav' => true
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'link' => '/blog',
                'sections' => [],
                'show_in_nav' => true
            ]
        ];
        
        foreach ($defaultPages as $pageData) {
            $page = TplPage::create([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'link' => $pageData['link'],
                'slug' => $pageData['slug'],
                'data' => [
                    'en' => [
                        'title' => $pageData['name'],
                        'description' => $pageData['name'] . ' page for ' . $site->site_name
                    ]
                ],
                'show_in_nav' => $pageData['show_in_nav'],
                'status' => true
            ]);
            
            // Add sections to page
            $this->addSectionsToPage($page, $pageData['sections'], $site->id);
            
            Log::info('Created default page', [
                'page_name' => $page->name,
                'site_id' => $site->id,
                'sections_count' => count($pageData['sections'])
            ]);
        }
    }
    
    /**
     * Add sections to a page
     */
    protected function addSectionsToPage(TplPage $page, array $templateIds, int $siteId): void
    {
        $sortOrder = 1;
        
        foreach ($templateIds as $templateId) {
            $template = TplLayout::where('tpl_id', $templateId)
                ->where('layout_type', 'section')
                ->where('status', true)
                ->first();
                
            if ($template) {
                TplPageSection::create([
                    'page_id' => $page->id,
                    'tpl_layouts_id' => $template->id,
                    'site_id' => $siteId,
                    'name' => $template->name . ' on ' . $page->name,
                    'content' => $template->default_config ?: '{}',
                    'status' => 1,
                    'sort_order' => $sortOrder++
                ]);
            }
        }
    }
    
    /**
     * Update default templates (useful for maintenance)
     */
    public function updateDefaultTemplates(): bool
    {
        try {
            // Get all default template IDs
            $defaultTemplateIds = [
                'default-seo-header',
                'default-seo-footer',
                'default-seo-hero',
                'default-seo-services',
                'default-seo-about',
                'default-seo-contact'
            ];
            
            // Ensure all default templates exist and are active
            foreach ($defaultTemplateIds as $templateId) {
                $template = TplLayout::where('tpl_id', $templateId)->first();
                if ($template && !$template->status) {
                    $template->update(['status' => true]);
                    Log::info('Activated default template', ['template_id' => $templateId]);
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to update default templates', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get available default templates
     */
    public function getDefaultTemplates(): array
    {
        return TplLayout::whereIn('tpl_id', [
            'default-seo-header',
            'default-seo-footer',
            'default-seo-hero',
            'default-seo-services',
            'default-seo-about',
            'default-seo-contact'
        ])
        ->where('status', true)
        ->get()
        ->groupBy('layout_type')
        ->toArray();
    }
    
    /**
     * Create custom site with specific templates
     */
    public function createCustomSite(User $user, array $options = []): Site
    {
        $siteName = $options['site_name'] ?? $this->generateSiteName($user);
        $domains = $options['domains'] ?? $this->getDefaultDomains();
        $subdomains = $options['subdomains'] ?? $this->getDefaultSubdomains($user);
        $templates = $options['templates'] ?? [];
        
        $site = Site::create([
            'user_id' => $user->id,
            'site_name' => $siteName,
            'url' => $options['url'] ?? $this->generateDefaultUrl($user),
            'status_id' => true,
            'active_header_id' => $templates['header_id'] ?? $this->getDefaultHeaderId(),
            'active_footer_id' => $templates['footer_id'] ?? $this->getDefaultFooterId()
        ]);
        
        $site->setDomainData($domains, $subdomains);
        $this->configService->initializeDefaults($site->id);
        
        // Create custom pages if specified, otherwise use defaults
        if (!empty($options['pages'])) {
            $this->createCustomPages($site, $options['pages']);
        } else {
            $this->createDefaultPagesForSite($site);
        }
        
        return $site;
    }
    
    /**
     * Create custom pages for a site
     */
    protected function createCustomPages(Site $site, array $pagesConfig): void
    {
        foreach ($pagesConfig as $pageData) {
            $page = TplPage::create([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'link' => $pageData['link'],
                'slug' => $pageData['slug'],
                'data' => $pageData['data'] ?? [
                    'en' => [
                        'title' => $pageData['name'],
                        'description' => $pageData['name'] . ' page'
                    ]
                ],
                'show_in_nav' => $pageData['show_in_nav'] ?? true,
                'status' => $pageData['status'] ?? true
            ]);
            
            if (!empty($pageData['sections'])) {
                $this->addSectionsToPage($page, $pageData['sections'], $site->id);
            }
        }
    }
}
