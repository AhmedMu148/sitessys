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
        
        // Get site configuration
        $siteConfig = SiteConfig::where('site_id', $site->id)
            ->where('is_default', true)
            ->with('language')
            ->first();
            
        if (!$siteConfig) {
            $siteConfig = SiteConfig::where('site_id', $site->id)
                ->with('language')
                ->first();
        }
        
        $lang = $siteConfig ? $siteConfig->language->code : 'en';
        $dir = $siteConfig ? $siteConfig->direction : 'ltr';
        
        // Get page designs
        $designs = TplDesign::where('site_id', $site->id)
            ->where('page_id', $page->id)
            ->where('lang_code', $lang)
            ->where('status', true)
            ->with(['layout', 'layoutType'])
            ->orderBy('sort_order')
            ->get();
            
        // Get nav and footer designs
        $navDesigns = $designs->where('layoutType.name', 'nav');
        $footerDesigns = $designs->where('layoutType.name', 'footer');
        
        // Get custom CSS and scripts
        $customCss = TplCustomCss::where('site_id', $site->id)->get();
        $customScripts = TplCustomScript::where('site_id', $site->id)->get();
        
        return view('frontend.layouts.app', compact(
            'page', 'designs', 'navDesigns', 'footerDesigns', 
            'lang', 'dir', 'site', 'customCss', 'customScripts'
        ));
    }
}
