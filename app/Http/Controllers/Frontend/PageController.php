<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\SiteConfig;
use App\Models\TplSection;
use App\Models\TplSite;
use App\Models\TplLayout;
use App\Models\TplLang;
use Exception;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        // For now, get the first admin's site
        // Later this can be based on domain or subdomain
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : Site::where('status', true)->first();
        
        if (!$site) {
            abort(404, 'Site not found');
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
                abort(404, 'Page not found');
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
        
        // Get navigation layout
        $navLayout = null;
        if ($tplSite && $tplSite->nav) {
            $navLayout = TplLayout::find($tplSite->nav);
        }
        
        // Get footer layout
        $footerLayout = null;
        if ($tplSite && $tplSite->footer) {
            $footerLayout = TplLayout::find($tplSite->footer);
        }
        
        // Get page sections
        $sectionIds = explode(',', $page->section_id);
        $sections = TplSection::where('site_id', $site->id)
            ->whereIn('id', $sectionIds)
            ->orderBy('position')
            ->get()
            ->map(function ($section) use ($lang) {
                // Parse JSON content
                try {
                    $contentData = json_decode($section->content, true);
                    $langData = $contentData[$lang] ?? $contentData['en'] ?? [];
                    
                    $section->parsed_content = [
                        'title' => $langData['title'] ?? $section->name,
                        'content' => $langData['content'] ?? '',
                        'button_text' => $langData['button_text'] ?? '',
                    ];
                } catch (Exception $e) {
                    // Fallback for old content format
                    $section->parsed_content = [
                        'title' => $section->name,
                        'content' => $section->content,
                        'button_text' => '',
                    ];
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
    }
}
