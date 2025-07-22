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
        
        // Get current site from middleware or request
        $site = request()->get('site') ?? app('site') ?? null;
        
        if ($site) {
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            $supportedLanguages = $siteConfig ? $siteConfig->getSupportedLanguages() : ['en'];
            
            if (in_array($lang, $supportedLanguages)) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
            }
        } else {
            // Fallback to check if language exists in system
            if (TplLang::where('lang_code', $lang)->exists()) {
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
        $lang = TplLang::where('lang_code', $locale)->first();
        
        return $lang ? $lang->dir : 'ltr';
    }

    /**
     * Get language configuration for frontend
     */
    public function getLanguageConfig()
    {
        try {
            $site = request()->get('site') ?? app('site') ?? null;
            
            if (!$site) {
                return response()->json([
                    'current_language' => app()->getLocale(),
                    'direction' => 'ltr',
                    'available_languages' => [
                        [
                            'code' => 'en',
                            'name' => 'English',
                            'flag' => 'us',
                            'is_rtl' => false
                        ]
                    ],
                    'show_switcher' => false
                ]);
            }

            // Get language configuration from site
            $languageConfig = $site->getConfiguration('languages', [
                'enabled_languages' => ['en'],
                'primary_language' => 'en',
                'rtl_languages' => ['ar', 'he', 'fa'],
                'show_language_switcher' => true
            ]);

            // Get language details
            $availableLanguages = TplLang::whereIn('lang_code', $languageConfig['enabled_languages'])
                ->where('status', true)
                ->orderBy('sort_order')
                ->get(['lang_code', 'lang_name', 'flag_icon']);

            $currentLocale = app()->getLocale();
            $isRtl = in_array($currentLocale, $languageConfig['rtl_languages']);

            return response()->json([
                'current_language' => $currentLocale,
                'direction' => $isRtl ? 'rtl' : 'ltr',
                'is_rtl' => $isRtl,
                'show_switcher' => $languageConfig['show_language_switcher'],
                'available_languages' => $availableLanguages->map(function($lang) use ($languageConfig) {
                    return [
                        'code' => $lang->lang_code,
                        'name' => $lang->lang_name,
                        'flag' => $lang->flag_icon,
                        'is_rtl' => in_array($lang->lang_code, $languageConfig['rtl_languages'])
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'current_language' => 'en',
                'direction' => 'ltr',
                'available_languages' => [
                    [
                        'code' => 'en',
                        'name' => 'English',
                        'flag' => 'us',
                        'is_rtl' => false
                    ]
                ],
                'show_switcher' => false,
                'error' => app()->environment('local') ? $e->getMessage() : 'Configuration error'
            ]);
        }
    }

    /**
     * Switch language with proper direction handling
     */
    public function switchLanguage(Request $request, $languageCode)
    {
        try {
            $site = request()->get('site') ?? app('site') ?? null;
            
            if ($site) {
                $languageConfig = $site->getConfiguration('languages', [
                    'enabled_languages' => ['en'],
                    'rtl_languages' => ['ar', 'he', 'fa']
                ]);
                
                if (!in_array($languageCode, $languageConfig['enabled_languages'])) {
                    return response()->json(['error' => 'Language not available'], 400);
                }
            }

            // Verify language exists
            $language = TplLang::where('lang_code', $languageCode)->where('status', true)->first();
            if (!$language) {
                return response()->json(['error' => 'Language not found'], 404);
            }

            // Set locale
            app()->setLocale($languageCode);
            session(['locale' => $languageCode]);

            $isRtl = $site ? 
                in_array($languageCode, $site->getConfiguration('languages', [])['rtl_languages'] ?? []) :
                in_array($languageCode, ['ar', 'he', 'fa']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'language' => [
                        'code' => $languageCode,
                        'name' => $language->lang_name,
                        'direction' => $isRtl ? 'rtl' : 'ltr',
                        'is_rtl' => $isRtl
                    ]
                ]);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Language switch failed',
                    'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to switch language');
        }
    }

    /**
     * Alternative language switching method for API compatibility
     * Expects JSON body with 'language' field
     */
    public function switchLanguageAlt(Request $request)
    {
        $languageCode = $request->input('language', 'en');
        return $this->switchLanguage($request, $languageCode);
    }
}
