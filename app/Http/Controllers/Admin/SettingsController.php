<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLang;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }
    /**
     * Display settings dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        $languages = TplLang::all();
        
        return view('admin.settings.index', compact('site', 'config', 'languages'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'url' => 'nullable|url',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site basic info
        $site->update([
            'site_name' => $request->site_name,
            'url' => $request->url
        ]);

        // Update site config
        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $settings = $request->input('settings', []);
        $data = $request->input('data', []);
        
        $config->update([
            'settings' => $settings,
            'data' => $data,
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Show language settings
     */
    public function languageEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create([
                'site_id' => $site->id,
                'settings' => ['timezone' => 'UTC'],
                'tpl_name' => 'business',
                'language_code' => ['languages' => ['en'], 'primary' => 'en']
            ]);
        }

        $languages = TplLang::all();

        return view('admin.settings.languages', compact('site', 'config', 'languages'));
    }

    /**
     * Update language settings
     */
    public function languageUpdate(Request $request)
    {
        $request->validate([
            'languages' => 'required|array|min:1',
            'languages.*' => 'exists:tpl_langs,code',
            'primary_language' => 'required|string|in:' . implode(',', $request->input('languages', ['en']))
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'language_code' => [
                'languages' => $request->input('languages'),
                'primary' => $request->input('primary_language')
            ]
        ]);

        return redirect()->back()->with('success', 'Language settings updated successfully.');
    }

    /**
     * Show general settings
     */
    public function generalEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create([
                'site_id' => $site->id,
                'settings' => ['timezone' => 'UTC'],
                'tpl_name' => 'business'
            ]);
        }

        return view('admin.settings.general', compact('site', 'config'));
    }

    /**
     * Update general settings
     */
    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'url' => 'nullable|url',
            'tpl_name' => 'required|string|max:50',
            'settings.timezone' => 'required|string',
            'data.logo' => 'nullable|string',
            'data.description' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site basic info
        $site->update([
            'site_name' => $request->site_name,
            'url' => $request->url
        ]);

        // Update site config
        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'tpl_name' => $request->tpl_name,
            'settings' => $request->input('settings', ['timezone' => 'UTC']),
            'data' => $request->input('data', [])
        ]);

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update language settings using ConfigurationService
     */
    public function updateLanguages(Request $request)
    {
        $request->validate([
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|string|size:2',
            'primary_language' => 'required|string|size:2',
            'rtl_languages' => 'nullable|array',
            'rtl_languages.*' => 'string|size:2',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Validate that primary language is in languages array
        if (!in_array($request->primary_language, $request->languages)) {
            return response()->json(['error' => 'Primary language must be one of the selected languages.'], 422);
        }

        $success = $site->setConfiguration('language', [
            'languages' => $request->languages,
            'primary_language' => $request->primary_language,
            'rtl_languages' => $request->rtl_languages ?? ['ar']
        ]);

        if ($success) {
            return response()->json(['message' => 'Language settings updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update language settings.'], 500);
    }

    /**
     * Update media settings using ConfigurationService
     */
    public function updateMedia(Request $request)
    {
        $request->validate([
            'max_file_size' => 'nullable|integer|min:1|max:10240', // KB
            'allowed_types' => 'nullable|array',
            'allowed_types.*' => 'string',
            'image_quality' => 'nullable|integer|min:1|max:100',
            'thumbnail_sizes' => 'nullable|array',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('media', $request->all());

        if ($success) {
            return response()->json(['message' => 'Media settings updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update media settings.'], 500);
    }

    /**
     * Update tenant settings using ConfigurationService
     */
    public function updateTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|string|max:100',
            'domain' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('tenant', $request->all());

        if ($success) {
            return response()->json(['message' => 'Tenant settings updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update tenant settings.'], 500);
    }

    /**
     * Get all available languages
     */
    public function getLanguages()
    {
        $languages = TplLang::all();
        return response()->json(['languages' => $languages]);
    }

    /**
     * Get current language configuration
     */
    public function getLanguageConfig()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $config = $site->getConfiguration('language');
        return response()->json(['configuration' => $config]);
    }

    /**
     * Get all settings configurations
     */
    public function getAllConfigurations()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $configurations = $site->getAllConfigurations();
        return response()->json(['configurations' => $configurations]);
    }

    /**
     * Reset configurations to defaults
     */
    public function resetToDefaults(Request $request)
    {
        $request->validate([
            'types' => 'nullable|array',
            'types.*' => 'string|in:theme,language,navigation,colors,sections,media,tenant'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $types = $request->types ?? ['theme', 'language', 'navigation', 'colors', 'sections', 'media', 'tenant'];
        $success = true;

        foreach ($types as $type) {
            $defaults = $this->configService->getDefaults($type);
            if (!empty($defaults)) {
                $result = $site->setConfiguration($type, $defaults);
                if (!$result) {
                    $success = false;
                }
            }
        }

        if ($success) {
            return response()->json(['message' => 'Configurations reset to defaults successfully.']);
        }

        return response()->json(['error' => 'Failed to reset some configurations.'], 500);
    }

    /**
     * Validate configuration data
     */
    public function validateConfiguration(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'data' => 'required|array'
        ]);

        $isValid = $this->configService->validate($request->type, $request->data);

        return response()->json(['valid' => $isValid]);
    }

    /**
     * Get configuration schema for a type
     */
    public function getConfigurationSchema(string $type)
    {
        $schemas = [
            'theme' => [
                'theme' => 'required|string|max:50',
                'header_theme' => 'nullable|string|max:50',
                'footer_theme' => 'nullable|string|max:50',
            ],
            'language' => [
                'languages' => 'required|array|min:1',
                'primary_language' => 'required|string|size:2',
                'rtl_languages' => 'nullable|array',
            ],
            'navigation' => [
                'header.theme' => 'required|string|max:50',
                'header.links' => 'required|array|max:5',
                'footer.theme' => 'required|string|max:50',
                'footer.links' => 'required|array|max:10',
            ],
            'colors' => [
                'primary' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
                'secondary' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            ],
            'media' => [
                'max_file_size' => 'nullable|integer|min:1|max:10240',
                'allowed_types' => 'nullable|array',
                'image_quality' => 'nullable|integer|min:1|max:100',
            ],
            'tenant' => [
                'tenant_id' => 'required|string|max:100',
                'domain' => 'nullable|string|max:255',
            ]
        ];

        return response()->json(['schema' => $schemas[$type] ?? []]);
    }
}
