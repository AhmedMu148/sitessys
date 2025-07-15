<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\SiteConfig;
use App\Models\PageSection;
use App\Models\TplSite;
use App\Models\TplLayout;
use App\Models\TplLang;
use Illuminate\Support\Facades\Log;
use Exception;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        try {
            // Check if we have a tenant user from the middleware
            $tenantUser = request()->attributes->get('tenant_user');
            
            if ($tenantUser) {
                // Use tenant's site
                $site = $tenantUser->sites()->where('status', true)->first();
            } else {
                // Fallback: get the first admin's site (for main domain)
                $user = \App\Models\User::where('email', 'admin@example.com')->first();
                if (!$user) {
                    // If admin@example.com doesn't exist, get any admin
                    $user = \App\Models\User::whereHas('roles', function($query) {
                        $query->whereIn('name', ['super-admin', 'admin']);
                    })->first();
                }
                $site = $user ? $user->sites()->where('status', true)->first() : null;
            }
            
            if (!$site) {
                // Create a simple welcome page if no site is found
                return $this->showWelcomePage();
            }
            
            // Convert slug to link format
            $link = $slug === 'home' ? '/' : '/' . $slug;
            
            // Get the page by link
            $page = TplPage::where('site_id', $site->id)
                ->where('link', $link)
                ->first();
                
            if (!$page) {
                // If specific page not found, try to get home page
                $page = TplPage::where('site_id', $site->id)
                    ->where('link', '/')
                    ->first();
                    
                if (!$page) {
                    // Create a simple page if no pages are found
                    return $this->showSimplePage($slug, $site);
                }
            }
            
            // Get site configuration
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            $config = $siteConfig ? $siteConfig->data : [];
        
            // Get language settings
            $lang = 'en'; // Default to English
            $dir = 'ltr';  // Default to LTR
            
            // Get site template configuration
            $tplSite = TplSite::where('site_id', $site->id)->first();
            
            // Get navigation layout (active only)
            $navLayout = null;
            if ($tplSite && $tplSite->nav) {
                $navLayout = TplLayout::where('id', $tplSite->nav)->where('status', true)->first();
            }
            
            // Get footer layout (active only)
            $footerLayout = null;
            if ($tplSite && $tplSite->footer) {
                $footerLayout = TplLayout::where('id', $tplSite->footer)->where('status', true)->first();
            }
            
            // Get page sections with their layouts
            $sections = PageSection::where('page_id', $page->id)
                ->where('is_active', true)
                ->with('layout')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($section) use ($lang) {
                    // Parse JSON content_data
                    try {
                        $contentData = json_decode($section->content_data, true);
                        $settings = json_decode($section->settings, true);
                        
                        $section->parsed_content = [
                            'title' => $contentData['title'] ?? $section->name,
                            'content' => $contentData['content'] ?? '',
                            'button_text' => $contentData['button_text'] ?? '',
                        ];
                        
                        $section->parsed_settings = $settings ?? [];
                    } catch (Exception $e) {
                        // Fallback for parsing errors
                        $section->parsed_content = [
                            'title' => $section->name,
                            'content' => '',
                            'button_text' => '',
                        ];
                        $section->parsed_settings = [];
                    }
                    
                    return $section;
                });
            
            return view('frontend.layouts.app', compact(
                'site', 
                'page', 
                'config', 
                'lang', 
                'dir', 
                'navLayout', 
                'footerLayout', 
                'sections'
            ));
            
        } catch (Exception $e) {
            // Log the error and show a simple welcome page
            Log::error('Frontend page error: ' . $e->getMessage());
            return $this->showWelcomePage();
        }
    }
    
    /**
     * Show a simple welcome page when no site is configured
     */
    private function showWelcomePage()
    {
        return view('frontend.welcome');
    }
    
    /**
     * Show a simple page when site exists but no specific page is found
     */
    private function showSimplePage($slug, $site)
    {
        return view('frontend.simple', compact('slug', 'site'));
    }
}
