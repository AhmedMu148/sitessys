<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLang;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Display language management interface
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Get all available languages
        $availableLanguages = TplLang::orderBy('sort_order')->get();
        
        // Get current language configuration
        $languageConfig = $site->getConfiguration('languages', [
            'enabled_languages' => ['en'],
            'primary_language' => 'en',
            'rtl_languages' => ['ar', 'he', 'fa'],
            'auto_detect' => false,
            'fallback_language' => 'en',
            'show_language_switcher' => true,
            'switcher_position' => 'header'
        ]);

        return view('admin.languages.index', compact(
            'site', 
            'availableLanguages', 
            'languageConfig'
        ));
    }

    /**
     * Toggle language status (enable/disable)
     */
    public function toggleLanguage(Request $request, $languageCode)
    {
        $request->validate([
            'enabled' => 'required|boolean'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Verify language exists
        $language = TplLang::where('lang_code', $languageCode)->first();
        if (!$language) {
            return response()->json(['error' => 'Language not found.'], 404);
        }

        // Get current language configuration
        $languageConfig = $site->getConfiguration('languages', [
            'enabled_languages' => ['en'],
            'primary_language' => 'en'
        ]);

        $enabledLanguages = $languageConfig['enabled_languages'];

        if ($request->boolean('enabled')) {
            // Enable language
            if (!in_array($languageCode, $enabledLanguages)) {
                $enabledLanguages[] = $languageCode;
            }
        } else {
            // Disable language (but not if it's the primary language)
            if ($languageCode === $languageConfig['primary_language']) {
                return response()->json([
                    'error' => 'Cannot disable the primary language. Please set a different primary language first.'
                ], 400);
            }
            
            $enabledLanguages = array_filter($enabledLanguages, function($lang) use ($languageCode) {
                return $lang !== $languageCode;
            });
        }

        // Update configuration
        $languageConfig['enabled_languages'] = array_values($enabledLanguages);
        $languageConfig['updated_at'] = now()->toISOString();
        $site->setConfiguration('languages', $languageConfig);

        return response()->json([
            'success' => true,
            'message' => $request->boolean('enabled') ? 'Language enabled successfully.' : 'Language disabled successfully.',
            'language' => [
                'code' => $languageCode,
                'name' => $language->lang_name,
                'enabled' => $request->boolean('enabled')
            ],
            'configuration' => $languageConfig
        ]);
    }

    /**
     * Set primary language and direction
     */
    public function setPrimaryLanguage(Request $request)
    {
        $request->validate([
            'language_code' => 'required|string|size:2',
            'direction' => 'required|in:ltr,rtl'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Verify language exists
        $language = TplLang::where('lang_code', $request->language_code)->first();
        if (!$language) {
            return response()->json(['error' => 'Language not found.'], 404);
        }

        // Get current language configuration
        $languageConfig = $site->getConfiguration('languages', [
            'enabled_languages' => ['en'],
            'primary_language' => 'en',
            'rtl_languages' => ['ar', 'he', 'fa']
        ]);

        // Ensure the language is enabled
        if (!in_array($request->language_code, $languageConfig['enabled_languages'])) {
            $languageConfig['enabled_languages'][] = $request->language_code;
        }

        // Update primary language and direction
        $languageConfig['primary_language'] = $request->language_code;
        $languageConfig['primary_direction'] = $request->direction;
        
        // Update RTL languages list if needed
        $rtlLanguages = $languageConfig['rtl_languages'];
        if ($request->direction === 'rtl' && !in_array($request->language_code, $rtlLanguages)) {
            $rtlLanguages[] = $request->language_code;
        } elseif ($request->direction === 'ltr') {
            $rtlLanguages = array_filter($rtlLanguages, function($lang) use ($request) {
                return $lang !== $request->language_code;
            });
        }
        $languageConfig['rtl_languages'] = array_values($rtlLanguages);
        $languageConfig['updated_at'] = now()->toISOString();

        // Save configuration
        $site->setConfiguration('languages', $languageConfig);

        return response()->json([
            'success' => true,
            'message' => 'Primary language updated successfully.',
            'configuration' => $languageConfig,
            'language' => [
                'code' => $request->language_code,
                'name' => $language->lang_name,
                'direction' => $request->direction,
                'is_rtl' => $request->direction === 'rtl'
            ]
        ]);
    }

    /**
     * Update language switcher settings
     */
    public function updateSwitcherSettings(Request $request)
    {
        $request->validate([
            'show_language_switcher' => 'required|boolean',
            'switcher_position' => 'required|in:header,footer,both',
            'auto_detect' => 'boolean',
            'fallback_language' => 'required|string|size:2'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Get current language configuration
        $languageConfig = $site->getConfiguration('languages', []);

        // Update switcher settings
        $languageConfig = array_merge($languageConfig, [
            'show_language_switcher' => $request->boolean('show_language_switcher'),
            'switcher_position' => $request->switcher_position,
            'auto_detect' => $request->boolean('auto_detect', false),
            'fallback_language' => $request->fallback_language,
            'updated_at' => now()->toISOString()
        ]);

        // Save configuration
        $site->setConfiguration('languages', $languageConfig);

        return response()->json([
            'success' => true,
            'message' => 'Language switcher settings updated successfully.',
            'configuration' => $languageConfig
        ]);
    }

    /**
     * Get language configuration for frontend
     */
    public function getLanguageConfig()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $languageConfig = $site->getConfiguration('languages', [
            'enabled_languages' => ['en'],
            'primary_language' => 'en',
            'rtl_languages' => ['ar', 'he', 'fa'],
            'auto_detect' => false,
            'fallback_language' => 'en',
            'show_language_switcher' => true,
            'switcher_position' => 'header'
        ]);

        // Get language details
        $enabledLanguageDetails = TplLang::whereIn('lang_code', $languageConfig['enabled_languages'])
            ->orderBy('sort_order')
            ->get(['lang_code', 'lang_name', 'flag_icon', 'status']);

        return response()->json([
            'success' => true,
            'configuration' => $languageConfig,
            'languages' => $enabledLanguageDetails->map(function($lang) use ($languageConfig) {
                return [
                    'code' => $lang->lang_code,
                    'name' => $lang->lang_name,
                    'flag' => $lang->flag_icon,
                    'is_primary' => $lang->lang_code === $languageConfig['primary_language'],
                    'is_rtl' => in_array($lang->lang_code, $languageConfig['rtl_languages']),
                    'direction' => in_array($lang->lang_code, $languageConfig['rtl_languages']) ? 'rtl' : 'ltr'
                ];
            })
        ]);
    }

    /**
     * Switch application language
     */
    public function switchLanguage(Request $request, $languageCode)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Get language configuration
        $languageConfig = $site->getConfiguration('languages', [
            'enabled_languages' => ['en']
        ]);

        // Check if language is enabled
        if (!in_array($languageCode, $languageConfig['enabled_languages'])) {
            return response()->json(['error' => 'Language not available.'], 400);
        }

        // Get language details
        $language = TplLang::where('lang_code', $languageCode)->first();
        if (!$language) {
            return response()->json(['error' => 'Language not found.'], 404);
        }

        // Set application locale
        App::setLocale($languageCode);
        
        // Store language preference in session
        session(['app_locale' => $languageCode]);

        $isRtl = in_array($languageCode, $languageConfig['rtl_languages']);

        return response()->json([
            'success' => true,
            'message' => 'Language switched successfully.',
            'language' => [
                'code' => $languageCode,
                'name' => $language->lang_name,
                'direction' => $isRtl ? 'rtl' : 'ltr',
                'is_rtl' => $isRtl
            ]
        ]);
    }

    /**
     * Reset language settings to defaults
     */
    public function resetToDefaults()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $defaultConfig = [
            'enabled_languages' => ['en'],
            'primary_language' => 'en',
            'primary_direction' => 'ltr',
            'rtl_languages' => ['ar', 'he', 'fa'],
            'auto_detect' => false,
            'fallback_language' => 'en',
            'show_language_switcher' => true,
            'switcher_position' => 'header',
            'updated_at' => now()->toISOString()
        ];

        $site->setConfiguration('languages', $defaultConfig);

        return response()->json([
            'success' => true,
            'message' => 'Language settings reset to defaults successfully.',
            'configuration' => $defaultConfig
        ]);
    }

    /**
     * Get available languages for selection
     */
    public function getAvailableLanguages()
    {
        $languages = TplLang::where('status', true)
            ->orderBy('sort_order')
            ->get(['lang_code', 'lang_name', 'flag_icon']);

        return response()->json([
            'success' => true,
            'languages' => $languages
        ]);
    }
}
