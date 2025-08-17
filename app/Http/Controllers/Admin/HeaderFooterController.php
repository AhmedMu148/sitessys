<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplSite;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Services\GlobalTemplateService;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HeaderFooterController extends Controller
{
    protected $globalTemplateService;
    protected $navigationService;

    public function __construct(GlobalTemplateService $globalTemplateService, NavigationService $navigationService)
    {
        $this->middleware('admin');
        $this->globalTemplateService = $globalTemplateService;
        $this->navigationService = $navigationService;
    }

    /**
     * Display the headers & footers management interface
     */
    public function index(Request $request)
    {
        try {
            // Get current site
            $site = $this->getCurrentSite($request);
            
            // Get available templates
            $availableTemplates = $this->getAvailableTemplates($site);
            
            // Get navigation configuration
            $navigationConfig = $this->getNavigationConfig($site);
            
            // Get social media configuration
            $socialMediaConfig = $this->getSocialMediaConfig($site);
            
            // Get available pages for navigation linking
            $availablePages = $this->getAvailablePages($site);

            return view('admin.layouts.headers-footers', compact(
                'site',
                'availableTemplates',
                'navigationConfig',
                'socialMediaConfig',
                'availablePages'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading headers & footers management: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Error loading headers & footers management: ' . $e->getMessage());
        }
    }

    /**
     * Activate a template (header or footer)
     */
    public function activate(Request $request, $templateId): RedirectResponse
    {
        try {
            $site = $this->getCurrentSite($request);
            $template = TplLayout::findOrFail($templateId);
            
            if ($template->layout_type === 'header') {
                $site->active_header_id = $template->id;
                $message = "Header template '{$template->name}' activated successfully.";
            } elseif ($template->layout_type === 'footer') {
                $site->active_footer_id = $template->id;
                $message = "Footer template '{$template->name}' activated successfully.";
            } else {
                return redirect()->back()->with('error', 'Invalid template type.');
            }
            
            $site->save();
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error activating template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error activating template: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user template
     */
    public function destroy(Request $request, $layout): RedirectResponse
    {
        try {
            $site = $this->getCurrentSite($request);
            $template = TplLayout::findOrFail($layout);
            
            // Only allow deletion of user-specific templates (not system templates)
            if (!str_starts_with($template->tpl_id, 'user-' . $site->user_id . '-')) {
                return redirect()->back()->with('error', 'Cannot delete system templates. You can only delete your custom templates.');
            }
            
            // Check if template is currently active
            if ($site->active_header_id == $template->id || $site->active_footer_id == $template->id) {
                return redirect()->back()->with('error', 'Cannot delete active template. Please activate another template first.');
            }
            
            $template->delete();
            
            return redirect()->back()->with('success', "Template '{$template->name}' deleted successfully.");
            
        } catch (\Exception $e) {
            Log::error('Error deleting template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting template: ' . $e->getMessage());
        }
    }

    /**
     * Create a user copy of a global template
     */
    public function createUserCopy(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:tpl_layouts,id',
                'name' => 'required|string|max:255'
            ]);

            $site = $this->getCurrentSite($request);
            $originalTemplate = TplLayout::findOrFail($request->template_id);
            
            // Create user copy
            $userTemplate = TplLayout::create([
                'tpl_id' => 'user-' . $site->user_id . '-' . time() . '-' . $originalTemplate->layout_type,
                'layout_type' => $originalTemplate->layout_type,
                'name' => $request->name,
                'content' => $originalTemplate->content,
                'configurable_fields' => $originalTemplate->configurable_fields,
                'default_config' => $originalTemplate->default_config,
                'path' => $originalTemplate->path,
                'status' => true,
                'sort_order' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => "Template copied successfully as '{$userTemplate->name}'",
                'template' => $userTemplate
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating user template copy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating template copy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update navigation links
     */
    public function updateNavigation(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'header_links' => 'array|max:5',
                'footer_links' => 'array|max:10',
                'show_auth_in_header' => 'boolean',
                'show_auth_in_footer' => 'boolean'
            ]);

            $site = $this->getCurrentSite($request);
            $tplSite = TplSite::firstOrCreate(['site_id' => $site->id]);
            
            // Update navigation data
            $navData = $tplSite->nav_data ?? [];
            $footerData = $tplSite->footer_data ?? [];
            
            if ($request->has('header_links')) {
                $navData['links'] = array_slice($request->header_links, 0, 5);
            }
            
            if ($request->has('footer_links')) {
                $footerData['links'] = array_slice($request->footer_links, 0, 10);
            }
            
            if ($request->has('show_auth_in_header')) {
                $navData['show_auth'] = $request->boolean('show_auth_in_header');
            }
            
            if ($request->has('show_auth_in_footer')) {
                $footerData['show_auth'] = $request->boolean('show_auth_in_footer');
            }
            
            $tplSite->update([
                'nav_data' => $navData,
                'footer_data' => $footerData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Navigation settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating navigation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating navigation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a navigation link
     */
    public function addNavigationLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:header,footer',
                'title' => 'required|string|max:100',
                'url' => 'required|string|max:255',
                'active' => 'boolean',
                'external' => 'boolean'
            ]);

            $site = $this->getCurrentSite($request);
            $tplSite = TplSite::firstOrCreate(['site_id' => $site->id]);
            
            $linkData = [
                'name' => $request->title,
                'url' => $request->url,
                'active' => $request->boolean('active', true),
                'external' => $request->boolean('external', false)
            ];

            if ($request->type === 'header') {
                $currentLinks = $tplSite->nav_data['links'] ?? [];
                if (count($currentLinks) >= 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum 5 header links allowed'
                    ], 400);
                }
                $currentLinks[] = $linkData;
                $tplSite->nav_data = array_merge($tplSite->nav_data ?? [], ['links' => $currentLinks]);
            } else {
                $currentLinks = $tplSite->footer_data['links'] ?? [];
                if (count($currentLinks) >= 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum 10 footer links allowed'
                    ], 400);
                }
                $currentLinks[] = $linkData;
                $tplSite->footer_data = array_merge($tplSite->footer_data ?? [], ['links' => $currentLinks]);
            }

            $tplSite->save();

            return response()->json([
                'success' => true,
                'message' => 'Navigation link added successfully',
                'link' => $linkData
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding navigation link: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding navigation link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a navigation link
     */
    public function removeNavigationLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:header,footer',
                'index' => 'required|integer|min:0'
            ]);

            $site = $this->getCurrentSite($request);
            $tplSite = TplSite::where('site_id', $site->id)->first();
            
            if (!$tplSite) {
                return response()->json([
                    'success' => false,
                    'message' => 'No navigation configuration found'
                ], 404);
            }

            if ($request->type === 'header') {
                $links = $tplSite->nav_data['links'] ?? [];
                if (isset($links[$request->index])) {
                    unset($links[$request->index]);
                    $tplSite->nav_data = array_merge($tplSite->nav_data ?? [], ['links' => array_values($links)]);
                }
            } else {
                $links = $tplSite->footer_data['links'] ?? [];
                if (isset($links[$request->index])) {
                    unset($links[$request->index]);
                    $tplSite->footer_data = array_merge($tplSite->footer_data ?? [], ['links' => array_values($links)]);
                }
            }

            $tplSite->save();

            return response()->json([
                'success' => true,
                'message' => 'Navigation link removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing navigation link: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing navigation link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle navigation link status
     */
    public function toggleNavigationLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:header,footer',
                'index' => 'required|integer|min:0',
                'active' => 'required|boolean'
            ]);

            $site = $this->getCurrentSite($request);
            $tplSite = TplSite::where('site_id', $site->id)->first();
            
            if (!$tplSite) {
                return response()->json([
                    'success' => false,
                    'message' => 'No navigation configuration found'
                ], 404);
            }

            if ($request->type === 'header') {
                $links = $tplSite->nav_data['links'] ?? [];
                if (isset($links[$request->index])) {
                    $links[$request->index]['active'] = $request->active;
                    $tplSite->nav_data = array_merge($tplSite->nav_data ?? [], ['links' => $links]);
                }
            } else {
                $links = $tplSite->footer_data['links'] ?? [];
                if (isset($links[$request->index])) {
                    $links[$request->index]['active'] = $request->active;
                    $tplSite->footer_data = array_merge($tplSite->footer_data ?? [], ['links' => $links]);
                }
            }

            $tplSite->save();

            return response()->json([
                'success' => true,
                'message' => 'Navigation link status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling navigation link: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error toggling navigation link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update social media links
     */
    public function updateSocialMedia(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'social_media' => 'required|array',
                'social_media.*' => 'nullable|url'
            ]);

            $site = $this->getCurrentSite($request);
            $tplSite = TplSite::firstOrCreate(['site_id' => $site->id]);
            
            // Filter out empty values
            $socialMedia = array_filter($request->social_media, function($value) {
                return !empty($value);
            });

            // Store social media in footer_data as it was originally
            $footerData = $tplSite->footer_data ?? [];
            $footerData['social_media'] = $socialMedia;
            $tplSite->footer_data = $footerData;
            $tplSite->save();

            return response()->json([
                'success' => true,
                'message' => 'Social media links updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating social media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating social media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current site for the user
     */
    private function getCurrentSite(Request $request): Site
    {
        // If site_id is provided in request, use it
        if ($request->has('site_id')) {
            $site = Site::find($request->site_id);
            if ($site && ($site->user_id == Auth::id() || Auth::user()->role === 'admin')) {
                return $site;
            }
        }

        // Otherwise get user's first site or admin's first site
        if (Auth::user()->role === 'admin') {
            return Site::first() ?? abort(404, 'No sites found');
        }

        return Site::where('user_id', Auth::id())->first() ?? abort(404, 'No site found for user');
    }

    /**
     * Get available templates
     */
    private function getAvailableTemplates(Site $site): array
    {
        // Get all available templates (system templates + user's own templates)
        $allTemplates = TplLayout::where(function($query) use ($site) {
                // Include all non-user templates OR user templates that belong to current user
                $query->where('tpl_id', 'not like', 'user-%')
                      ->orWhere('tpl_id', 'like', 'user-' . $site->user_id . '-%');
            })
            ->orderBy('layout_type')
            ->orderBy('name')
            ->get()
            ->map(function($template) use ($site) {
                return [
                    'id' => $template->id,
                    'tpl_id' => $template->tpl_id,
                    'layout_type' => $template->layout_type,
                    'name' => $template->name,
                    'description' => $template->description ?? '',
                    'preview_image' => $template->preview_image ?? null,
                    'is_user_template' => str_starts_with($template->tpl_id, 'user-' . $site->user_id . '-')
                ];
            })
            ->toArray();

        // Separate user-specific templates
        $userTemplates = array_filter($allTemplates, function($template) use ($site) {
            return str_starts_with($template['tpl_id'], 'user-' . $site->user_id . '-');
        });

        // System templates are all non-user templates
        $systemTemplates = array_filter($allTemplates, function($template) {
            return !str_starts_with($template['tpl_id'], 'user-');
        });

        return [
            'global' => $allTemplates, // All available templates for this user
            'user' => array_values($userTemplates),
            'system' => array_values($systemTemplates)
        ];
    }

    /**
     * Get navigation configuration
     */
    private function getNavigationConfig(Site $site): array
    {
        $tplSite = TplSite::where('site_id', $site->id)->first();
        
        if (!$tplSite) {
            // Create a new TplSite record if it doesn't exist
            $tplSite = TplSite::create([
                'site_id' => $site->id,
                'nav_data' => ['links' => [], 'show_auth' => true, 'social_media' => []],
                'footer_data' => ['links' => [], 'show_auth' => true],
            ]);
        }

        // Normalize link data - convert 'name' to 'title' for consistent usage
        $headerLinks = collect($tplSite->nav_data['links'] ?? [])->map(function ($link) {
            return [
                'title' => $link['title'] ?? $link['name'] ?? 'Untitled',
                'url' => $link['url'] ?? '#',
                'active' => $link['active'] ?? true,
                'external' => $link['external'] ?? false
            ];
        })->toArray();

        $footerLinks = collect($tplSite->footer_data['links'] ?? [])->map(function ($link) {
            return [
                'title' => $link['title'] ?? $link['name'] ?? 'Untitled',
                'url' => $link['url'] ?? '#',
                'active' => $link['active'] ?? true,
                'external' => $link['external'] ?? false
            ];
        })->toArray();

        return [
            'header_links' => $headerLinks,
            'footer_links' => $footerLinks,
            'show_auth_in_header' => $tplSite->nav_data['show_auth'] ?? true,
            'show_auth_in_footer' => $tplSite->footer_data['show_auth'] ?? true
        ];
    }

    /**
     * Get social media configuration
     */
    private function getSocialMediaConfig(Site $site): array
    {
        $tplSite = TplSite::where('site_id', $site->id)->first();
        
        if (!$tplSite) {
            // Create a new TplSite record if it doesn't exist
            $tplSite = TplSite::create([
                'site_id' => $site->id,
                'nav_data' => ['links' => [], 'show_auth' => false],
                'footer_data' => ['links' => [], 'show_auth' => false, 'social_media' => []],
            ]);
        }
        
        return $tplSite->footer_data['social_media'] ?? [];
    }

    /**
     * Add section template to page
     */
    public function addSectionToPage(Request $request): JsonResponse
    {
        try {
            // Log the incoming request for debugging
            Log::info('Add section to page request:', [
                'all_data' => $request->all(),
                'json_data' => $request->json()->all(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);

            // Validate the request
            $validated = $request->validate([
                'template_id' => 'required|integer|exists:tpl_layouts,id',
                'page_id' => 'required|integer|exists:tpl_pages,id',
                'position' => 'nullable|in:start,end,custom',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            $user = Auth::user();
            $site = $this->getCurrentSite($request);
            
            Log::info('User and site info:', [
                'user_id' => $user->id,
                'site_id' => $site->id
            ]);
            
            // Check if page belongs to user's site
            $page = TplPage::where('id', $validated['page_id'])
                ->where('site_id', $site->id)
                ->first();
                
            if (!$page) {
                Log::warning('Page not found or not accessible', [
                    'page_id' => $validated['page_id'],
                    'site_id' => $site->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found or not accessible'
                ], 404);
            }

            // Check if template exists and is a section
            $template = TplLayout::where('id', $validated['template_id'])
                ->where('layout_type', 'section')
                ->first();
                
            if (!$template) {
                Log::warning('Section template not found', [
                    'template_id' => $validated['template_id']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Section template not found'
                ], 404);
            }

            // Determine sort order
            $position = $validated['position'] ?? 'end';
            $sortOrder = 0;
            
            if ($position === 'start') {
                $sortOrder = 0;
                // Update existing sections to make room
                TplPageSection::where('page_id', $page->id)
                    ->increment('sort_order');
            } elseif ($position === 'end') {
                $maxOrder = TplPageSection::where('page_id', $page->id)
                    ->max('sort_order');
                $sortOrder = ($maxOrder ?? -1) + 1;
            } elseif ($position === 'custom' && isset($validated['sort_order'])) {
                $sortOrder = $validated['sort_order'];
                // Update existing sections to make room if needed
                TplPageSection::where('page_id', $page->id)
                    ->where('sort_order', '>=', $sortOrder)
                    ->increment('sort_order');
            }

            // Create the page section
            $pageSection = TplPageSection::create([
                'page_id' => $page->id,
                'site_id' => $site->id,
                'tpl_layouts_id' => $template->id,
                'name' => $template->name,
                'content' => $template->content ?? '{}',
                'status' => true,
                'sort_order' => $sortOrder
            ]);

            Log::info('Section added to page successfully', [
                'page_id' => $page->id,
                'template_id' => $template->id,
                'section_id' => $pageSection->id,
                'sort_order' => $sortOrder
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section added to page successfully',
                'data' => [
                    'section_id' => $pageSection->id,
                    'page_name' => $page->name,
                    'template_name' => $template->name,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Failed to add section to page: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add section to page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current navigation configuration (API endpoint)
     */
    public function getNavigation(Request $request): JsonResponse
    {
        try {
            $site = $this->getCurrentSite($request);
            $navigationConfig = $this->getNavigationConfig($site);
            
            return response()->json([
                'success' => true,
                'navigationConfig' => $navigationConfig
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting navigation configuration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting navigation configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current social media configuration (API endpoint)
     */
    public function getSocialMedia(Request $request): JsonResponse
    {
        try {
            $site = $this->getCurrentSite($request);
            $socialMediaConfig = $this->getSocialMediaConfig($site);
            
            return response()->json([
                'success' => true,
                'socialMediaConfig' => $socialMediaConfig
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting social media configuration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting social media configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available pages for navigation
     */
    private function getAvailablePages(Site $site): array
    {
        return TplPage::where('site_id', $site->id)
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'link'])
            ->toArray();
    }

    /**
     * Get header content for editing
     */
    public function getHeaderContent(Request $request, $id): JsonResponse
    {
        try {
            $site = $this->getCurrentSite($request);
            
            // Verify this is the active header for the site
            if ($site->active_header_id != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This header is not active for the current site'
                ], 403);
            }
            
            $header = TplLayout::where('id', $id)
                ->where('layout_type', 'header')
                ->first();
                
            if (!$header) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'header' => [
                    'id' => $header->id,
                    'name' => $header->name,
                    'content_data' => $header->content,
                    'content' => $header->content
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting header content: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting header content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update header content
     */
    public function updateHeaderContent(Request $request, $id): JsonResponse
{
    try {
        Log::info('=== Header Content Update Request ===', [
            'id' => $id,
            'payload' => $request->all()
        ]);

        $site  = $this->getCurrentSite($request);
        $force = (bool) $request->boolean('force_override');

        if ($site->active_header_id != $id && !$force) {
            return response()->json([
                'success' => false,
                'message' => 'This header is not active for the current site'
            ], 403);
        }

        $header = \App\Models\TplLayout::where('id', $id)
            ->where('layout_type', 'header')
            ->first();

        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'Header not found'
            ], 404);
        }

        // اقرأ الداتا القادمة كـ array مهما كان نوعها
        $incoming = $request->input('content_data', []);
        $incoming = $this->toArray($incoming);   // <— هنضيف الهيلبر تحت

        // الموجود حاليًا في قاعدة البيانات (هيكون Array بسبب الـ casts)
        $existing = $this->toArray($header->content);

        // حافظ على مفاتيح الـ HTML إن كانت موجودة سابقًا ولم تُرسل الآن
        foreach (['html','template','template_html','raw_html'] as $k) {
            if (array_key_exists($k, $existing) && !array_key_exists($k, $incoming)) {
                $incoming[$k] = $existing[$k];
            }
        }

        // دمج لطيف: المفاتيح الجديدة تغلب، والمصفوفات الفرعية تندمج
        $merged = array_replace_recursive($existing, $incoming);

        if (empty($merged)) {
            return response()->json([
                'success' => false,
                'message' => 'Nothing to update'
            ], 422);
        }

        // لو مفيش html جوه المحتوى، حاول نجيب ملف البليد كـ fallback
        if (!isset($merged['html']) && !empty($header->path)) {
            try {
                $relative = str_replace(['..'], '', ltrim($header->path, '/'));
                $fullPath = str_starts_with($relative, 'resources/views')
                    ? base_path($relative)
                    : resource_path('views/'.$relative);

                if (is_file($fullPath)) {
                    $merged['html'] = $merged['html_original'] = file_get_contents($fullPath);
                }
            } catch (\Throwable $e) {
                Log::warning('Header HTML fallback failed: '.$e->getMessage());
            }
        }

        // الحفظ — بفضل الـ casts هيُخزن JSON تلقائيًا
        $header->content = $merged;
        $header->save();

        return response()->json([
            'success' => true,
            'message' => 'Header content updated successfully',
            'header'  => [
                'id'           => $header->id,
                'name'         => $header->name,
                'content_data' => $header->content, // راجع كـ Array
            ]
        ]);
    } catch (\Throwable $e) {
        Log::error('Error updating header content: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        return response()->json([
            'success' => false,
            'message' => 'Error updating header content: '.$e->getMessage()
        ], 500);
    }
}

// Helper صغير يحوّل أي قيمة لـ Array بأمان
private function toArray($value): array
{
    if (is_array($value)) return $value;
    if (is_object($value)) return json_decode(json_encode($value), true) ?? [];
    if (is_string($value)) {
        $trim = trim($value);
        if ($trim === '') return [];
        $decoded = json_decode($trim, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;
    }
    return [];
}




    /**
     * Get footer content for editing
     */
    // App/Http/Controllers/Admin/HeaderFooterController.php

public function getFooterContent(Request $request, $id): JsonResponse
{
    try {
        $site = $this->getCurrentSite($request);

        if ($site->active_footer_id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'This footer is not active for the current site'
            ], 403);
        }

        $footer = TplLayout::where('id', $id)
            ->where('layout_type', 'footer')
            ->first();

        if (!$footer) {
            return response()->json(['success' => false, 'message' => 'Footer not found'], 404);
        }

        $tplSite = TplSite::firstOrCreate(['site_id' => $site->id]);

        // الكونفيج الفعلية المعروضة تيجي من footer_data
        $fd = $tplSite->footer_data ?? [];

        // نطبّع شويّة أسماء عشان الفورم يلاقي اللي متوقعه
        // Merge default_config with any simple values saved in footer_data so editor shows persisted fields
        $defaults = $footer->default_config ?? [];
        // Prepare fd simple copy (exclude complex keys handled below)
        $fd_simple = is_array($fd) ? $fd : [];
        foreach (['links', 'social_media', 'newsletter', 'show_auth'] as $k) {
            if (array_key_exists($k, $fd_simple)) unset($fd_simple[$k]);
        }

        $config = array_merge($defaults, $fd_simple, [
            // روابط الفوتر (قائمة)
            'footer_links'           => $fd['links'] ?? [],
            // النشرة
            'show_newsletter'        => data_get($fd, 'newsletter.enabled', data_get($defaults, 'show_newsletter', true)),
            'newsletter_title'       => data_get($fd, 'newsletter.title', data_get($defaults, 'newsletter_title', 'Newsletter')),
            'newsletter_description' => data_get($fd, 'newsletter.description', data_get($defaults, 'newsletter_description', 'Stay updated')),
            // السوشيال: حوّل من key=>url إلى [{icon,url}]
            'social_links'           => collect($fd['social_media'] ?? [])->map(function($url, $platform) {
                return ['icon' => 'fab fa-'.$platform, 'url' => $url];
            })->values()->all(),
            // إظهار روابط الدخول
            'show_auth'              => $fd['show_auth'] ?? data_get($defaults, 'show_auth', true),
        ]);

    // Expose contact_info nested fields as top-level simple keys so the editor can render them
    // Prioritize explicit flat values in footer_data, then fall back to defaults.contact_info
    $config['contact_email'] = $fd['contact_email'] ?? data_get($defaults, 'contact_info.email', '');
    $config['contact_phone'] = $fd['contact_phone'] ?? data_get($defaults, 'contact_info.phone', '');
    $config['address']       = $fd['address']       ?? data_get($defaults, 'contact_info.address', '');

        // محتوى القالب (HTML/CSS/JS) يبقى زي ما هو من TplLayout
        $content = [
            'html' => data_get($footer->content, 'html', ''),
            'css'  => data_get($footer->content, 'css', ''),
            'js'   => data_get($footer->content, 'js', ''),
        ];

        return response()->json([
            'success' => true,
            'footer'  => [
                'id'           => $footer->id,
                'name'         => $footer->name,
                'content'      => $content,     // HTML/CSS/JS
                'content_data' => $config,      // كونفيج الإدِتِر (من footer_data)
            ]
        ]);
    } catch (\Throwable $e) {
        Log::error('Error getting footer content', ['e' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Error getting footer content: ' . $e->getMessage()], 500);
    }
}


    /**
     * Update footer content
     */
public function updateFooterContent(Request $request, $id): JsonResponse
{
    try {
        Log::info('=== Footer Content Update Request ===', ['id' => $id, 'payload' => $request->all()]);

        $site  = $this->getCurrentSite($request);
        $force = (bool) $request->boolean('force_override');

        if ($site->active_footer_id != $id && !$force) {
            return response()->json([
                'success' => false,
                'message' => 'This footer is not active for the current site'
            ], 403);
        }

        $footer = TplLayout::where('id', $id)
            ->where('layout_type', 'footer')
            ->first();

        if (!$footer) {
            return response()->json(['success' => false, 'message' => 'Footer not found'], 404);
        }

        $incoming = $this->toArray($request->input('content_data', []));
        $tplSite  = TplSite::firstOrCreate(['site_id' => $site->id]);
        $fd       = $tplSite->footer_data ?? [];

        // 1) روابط الفوتر: اقبل footer_links أو links
        if (isset($incoming['footer_links']) || isset($incoming['links'])) {
            $fd['links'] = $incoming['footer_links'] ?? $incoming['links'];
        }

        // 2) النشرة البريدية
        $fd['newsletter'] = [
            'enabled'     => $incoming['show_newsletter']        ?? data_get($fd, 'newsletter.enabled', true),
            'title'       => $incoming['newsletter_title']       ?? data_get($fd, 'newsletter.title', 'Newsletter'),
            'description' => $incoming['newsletter_description'] ?? data_get($fd, 'newsletter.description', 'Stay updated'),
        ];

        // 3) السوشيال: حول من [{icon,url}] إلى key=>url
        if (isset($incoming['social_links']) && is_array($incoming['social_links'])) {
            $fd['social_media'] = [];
            foreach ($incoming['social_links'] as $row) {
                $icon = (string)($row['icon'] ?? '');
                $url  = (string)($row['url']  ?? '');
                if ($url !== '') {
                    // استخرج المنصّة من آخر كلمة في الأيكون (fab fa-twitter => twitter)
                    $platform = trim(str_replace('fab fa-', '', strtolower($icon)));
                    $platform = $platform ?: 'link';
                    $fd['social_media'][$platform] = $url;
                }
            }
        }

        // 4) show_auth لو جاية
        if (array_key_exists('show_auth', $incoming)) {
            $fd['show_auth'] = (bool)$incoming['show_auth'];
        }

        // 5) Persist any additional incoming simple keys into footer_data
        // This allows editing default_config fields like company_name, copyright, description, etc.
        $handled = ['footer_links', 'links', 'show_newsletter', 'newsletter_title', 'newsletter_description', 'social_links', 'show_auth'];
        foreach ($incoming as $k => $v) {
            if (in_array($k, $handled, true)) continue;
            // Only store scalar values or arrays (avoid storing objects with unexpected structure)
            if (is_scalar($v) || is_array($v)) {
                $fd[$k] = $v;
            }
        }

    // Debug: log incoming payload and footer data before save
    Log::info('Footer update incoming payload (debug):', ['incoming' => $incoming]);
    Log::info('Footer data about to be saved (debug):', ['footer_data' => $fd]);

    $tplSite->footer_data = $fd;
    $tplSite->save();

    // Refresh the model to ensure we have the persisted value and log it
    try {
        $tplSite->refresh();
        Log::info('Footer data after save (debug):', ['footer_data' => $tplSite->footer_data]);
    } catch (\Throwable $e) {
        Log::warning('Failed to refresh tplSite after saving footer_data', ['error' => $e->getMessage()]);
    }

    $persisted = $tplSite->footer_data ?? $fd;

    return response()->json([
        'success' => true,
        'message' => 'Footer content updated successfully',
        'footer'  => [
            'id'           => $footer->id,
            'name'         => $footer->name,
            'content_data' => $persisted,
        ]
    ]);
    } catch (\Throwable $e) {
        Log::error('Error updating footer content: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        return response()->json(['success' => false, 'message' => 'Error updating footer content: ' . $e->getMessage()], 500);
    }
}




}
