<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\SiteConfig;
use App\Models\TplDesign;
use App\Models\TplCustomCss;
use App\Models\TplCustomScript;
use App\Models\TplLang;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        // Get the default site
        $site = Site::where('status', true)->first();
        
        if (!$site) {
            abort(404, 'Site not found');
        }
        
        // Get the page
        $page = TplPage::where('site_id', $site->id)
            ->where('slug', $slug)
            ->where('status', true)
            ->first();
            
        if (!$page) {
            abort(404, 'Page not found');
        }
        
        // Get site configuration with language info
        $defaultLang = TplLang::find($site->default_lang_id);
        $langConfigs = SiteConfig::where('site_id', $site->id)
            ->where('key', 'language_config')
            ->get()
            ->keyBy('lang_id');
        
        // Get the current language config
        $currentLangConfig = $langConfigs->get($defaultLang->id);
        $langSettings = json_decode($currentLangConfig->value ?? '{}', true);
        
        // Set language and direction
        $lang = $defaultLang->code ?? 'en';
        $dir = $langSettings['direction'] ?? 'ltr';
        
        // Get site configs
        $siteConfigs = SiteConfig::where('site_id', $site->id)->get()->keyBy('key');
        
        // Parse JSON configs
        $socialLinks = json_decode($siteConfigs->get('social_links')->value ?? '{}', true) ?: [
            'facebook' => '#',
            'twitter' => '#',
            'linkedin' => '#',
            'instagram' => '#'
        ];
        
        $contactInfo = json_decode($siteConfigs->get('contact_info')->value ?? '{}', true) ?: [
            'email' => 'info@example.com',
            'phone' => '+1 234 567 890',
            'address' => '123 Street Name, City, Country'
        ];
        
        // Get page designs
        $designs = TplDesign::where('site_id', $site->id)
            ->where('page_id', $page->id)
            ->where('lang_code', $lang)
            ->where('status', true)
            ->with(['layout', 'layout.type'])
            ->orderBy('sort_order')
            ->get();
            
        // Get nav and footer designs
        $navDesigns = $designs->where('layout.type.name', 'nav');
        $footerDesigns = $designs->where('layout.type.name', 'footer');
        
        // Get custom CSS and scripts
        $customCss = TplCustomCss::where('site_id', $site->id)->get();
        $customScripts = TplCustomScript::where('site_id', $site->id)->get();
        
        return view('frontend.layouts.app', compact(
            'page', 'designs', 'navDesigns', 'footerDesigns', 
            'lang', 'dir', 'site', 'siteConfigs', 'customCss', 'customScripts',
            'socialLinks', 'contactInfo'
        ));
    }
}
