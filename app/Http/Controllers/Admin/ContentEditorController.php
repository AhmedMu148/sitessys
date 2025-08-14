<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplSite;
use App\Models\TplPageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContentEditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show active header/footer and active sections with edit options.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            abort(404, 'No active site found');
        }

        $activeHeader = $site->active_header_id
            ? TplLayout::where('id', $site->active_header_id)->first()
            : null;

        $activeFooter = $site->active_footer_id
            ? TplLayout::where('id', $site->active_footer_id)->first()
            : null;

        // Load pages with their active sections
        $pages = TplPage::where('site_id', $site->id)
            ->with(['sections' => function ($q) {
                $q->where('status', true)->orderBy('sort_order');
            }])
            ->orderBy('name')
            ->get();

        // For modals / editors
        $navigationConfig  = $this->getNavigationConfig($site);
        $socialMediaConfig = $this->getSocialMediaConfig($site);
        $availablePages    = $this->getAvailablePages($site);

        return view('admin.content.index', compact(
            'site',
            'activeHeader',
            'activeFooter',
            'pages',
            'navigationConfig',
            'socialMediaConfig',
            'availablePages'
        ));
    }

    /**
     * Get navigation configuration for the site
     */
    private function getNavigationConfig(Site $site): array
    {
        $tplSite = $site->tplSite;

        if (!$tplSite) {
            $tplSite = TplSite::create([
                'site_id'     => $site->id,
                'nav_data'    => ['links' => [], 'show_auth' => false],
                'footer_data' => ['links' => [], 'show_auth' => false],
            ]);
        }

        return [
            'header_links'        => $tplSite->nav_data['links']        ?? [],
            'footer_links'        => $tplSite->footer_data['links']     ?? [],
            'show_auth_in_header' => $tplSite->nav_data['show_auth']    ?? false,
            'show_auth_in_footer' => $tplSite->footer_data['show_auth'] ?? false,
        ];
    }

    /**
     * Get social media configuration for the site
     */
    private function getSocialMediaConfig(Site $site): array
    {
        $tplSite = $site->tplSite;

        if (!$tplSite) {
            $tplSite = TplSite::create([
                'site_id'     => $site->id,
                'nav_data'    => ['links' => [], 'show_auth' => false],
                'footer_data' => ['links' => [], 'show_auth' => false, 'social_media' => []],
            ]);
        }

        return $tplSite->footer_data['social_media'] ?? [];
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

    /* -----------------------------------------------------------------
     | Header endpoints
     ----------------------------------------------------------------- */

    public function getHeaderContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_header_id) {
                return response()->json(['success' => false, 'message' => 'No active header found'], 404);
            }

            $header = TplLayout::where('id', $site->active_header_id)->first();
            if (!$header) {
                return response()->json(['success' => false, 'message' => 'Header layout not found'], 404);
            }

            $tplSite = TplSite::where('site_id', $site->id)
                ->where('tpl_layouts_id', $header->id)
                ->first();

            return response()->json([
                'success' => true,
                'header'  => [
                    'id'                  => $header->id,
                    'name'                => $header->name,
                    'description'         => $header->description,
                    'configurable_fields' => $header->configurable_fields,
                    'default_config'      => $header->default_config,
                    'current_content'     => $tplSite ? $tplSite->content : null,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error loading header data: ' . $e->getMessage()], 500);
        }
    }

    public function updateHeaderContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_header_id) {
                return response()->json(['success' => false, 'message' => 'No active header found'], 404);
            }

            $header = TplLayout::where('id', $site->active_header_id)->first();
            if (!$header) {
                return response()->json(['success' => false, 'message' => 'Header layout not found'], 404);
            }

            $payload = $this->toArray($request->input('content_data', [])); // safe array

            TplSite::updateOrCreate(
                ['site_id' => $site->id, 'tpl_layouts_id' => $header->id],
                ['content' => $payload]
            );

            return response()->json(['success' => true, 'message' => 'Header content updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error updating header content: ' . $e->getMessage()], 500);
        }
    }

    /* -----------------------------------------------------------------
     | Footer endpoints
     ----------------------------------------------------------------- */

    public function getFooterContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_footer_id) {
                return response()->json(['success' => false, 'message' => 'No active footer found'], 404);
            }

            $footer = TplLayout::where('id', $site->active_footer_id)->first();
            if (!$footer) {
                return response()->json(['success' => false, 'message' => 'Footer layout not found'], 404);
            }

            $tplSite = TplSite::where('site_id', $site->id)
                ->where('tpl_layouts_id', $footer->id)
                ->first();

            return response()->json([
                'success' => true,
                'footer'  => [
                    'id'                  => $footer->id,
                    'name'                => $footer->name,
                    'description'         => $footer->description,
                    'configurable_fields' => $footer->configurable_fields,
                    'default_config'      => $footer->default_config,
                    'current_content'     => $tplSite ? $tplSite->content : null,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error loading footer data: ' . $e->getMessage()], 500);
        }
    }

    public function updateFooterContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_footer_id) {
                return response()->json(['success' => false, 'message' => 'No active footer found'], 404);
            }

            $footer = TplLayout::where('id', $site->active_footer_id)->first();
            if (!$footer) {
                return response()->json(['success' => false, 'message' => 'Footer layout not found'], 404);
            }

            $payload = $this->toArray($request->input('content_data', [])); // safe array

            TplSite::updateOrCreate(
                ['site_id' => $site->id, 'tpl_layouts_id' => $footer->id],
                ['content' => $payload]
            );

            return response()->json(['success' => true, 'message' => 'Footer content updated successfully']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error updating footer content: ' . $e->getMessage()], 500);
        }
    }

    /* -----------------------------------------------------------------
     | Sections endpoints
     ----------------------------------------------------------------- */

    /**
     * Get section content for editing.
     */
    public function getSectionContent(Request $request, $pageId, $sectionId)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site) {
                return response()->json(['success' => false, 'message' => 'No active site found'], 404);
            }

            $section = TplPageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->with('layout')
                ->first();

            if (!$section) {
                return response()->json(['success' => false, 'message' => 'Section not found'], 404);
            }

            // Prepare content data fallbacks
            $contentData = $section->content_data;
            if (empty($contentData) && !empty($section->content)) {
                $contentData = is_string($section->content)
                    ? (json_decode($section->content, true) ?? [])
                    : $section->content;
            }
            if (empty($contentData) && !empty($section->layout->default_config)) {
                $contentData = $section->layout->default_config;
            }

            Log::info('getSectionContent - returning data', [
                'section_id'      => $sectionId,
                'layout_name'     => optional($section->layout)->name,
                'has_contentData' => !empty($contentData),
            ]);

            return response()->json([
                'success' => true,
                'section' => [
                    'id'             => $section->id,
                    'name'           => $section->name,
                    'content'        => $section->content,
                    'content_data'   => $contentData,
                    'settings'       => $section->settings,
                    'custom_styles'  => $section->custom_styles,
                    'custom_scripts' => $section->custom_scripts,
                    'layout'         => [
                        'id'                  => $section->layout->id,
                        'name'                => $section->layout->name,
                        'description'         => $section->layout->description,
                        'configurable_fields' => $section->layout->configurable_fields,
                        'default_config'      => $section->layout->default_config,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error in getSectionContent: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error loading section data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update section content.
     */
    public function updateSectionContent(Request $request, $pageId, $sectionId)
    {
        try {
            // Write debug file (optional but handy)
            @file_put_contents(
                storage_path('logs/section_update_debug.log'),
                "[" . date('Y-m-d H:i:s') . "] UpdateSectionContent called\n" .
                "Page ID: $pageId\n" .
                "Section ID: $sectionId\n" .
                "Request Data: " . json_encode($request->all()) . "\n" .
                "Headers: " . json_encode($request->headers->all()) . "\n\n",
                FILE_APPEND | LOCK_EX
            );

            Log::info('UpdateSectionContent called', [
                'pageId'    => $pageId,
                'sectionId' => $sectionId,
                'data'      => $request->all(),
            ]);

            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site) {
                return response()->json(['success' => false, 'message' => 'No active site found'], 404);
            }

            $section = TplPageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->first();

            if (!$section) {
                return response()->json(['success' => false, 'message' => 'Section not found'], 404);
            }

            // Accept array or JSON string
            $contentData = $this->toArray($request->input('content_data', []));

            // Safe log (context must be an array)
            Log::info('Content data to save', ['payload' => $contentData]);

            // Normalize aliases
            $aliases = [
                'title'            => ['hero_title'],
                'hero_title'       => ['title'],
                'subtitle'         => ['hero_description', 'content', 'description'],
                'hero_description' => ['subtitle', 'content', 'description'],
                'button_text'      => ['cta_text'],
                'cta_text'         => ['button_text'],
                'button_url'       => ['cta_url'],
                'cta_url'          => ['button_url'],
            ];
            foreach ($aliases as $primary => $alts) {
                if (isset($contentData[$primary]) && $contentData[$primary] !== '') {
                    foreach ($alts as $alt) {
                        if (!isset($contentData[$alt]) || $contentData[$alt] === '') {
                            $contentData[$alt] = $contentData[$primary];
                        }
                    }
                }
            }

            // Save to both fields (compat)
            $section->update([
                'content_data' => $contentData,
                'content'      => $contentData,
            ]);

            Log::info('Section updated successfully', [
                'section_id'       => $section->id,
                'new_content_data' => $section->fresh()->content_data,
            ]);

            $fresh = $section->fresh();

            return response()->json([
                'success'      => true,
                'message'      => 'Section content updated successfully',
                'content_data' => $fresh->content_data,
                'content'      => $fresh->content,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error updating section content', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Error updating section content: ' . $e->getMessage()], 500);
        }
    }

    /* -----------------------------------------------------------------
     | Helpers
     ----------------------------------------------------------------- */

    /**
     * Safely convert any value to array (supports JSON strings).
     */
    private function toArray($value): array
    {
        if (is_array($value))  return $value;
        if (is_object($value)) return json_decode(json_encode($value), true) ?? [];
        if (is_string($value)) {
            $trim = trim($value);
            if ($trim === '') return [];
            $decoded = json_decode($trim, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }
}
