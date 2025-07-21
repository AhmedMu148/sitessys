<?php

namespace App\Http\Controllers;

use App\Models\SiteConfig;
use App\Models\TplLang;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Set the application locale
     */
    public function setLocale(Request $request)
    {
        $lang = $request->query('lang', 'en');
        
        // Get current site from middleware
        $site = app('site');
        
        if ($site) {
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            $supportedLanguages = $siteConfig ? $siteConfig->getSupportedLanguages() : ['en'];
            
            if (in_array($lang, $supportedLanguages)) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
            }
        } else {
            // Fallback to check if language exists in system
            if (TplLang::where('code', $lang)->exists()) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
            }
        }
        
        return redirect()->back();
    }

    /**
     * Get current language direction (LTR/RTL)
     */
    public function getCurrentDirection()
    {
        $locale = app()->getLocale();
        $lang = TplLang::where('code', $locale)->first();
        
        return $lang ? $lang->dir : 'ltr';
    }

    /**
     * Get available languages for current site
     */
    public function getAvailableLanguages()
    {
        $site = app('site');
        
        if (!$site) {
            return TplLang::all();
        }
        
        $siteConfig = SiteConfig::where('site_id', $site->id)->first();
        $supportedLanguages = $siteConfig ? $siteConfig->getSupportedLanguages() : ['en'];
        
        return TplLang::whereIn('code', $supportedLanguages)->get();
    }
}
