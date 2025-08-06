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
            
            // Don't allow deletion of global templates
            if (str_starts_with($template->tpl_id, 'global-')) {
                return redirect()->back()->with('error', 'Cannot delete global templates.');
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

            $tplSite->footer_data = array_merge($tplSite->footer_data ?? [], ['social_media' => $socialMedia]);
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
        // Get global templates
        $globalTemplates = TplLayout::where('tpl_id', 'like', 'global-%')
            ->orderBy('layout_type')
            ->orderBy('name')
            ->get()
            ->map(function($template) {
                return [
                    'id' => $template->id,
                    'tpl_id' => $template->tpl_id,
                    'layout_type' => $template->layout_type,
                    'name' => $template->name,
                    'description' => $template->description ?? '',
                    'preview_image' => $template->preview_image ?? null
                ];
            })
            ->toArray();

        // Get user templates for this site
        $userTemplates = TplLayout::where('tpl_id', 'like', 'user-' . $site->user_id . '-%')
            ->orderBy('layout_type')
            ->orderBy('name')
            ->get()
            ->map(function($template) {
                return [
                    'id' => $template->id,
                    'tpl_id' => $template->tpl_id,
                    'layout_type' => $template->layout_type,
                    'name' => $template->name,
                    'description' => $template->description ?? '',
                    'preview_image' => $template->preview_image ?? null
                ];
            })
            ->toArray();

        return [
            'global' => $globalTemplates,
            'user' => $userTemplates
        ];
    }

    /**
     * Get navigation configuration
     */
    private function getNavigationConfig(Site $site): array
    {
        $tplSite = TplSite::where('site_id', $site->id)->first();
        
        if (!$tplSite) {
            return [
                'header_links' => [],
                'footer_links' => [],
                'show_auth_in_header' => true,
                'show_auth_in_footer' => true
            ];
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
}
