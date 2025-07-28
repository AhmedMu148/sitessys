<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\User;
use App\Models\TplSite;
use App\Models\TplLayout;
use App\Models\SiteConfig;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    protected ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }
    /**
     * Display a listing of templates
     */
    public function index()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Get templates by type - same as page edit functionality
        $headerTemplates = TplLayout::where('layout_type', 'header')
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
        $sectionTemplates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
        $footerTemplates = TplLayout::where('layout_type', 'footer')
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get all layouts for backward compatibility
        $layouts = TplLayout::where('status', true)
            ->orderBy('layout_type')
            ->orderBy('sort_order')
            ->paginate(10);
            
        return view('admin.templates.index', compact('layouts', 'site', 'headerTemplates', 'sectionTemplates', 'footerTemplates'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        return view('admin.templates.create', compact('site'));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:header,footer,section',
            'description' => 'nullable|string',
            'language' => 'nullable|string|in:en,ar',
            'direction' => 'nullable|string|in:ltr,rtl',
        ]);

        $template = TplLayout::create([
            'tpl_id' => strtolower(str_replace(' ', '_', $request->name)) . '_' . time(),
            'layout_type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'content' => [
                'html' => '<div class="template-content"><!-- Template content goes here --></div>',
                'language' => $request->language ?? 'en',
                'direction' => $request->direction ?? 'ltr'
            ],
            'status' => $request->has('is_active') ? true : false,
            'sort_order' => TplLayout::where('layout_type', $request->type)->count() + 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Template created successfully.',
                'template' => $template
            ]);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified template
     */
    public function show(TplLayout $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(TplLayout $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, TplLayout $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'layout_type' => 'required|in:header,footer,section',
            'content' => 'required|string',
        ]);

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => ['html' => $request->input('content', '')],
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified template
     */
    public function destroy(TplLayout $template)
    {
        $template->delete();
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Show navigation settings
     */
    public function navEdit()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $headers = TplLayout::where('layout_type', 'header')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        return view('admin.templates.nav', compact('site', 'headers', 'tplSite'));
    }

    /**
     * Update navigation settings
     */
    public function navUpdate(Request $request)
    {
        $request->validate([
            'active_header_id' => 'nullable|exists:tpl_layouts,id',
            'links' => 'array|max:3',
            'links.*.url' => 'required_with:links.*.label|string',
            'links.*.label' => 'required_with:links.*.url|string'
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site active header
        if ($request->active_header_id) {
            $site->update(['active_header_id' => $request->active_header_id]);
        }

        // Update navigation links
        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        $links = array_filter($request->input('links', []), function($link) {
            return !empty($link['url']) && !empty($link['label']);
        });

        $tplSite->update([
            'nav_data' => ['links' => array_values($links)]
        ]);

        return redirect()->back()->with('success', 'Navigation settings updated successfully.');
    }

    /**
     * Show footer settings
     */
    public function footerEdit()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $footers = TplLayout::where('layout_type', 'footer')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        return view('admin.templates.footer', compact('site', 'footers', 'tplSite'));
    }

    /**
     * Update footer settings
     */
    public function footerUpdate(Request $request)
    {
        $request->validate([
            'active_footer_id' => 'nullable|exists:tpl_layouts,id',
            'links' => 'array|max:10',
            'links.*.url' => 'required_with:links.*.label|string',
            'links.*.label' => 'required_with:links.*.url|string'
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site active footer
        if ($request->active_footer_id) {
            $site->update(['active_footer_id' => $request->active_footer_id]);
        }

        // Update footer links
        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        $links = array_filter($request->input('links', []), function($link) {
            return !empty($link['url']) && !empty($link['label']);
        });

        $tplSite->update([
            'footer_data' => ['links' => array_values($links)]
        ]);

        return redirect()->back()->with('success', 'Footer settings updated successfully.');
    }

    /**
     * Show color settings
     */
    public function colorsEdit()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
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

        return view('admin.templates.colors', compact('site', 'config'));
    }

    /**
     * Update color settings
     */
    public function colorsUpdate(Request $request)
    {
        $request->validate([
            'colors' => 'array',
            'colors.nav.background' => 'nullable|string',
            'colors.nav.text' => 'nullable|string',
            'colors.hero.background' => 'nullable|string',
            'colors.hero.text' => 'nullable|string',
            'colors.footer.background' => 'nullable|string',
            'colors.footer.text' => 'nullable|string',
            'colors.button.primary' => 'nullable|string',
            'colors.button.secondary' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'tpl_colors' => $request->input('colors', [])
        ]);

        return redirect()->back()->with('success', 'Color settings updated successfully.');
    }

    /**
     * Get site configuration dashboard
     */
    public function configuration()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $configurations = $site->getAllConfigurations();
        
        return view('admin.templates.configuration', compact('site', 'configurations'));
    }

    /**
     * Update theme configuration
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|max:50',
            'header_theme' => 'nullable|string|max:50',
            'footer_theme' => 'nullable|string|max:50',
            'page_themes' => 'nullable|array',
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('theme', $request->only([
            'theme', 'header_theme', 'footer_theme', 'page_themes'
        ]));

        if ($success) {
            return response()->json(['message' => 'Theme configuration updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update theme configuration.'], 500);
    }

    /**
     * Update navigation configuration
     */
    public function updateNavigation(Request $request)
    {
        $request->validate([
            'header' => 'required|array',
            'header.theme' => 'required|string|max:50',
            'header.links' => 'required|array|max:5',
            'header.links.*.url' => 'required|string|max:255',
            'header.links.*.label' => 'required|string|max:100',
            'footer' => 'required|array',
            'footer.theme' => 'required|string|max:50',
            'footer.links' => 'required|array|max:10',
            'footer.links.*.url' => 'required|string|max:255',
            'footer.links.*.label' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('navigation', $request->only([
            'header', 'footer'
        ]));

        if ($success) {
            return response()->json(['message' => 'Navigation configuration updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update navigation configuration.'], 500);
    }

    /**
     * Update colors configuration via ConfigurationService
     */
    public function updateColorsConfig(Request $request)
    {
        $request->validate([
            'primary' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'nav' => 'nullable|array',
            'nav.background' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'nav.text' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'footer' => 'nullable|array',
            'footer.background' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'footer.text' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('colors', $request->all());

        if ($success) {
            return response()->json(['message' => 'Colors configuration updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update colors configuration.'], 500);
    }

    /**
     * Update sections configuration
     */
    public function updateSections(Request $request)
    {
        $request->validate([
            'active_sections' => 'required|array',
            'active_sections.*.section_id' => 'required|string|max:50',
            'active_sections.*.is_active' => 'required|boolean',
            'active_sections.*.sort_order' => 'required|integer|min:0',
            'section_content' => 'nullable|array',
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->setConfiguration('sections', $request->only([
            'active_sections', 'section_content'
        ]));

        if ($success) {
            return response()->json(['message' => 'Sections configuration updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update sections configuration.'], 500);
    }

    /**
     * Get configuration by type
     */
    public function getConfiguration(Request $request, string $type)
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $config = $site->getConfiguration($type);
        
        return response()->json(['configuration' => $config]);
    }

    /**
     * Export all configurations
     */
    public function exportConfigurations()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $backup = $site->exportConfigurations();
        
        return response()->json($backup);
    }

    /**
     * Import configurations from backup
     */
    public function importConfigurations(Request $request)
    {
        $request->validate([
            'backup' => 'required|array',
            'backup.configurations' => 'required|array',
        ]);

        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->importConfigurations($request->backup);

        if ($success) {
            return response()->json(['message' => 'Configurations imported successfully.']);
        }

        return response()->json(['error' => 'Failed to import configurations.'], 500);
    }

    /**
     * Initialize default configurations
     */
    public function initializeDefaults()
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $success = $site->initializeDefaultConfigurations();

        if ($success) {
            return response()->json(['message' => 'Default configurations initialized successfully.']);
        }

        return response()->json(['error' => 'Failed to initialize default configurations.'], 500);
    }

    // ===================== NEW TEMPLATE MANAGEMENT METHODS =====================

    /**
     * Update header template settings
     */
    public function updateHeader(Request $request)
    {
        $request->validate([
            'template_id' => 'required|string',
            'links' => 'array|max:5',
            'links.*.name' => 'required|string|max:255',
            'links.*.url' => 'required|string|max:255',
        ]);

        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => __('No active site found')
                ], 404);
            }

            // Save header settings to site configuration
            $headerConfig = [
                'template_id' => $request->template_id,
                'links' => $request->links ?? [],
                'updated_at' => now()
            ];

            // Update or create site configuration for header
            SiteConfig::updateOrCreate(
                ['site_id' => $site->id, 'config_key' => 'header_template'],
                ['config_value' => json_encode($headerConfig)]
            );

            Log::info('Header template updated', $headerConfig);

            return response()->json([
                'success' => true,
                'message' => __('Header template updated successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update header template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update header template')
            ], 500);
        }
    }

    /**
     * Update footer template settings
     */
    public function updateFooter(Request $request)
    {
        $request->validate([
            'template_id' => 'required|string',
            'links' => 'array|max:10',
            'links.*.name' => 'required|string|max:255',
            'links.*.url' => 'required|string|max:255',
        ]);

        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => __('No active site found')
                ], 404);
            }

            // Save footer settings to site configuration
            $footerConfig = [
                'template_id' => $request->template_id,
                'links' => $request->links ?? [],
                'updated_at' => now()
            ];

            // Update or create site configuration for footer
            SiteConfig::updateOrCreate(
                ['site_id' => $site->id, 'config_key' => 'footer_template'],
                ['config_value' => json_encode($footerConfig)]
            );

            Log::info('Footer template updated', $footerConfig);

            return response()->json([
                'success' => true,
                'message' => __('Footer template updated successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update footer template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update footer template')
            ], 500);
        }
    }

    /**
     * Preview header template
     */
    public function previewHeader($templateId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response('<div class="alert alert-danger">No active site found</div>');
            }

            $template = TplLayout::where('id', $templateId)
                ->where('layout_type', 'header')
                ->where('status', true)
                ->first();

            if (!$template) {
                return response('<div class="alert alert-danger">Header template not found</div>');
            }

            return view('admin.pages.sections.preview', [
                'section' => (object)[
                    'id' => $template->id,
                    'name' => $template->name,
                    'layout' => $template,
                    'type' => 'header',
                    'custom_styles' => $template->custom_styles ?? '',
                    'custom_scripts' => $template->custom_scripts ?? ''
                ],
                'page' => (object)[
                    'id' => 0,
                    'name' => 'Header Preview'
                ],
                'site' => $site
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate header preview: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Failed to generate preview</div>');
        }
    }

    /**
     * Preview footer template
     */
    public function previewFooter($templateId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response('<div class="alert alert-danger">No active site found</div>');
            }

            $template = TplLayout::where('id', $templateId)
                ->where('layout_type', 'footer')
                ->where('status', true)
                ->first();

            if (!$template) {
                return response('<div class="alert alert-danger">Footer template not found</div>');
            }

            return view('admin.pages.sections.preview', [
                'section' => (object)[
                    'id' => $template->id,
                    'name' => $template->name,
                    'layout' => $template,
                    'type' => 'footer',
                    'custom_styles' => $template->custom_styles ?? '',
                    'custom_scripts' => $template->custom_scripts ?? ''
                ],
                'page' => (object)[
                    'id' => 0,
                    'name' => 'Footer Preview'
                ],
                'site' => $site
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate footer preview: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Failed to generate preview</div>');
        }
    }

    /**
     * Get header template links (New template page API)
     */
    public function getHeaderLinks($templateId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($templateId);
            if (!$template || $template->layout_type !== 'header') {
                return response()->json([
                    'success' => false,
                    'message' => 'Header template not found'
                ], 404);
            }

            // Get existing links from tpl_site nav_data
            $tplSite = TplSite::where('site_id', $site->id)->first();
            $links = ['en' => [], 'ar' => []];
            
            if ($tplSite && isset($tplSite->nav_data['links'])) {
                // Convert old format to new multi-language format if needed
                $existingLinks = $tplSite->nav_data['links'];
                if (isset($existingLinks['en']) || isset($existingLinks['ar'])) {
                    $links = $existingLinks;
                } else {
                    // Convert old single-language format to multi-language
                    $links['en'] = $existingLinks;
                }
            }

            return response()->json([
                'success' => true,
                'links' => $links
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get header links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get header links'
            ], 500);
        }
    }

    /**
     * Update header template links (New template page API)
     */
    public function updateHeaderLinksNew(Request $request, $templateId)
    {
        try {
            $request->validate([
                'links.en' => 'array|max:5',
                'links.ar' => 'array|max:5',
                'links.en.*.label' => 'required|string|max:255',
                'links.en.*.url' => 'required|string|max:255',
                'links.ar.*.label' => 'required|string|max:255',
                'links.ar.*.url' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($templateId);
            if (!$template || $template->layout_type !== 'header') {
                return response()->json([
                    'success' => false,
                    'message' => 'Header template not found'
                ], 404);
            }

            // Get or create tpl_site record
            $tplSite = TplSite::where('site_id', $site->id)->first();
            if (!$tplSite) {
                $tplSite = TplSite::create(['site_id' => $site->id]);
            }

            // Update navigation links with multi-language support
            $tplSite->update([
                'nav' => $templateId, // Set active nav template
                'nav_data' => [
                    'template_id' => $templateId,
                    'links' => $request->input('links', ['en' => [], 'ar' => []])
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Header links updated successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update header links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update header links')
            ], 500);
        }
    }

    /**
     * Get footer template links (New template page API)
     */
    public function getFooterLinks($templateId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($templateId);
            if (!$template || $template->layout_type !== 'footer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Footer template not found'
                ], 404);
            }

            // Get existing links from tpl_site footer_data
            $tplSite = TplSite::where('site_id', $site->id)->first();
            $links = ['en' => [], 'ar' => []];
            
            if ($tplSite && isset($tplSite->footer_data['links'])) {
                // Convert old format to new multi-language format if needed
                $existingLinks = $tplSite->footer_data['links'];
                if (isset($existingLinks['en']) || isset($existingLinks['ar'])) {
                    $links = $existingLinks;
                } else {
                    // Convert old single-language format to multi-language
                    $links['en'] = $existingLinks;
                }
            }

            return response()->json([
                'success' => true,
                'links' => $links
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get footer links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get footer links'
            ], 500);
        }
    }

    /**
     * Update footer template links (New template page API)
     */
    public function updateFooterLinksNew(Request $request, $templateId)
    {
        try {
            $request->validate([
                'links.en' => 'array|max:10',
                'links.ar' => 'array|max:10',
                'links.en.*.label' => 'required|string|max:255',
                'links.en.*.url' => 'required|string|max:255',
                'links.ar.*.label' => 'required|string|max:255',
                'links.ar.*.url' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($templateId);
            if (!$template || $template->layout_type !== 'footer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Footer template not found'
                ], 404);
            }

            // Get or create tpl_site record
            $tplSite = TplSite::where('site_id', $site->id)->first();
            if (!$tplSite) {
                $tplSite = TplSite::create(['site_id' => $site->id]);
            }

            // Update footer links with multi-language support
            $tplSite->update([
                'footer' => $templateId, // Set active footer template
                'footer_data' => [
                    'template_id' => $templateId,
                    'links' => $request->input('links', ['en' => [], 'ar' => []])
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Footer links updated successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update footer links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update footer links')
            ], 500);
        }
    }

    /**
     * Select header template (New template page API)
     */
    public function selectHeaderTemplate(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:tpl_layouts,id'
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($request->template_id);
            if (!$template || $template->layout_type !== 'header') {
                return response()->json([
                    'success' => false,
                    'message' => 'Header template not found'
                ], 404);
            }

            // Update active header template in site
            $site->update(['active_header_id' => $request->template_id]);

            // Also update tpl_site nav reference
            $tplSite = TplSite::where('site_id', $site->id)->first();
            if (!$tplSite) {
                $tplSite = TplSite::create(['site_id' => $site->id]);
            }
            $tplSite->update(['nav' => $request->template_id]);

            return response()->json([
                'success' => true,
                'message' => __('Header template selected successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to select header template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to select header template')
            ], 500);
        }
    }

    /**
     * Select footer template (New template page API)
     */
    public function selectFooterTemplate(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:tpl_layouts,id'
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $template = TplLayout::find($request->template_id);
            if (!$template || $template->layout_type !== 'footer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Footer template not found'
                ], 404);
            }

            // Update active footer template in site
            $site->update(['active_footer_id' => $request->template_id]);

            // Also update tpl_site footer reference
            $tplSite = TplSite::where('site_id', $site->id)->first();
            if (!$tplSite) {
                $tplSite = TplSite::create(['site_id' => $site->id]);
            }
            $tplSite->update(['footer' => $request->template_id]);

            return response()->json([
                'success' => true,
                'message' => __('Footer template selected successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to select footer template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to select footer template')
            ], 500);
        }
    }

    /**
     * Add section to page
     */
    public function addSectionToPage(Request $request, $sectionId)
    {
        try {
            $request->validate([
                'page_id' => 'required|exists:tpl_pages,id',
                'sort_order' => 'integer|min:0'
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $section = TplLayout::find($sectionId);
            if (!$section || $section->layout_type !== 'section') {
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            // Create page section
            \App\Models\TplPageSection::create([
                'page_id' => $request->page_id,
                'tpl_layouts_id' => $sectionId,
                'site_id' => $site->id,
                'name' => $section->name,
                'content' => $section->content ?? [],
                'status' => true,
                'sort_order' => $request->sort_order ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Section added to page successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add section to page: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to add section to page')
            ], 500);
        }
    }

    /**
     * Preview section
     */
    public function previewSection($sectionId)
    {
        try {
            $section = TplLayout::find($sectionId);
            if (!$section || $section->layout_type !== 'section') {
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'section' => [
                    'id' => $section->id,
                    'name' => $section->name,
                    'content' => $section->content,
                    'preview_image' => $section->preview_image
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to preview section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview section'
            ], 500);
        }
    }

    public function previewSectionView($sectionId)
    {
        try {
            $template = TplLayout::find($sectionId);
            if (!$template || $template->layout_type !== 'section') {
                abort(404, 'Section template not found');
            }

            $site = SiteConfig::first();
            if (!$site) {
                $site = (object)[
                    'name' => 'Site Preview',
                    'description' => '',
                    'language' => 'en'
                ];
            }

            return view('admin.pages.sections.preview', [
                'section' => (object)[
                    'id' => $template->id,
                    'name' => $template->name,
                    'layout' => $template,
                    'type' => 'section',
                    'custom_styles' => $template->custom_styles ?? '',
                    'custom_scripts' => $template->custom_scripts ?? ''
                ],
                'page' => (object)[
                    'id' => 0,
                    'name' => 'Section Preview'
                ],
                'site' => $site
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to preview section: ' . $e->getMessage());
            abort(500, 'Failed to preview section');
        }
    }

    /**
     * Duplicate section
     */
    public function duplicateSection($sectionId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $originalSection = TplLayout::find($sectionId);
            if (!$originalSection || $originalSection->layout_type !== 'section') {
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            // Create duplicate section
            $duplicateSection = TplLayout::create([
                'tpl_id' => $originalSection->tpl_id . '_copy_' . time(),
                'layout_type' => 'section',
                'name' => $originalSection->name . ' (Copy)',
                'description' => $originalSection->description,
                'preview_image' => $originalSection->preview_image,
                'path' => $originalSection->path,
                'default_config' => $originalSection->default_config,
                'content' => $originalSection->content,
                'configurable_fields' => $originalSection->configurable_fields,
                'status' => true,
                'sort_order' => TplLayout::where('layout_type', 'section')->count() + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Section duplicated successfully'),
                'section' => $duplicateSection
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to duplicate section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to duplicate section')
            ], 500);
        }
    }

    /**
     * Create custom section
     */
    public function createCustomSection(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'content.en.title' => 'nullable|string|max:255',
                'content.en.subtitle' => 'nullable|string',
                'content.ar.title' => 'nullable|string|max:255',
                'content.ar.subtitle' => 'nullable|string',
                'custom_styles' => 'nullable|string',
                'custom_scripts' => 'nullable|string',
                'preview_image' => 'nullable|image|max:2048'
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $previewImagePath = null;
            if ($request->hasFile('preview_image')) {
                $previewImagePath = $request->file('preview_image')->store('sections', 'public');
            }

            // Create custom section
            $customSection = TplLayout::create([
                'tpl_id' => 'custom_' . strtolower(str_replace(' ', '_', $request->name)) . '_' . time(),
                'layout_type' => 'section',
                'name' => $request->name,
                'description' => $request->description,
                'preview_image' => $previewImagePath ? '/storage/' . $previewImagePath : null,
                'path' => 'custom.section',
                'default_config' => [],
                'content' => [
                    'en' => [
                        'title' => $request->input('content.en.title'),
                        'subtitle' => $request->input('content.en.subtitle')
                    ],
                    'ar' => [
                        'title' => $request->input('content.ar.title'),
                        'subtitle' => $request->input('content.ar.subtitle')
                    ],
                    'styles' => $request->custom_styles,
                    'scripts' => $request->custom_scripts
                ],
                'configurable_fields' => ['title', 'subtitle', 'styles', 'scripts'],
                'status' => true,
                'sort_order' => TplLayout::where('layout_type', 'section')->count() + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Custom section created successfully'),
                'section' => $customSection
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create custom section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to create custom section')
            ], 500);
        }
    }

    /**
     * Get site pages for dropdown
     */
    public function getSitePages()
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $pages = \App\Models\TplPage::where('site_id', $site->id)
                ->where('status', true)
                ->select('id', 'name', 'slug')
                ->get();

            return response()->json([
                'success' => true,
                'pages' => $pages
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get site pages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get site pages'
            ], 500);
        }
    }

    /**
     * Get page sections for dropdown
     */
    public function getPageSections($pageId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $sections = \App\Models\TplPageSection::where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get()
                ->map(function($section) {
                    return [
                        'id' => $section->id,
                        'name' => $section->name,
                        'sort_order' => $section->sort_order
                    ];
                });

            return response()->json([
                'success' => true,
                'sections' => $sections
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get page sections: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get page sections'
            ], 500);
        }
    }

    /**
     * Get section content for editing
     */
    public function getSectionContent($sectionId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $section = TplLayout::find($sectionId);
            if (!$section || $section->layout_type !== 'section') {
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            // Try to find a page section that uses this template
            $pageSection = \App\Models\TplPageSection::where('tpl_layouts_id', $sectionId)
                ->where('site_id', $site->id)
                ->first();

            return response()->json([
                'success' => true,
                'content' => $section->content ?? [],
                'image' => $section->preview_image,
                'section' => $pageSection ? [
                    'id' => $pageSection->id,
                    'page_id' => $pageSection->page_id,
                    'name' => $pageSection->name
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get section content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get section content'
            ], 500);
        }
    }

    /**
     * Update section content
     */
    public function updateSectionContent(Request $request, $sectionId)
    {
        try {
            $request->validate([
                'content.en.title' => 'nullable|string|max:255',
                'content.en.subtitle' => 'nullable|string',
                'content.en.description' => 'nullable|string',
                'content.en.button_text' => 'nullable|string|max:100',
                'content.en.button_url' => 'nullable|url|max:255',
                'content.ar.title' => 'nullable|string|max:255',
                'content.ar.subtitle' => 'nullable|string',
                'content.ar.description' => 'nullable|string',
                'content.ar.button_text' => 'nullable|string|max:100',
                'content.ar.button_url' => 'nullable|url|max:255',
                'image_en' => 'nullable|image|max:2048',
                'image_ar' => 'nullable|image|max:2048'
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            $section = TplLayout::find($sectionId);
            if (!$section || $section->layout_type !== 'section') {
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            $contentData = json_decode($request->input('content'), true);
            $existingContent = $section->content ?? [];

            // Handle image uploads
            $imageUrl = $section->preview_image;
            if ($request->hasFile('image_en') || $request->hasFile('image_ar')) {
                $imageFile = $request->hasFile('image_en') ? $request->file('image_en') : $request->file('image_ar');
                $imagePath = $imageFile->store('sections', 'public');
                $imageUrl = '/storage/' . $imagePath;
            }

            // Update content
            $updatedContent = array_merge($existingContent, $contentData);

            $section->update([
                'content' => $updatedContent,
                'preview_image' => $imageUrl
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Section content updated successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update section content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update section content')
            ], 500);
        }
    }

    /**
     * Generate template preview
     */
    public function generatePreview($type, $templateId)
    {
        try {
            $template = TplLayout::find($templateId);
            if (!$template || $template->layout_type !== $type) {
                return response('<div class="alert alert-danger">Template not found</div>');
            }

            $language = request('lang', 'en');
            $content = $template->content ?? [];

            // Generate preview HTML based on template type
            switch ($type) {
                case 'header':
                    return view('admin.templates.preview.header', compact('template', 'content', 'language'));
                case 'footer':
                    return view('admin.templates.preview.footer', compact('template', 'content', 'language'));
                case 'section':
                    return view('admin.templates.preview.section', compact('template', 'content', 'language'));
                default:
                    return response('<div class="alert alert-warning">Preview not available</div>');
            }

        } catch (\Exception $e) {
            Log::error('Failed to generate template preview: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Failed to generate preview</div>');
        }
    }
}
