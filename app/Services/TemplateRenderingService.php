<?php

namespace App\Services;

use App\Models\Site;
use App\Models\TplLayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TemplateRenderingService
{
    private NavigationService $navigationService;
    private GlobalTemplateService $globalTemplateService;
    
    public function __construct(
        NavigationService $navigationService,
        GlobalTemplateService $globalTemplateService
    ) {
        $this->navigationService = $navigationService;
        $this->globalTemplateService = $globalTemplateService;
    }
    
    /**
     * Render template content with dynamic placeholders
     */
    public function renderTemplate(TplLayout $template, Site $site): string
    {
        $content = $this->getTemplateContent($template);
        
        if (empty($content)) {
            return '';
        }
        
        // Get placeholder values
        $placeholders = $this->getTemplatePlaceholders($site);
        
        // Replace placeholders in content
        return $this->replacePlaceholders($content, $placeholders);
    }
    
    /**
     * Get template content (HTML)
     */
    private function getTemplateContent(TplLayout $template): string
    {
        $content = $template->content;
        
        if (is_array($content)) {
            return $content['html'] ?? '';
        }
        
        return $content ?? '';
    }
    
    /**
     * Get template CSS
     */
    public function getTemplateCSS(TplLayout $template): string
    {
        $content = $template->content;
        
        if (is_array($content)) {
            return $content['css'] ?? '';
        }
        
        return '';
    }
    
    /**
     * Get all placeholders for template rendering
     */
    private function getTemplatePlaceholders(Site $site): array
    {
        $siteConfig = $site->config;
        $siteData = $siteConfig ? $siteConfig->data : [];
        
        return [
            // Site information
            'site_name' => $site->site_name ?? 'My Website',
            'site_description' => $siteData['description'] ?? 'Welcome to our website',
            'site_tagline' => $siteData['tagline'] ?? 'Your success is our mission',
            'home_url' => url('/'),
            'current_year' => date('Y'),
            
            // Navigation
            'navigation_menu' => $this->navigationService->renderNavigationMenu($site->id, 'header'),
            'footer_navigation_menu' => $this->navigationService->renderNavigationMenu($site->id, 'footer'),
            
            // Authentication section
            'auth_section' => $this->navigationService->renderAuthSection(),
            
            // Social media
            'social_media' => $this->navigationService->renderSocialMediaLinks($site->id),
            
            // Contact information
            'contact_info' => $this->renderContactInfo($site),
            
            // Search form
            'search_form' => $this->renderSearchForm(),
            
            // Company links
            'company_links' => $this->renderCompanyLinks($site)
        ];
    }
    
    /**
     * Replace placeholders in content
     */
    private function replacePlaceholders(string $content, array $placeholders): string
    {
        foreach ($placeholders as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
    
    /**
     * Render contact information
     */
    private function renderContactInfo(Site $site): string
    {
        $siteConfig = $site->config;
        if (!$siteConfig) {
            return '';
        }
        
        $contactData = $siteConfig->data['contact'] ?? [];
        
        if (empty($contactData)) {
            return '';
        }
        
        $html = '<div class="contact-info">';
        
        if (!empty($contactData['email'])) {
            $html .= '<div class="contact-item">
                        <i class="feather-mail"></i>
                        <a href="mailto:' . htmlspecialchars($contactData['email']) . '">
                            ' . htmlspecialchars($contactData['email']) . '
                        </a>
                      </div>';
        }
        
        if (!empty($contactData['phone'])) {
            $html .= '<div class="contact-item">
                        <i class="feather-phone"></i>
                        <a href="tel:' . htmlspecialchars($contactData['phone']) . '">
                            ' . htmlspecialchars($contactData['phone']) . '
                        </a>
                      </div>';
        }
        
        if (!empty($contactData['address'])) {
            $html .= '<div class="contact-item">
                        <i class="feather-map-pin"></i>
                        <span>' . htmlspecialchars($contactData['address']) . '</span>
                      </div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render search form
     */
    private function renderSearchForm(): string
    {
        return '<form class="search-form" method="GET" action="' . route('search') . '">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Search..." value="' . htmlspecialchars(request('q', '')) . '">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="feather-search"></i>
                        </button>
                    </div>
                </form>';
    }
    
    /**
     * Render company links
     */
    private function renderCompanyLinks(Site $site): string
    {
        $companyLinks = [
            ['title' => 'About Us', 'url' => '/about'],
            ['title' => 'Careers', 'url' => '/careers'],
            ['title' => 'Privacy Policy', 'url' => '/privacy'],
            ['title' => 'Terms of Service', 'url' => '/terms']
        ];
        
        $html = '<ul class="company-links">';
        
        foreach ($companyLinks as $link) {
            $html .= '<li><a href="' . url($link['url']) . '">' . htmlspecialchars($link['title']) . '</a></li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
    
    /**
     * Get combined CSS for a site (template CSS + custom CSS)
     */
    public function getSiteCSS(Site $site): string
    {
        $css = '';
        
        // Add header CSS
        if ($site->activeHeader) {
            $css .= $this->getTemplateCSS($site->activeHeader) . "\n\n";
        }
        
        // Add footer CSS
        if ($site->activeFooter) {
            $css .= $this->getTemplateCSS($site->activeFooter) . "\n\n";
        }
        
        // Add custom site CSS
        $siteConfig = $site->config;
        if ($siteConfig && !empty($siteConfig->tpl_colors)) {
            $css .= $this->generateCustomCSS($siteConfig->tpl_colors) . "\n\n";
        }
        
        return $css;
    }
    
    /**
     * Generate custom CSS from site colors configuration
     */
    private function generateCustomCSS(array $colors): string
    {
        if (empty($colors)) {
            return '';
        }
        
        $css = "/* Custom Site Colors */\n";
        
        if (!empty($colors['primary'])) {
            $css .= ":root { --primary-color: {$colors['primary']}; }\n";
        }
        
        if (!empty($colors['secondary'])) {
            $css .= ":root { --secondary-color: {$colors['secondary']}; }\n";
        }
        
        if (!empty($colors['accent'])) {
            $css .= ":root { --accent-color: {$colors['accent']}; }\n";
        }
        
        return $css;
    }
    
    /**
     * Cache template rendering for performance
     */
    public function getCachedRenderedTemplate(TplLayout $template, Site $site): string
    {
        $cacheKey = 'rendered_template_' . $template->id . '_' . $site->id . '_' . $site->updated_at->timestamp;
        
        return Cache::remember($cacheKey, 1800, function () use ($template, $site) {
            return $this->renderTemplate($template, $site);
        });
    }
    
    /**
     * Clear template cache for a site
     */
    public function clearTemplateCache(Site $site): void
    {
        if ($site->activeHeader) {
            Cache::forget('rendered_template_' . $site->activeHeader->id . '_' . $site->id . '_' . $site->updated_at->timestamp);
        }
        
        if ($site->activeFooter) {
            Cache::forget('rendered_template_' . $site->activeFooter->id . '_' . $site->id . '_' . $site->updated_at->timestamp);
        }
    }
}
