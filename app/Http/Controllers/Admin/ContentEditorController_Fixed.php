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

        return view('admin.content.index', compact('activeHeader', 'activeFooter', 'pages'));
    }

    /**
     * Get header content for editing
     */
    public function getHeaderContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_header_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active header found'
                ], 404);
            }

            $header = TplLayout::where('id', $site->active_header_id)->first();

            if (!$header) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header layout not found'
                ], 404);
            }

            // Get current site configuration content
            $tplSite = TplSite::where('site_id', $site->id)
                ->where('tpl_layouts_id', $header->id)
                ->first();

            return response()->json([
                'success' => true,
                'header' => [
                    'id' => $header->id,
                    'name' => $header->name,
                    'description' => $header->description,
                    'configurable_fields' => $header->configurable_fields,
                    'default_config' => $header->default_config,
                    'current_content' => $tplSite ? $tplSite->content : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading header data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update header content
     */
    public function updateHeaderContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_header_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active header found'
                ], 404);
            }

            $header = TplLayout::where('id', $site->active_header_id)->first();

            if (!$header) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header layout not found'
                ], 404);
            }

            // Update or create TplSite record
            $tplSite = TplSite::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'tpl_layouts_id' => $header->id
                ],
                [
                    'content' => $request->input('content_data', [])
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Header content updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating header content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get footer content for editing
     */
    public function getFooterContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_footer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active footer found'
                ], 404);
            }

            $footer = TplLayout::where('id', $site->active_footer_id)->first();

            if (!$footer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Footer layout not found'
                ], 404);
            }

            // Get current site configuration content
            $tplSite = TplSite::where('site_id', $site->id)
                ->where('tpl_layouts_id', $footer->id)
                ->first();

            return response()->json([
                'success' => true,
                'footer' => [
                    'id' => $footer->id,
                    'name' => $footer->name,
                    'description' => $footer->description,
                    'configurable_fields' => $footer->configurable_fields,
                    'default_config' => $footer->default_config,
                    'current_content' => $tplSite ? $tplSite->content : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading footer data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update footer content
     */
    public function updateFooterContent(Request $request)
    {
        try {
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site || !$site->active_footer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active footer found'
                ], 404);
            }

            $footer = TplLayout::where('id', $site->active_footer_id)->first();

            if (!$footer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Footer layout not found'
                ], 404);
            }

            // Update or create TplSite record
            $tplSite = TplSite::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'tpl_layouts_id' => $footer->id
                ],
                [
                    'content' => $request->input('content_data', [])
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Footer content updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating footer content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get section content for editing
     */
    public function getSectionContent(Request $request, $pageId, $sectionId)
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

            // Get section with layout
            $section = TplPageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->with('layout')
                ->first();

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found'
                ], 404);
            }

            // Prepare content data with better fallbacks
            $contentData = $section->content_data;
            
            // If content_data is empty, try to get from content field
            if (empty($contentData) && !empty($section->content)) {
                if (is_string($section->content)) {
                    $contentData = json_decode($section->content, true) ?? [];
                } else {
                    $contentData = $section->content;
                }
            }
            
            // If still empty, use default configuration
            if (empty($contentData) && !empty($section->layout->default_config)) {
                $contentData = $section->layout->default_config;
            }

            return response()->json([
                'success' => true,
                'section' => [
                    'id' => $section->id,
                    'name' => $section->name,
                    'content' => $section->content,
                    'content_data' => $contentData,
                    'settings' => $section->settings,
                    'custom_styles' => $section->custom_styles,
                    'custom_scripts' => $section->custom_scripts,
                    'layout' => [
                        'id' => $section->layout->id,
                        'name' => $section->layout->name,
                        'description' => $section->layout->description,
                        'configurable_fields' => $section->layout->configurable_fields,
                        'default_config' => $section->layout->default_config,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getSectionContent: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading section data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update section content
     */
    public function updateSectionContent(Request $request, $pageId, $sectionId)
    {
        try {
            // Debug: Log incoming request data
            Log::info('UpdateSectionContent called', [
                'pageId' => $pageId,
                'sectionId' => $sectionId,
                'data' => $request->all()
            ]);
            
            $user = Auth::user();
            $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            // Get section
            $section = TplPageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->first();

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found'
                ], 404);
            }

            // Update content_data field with the new data
            $contentData = $request->input('content_data', []);
            
            Log::info('Content data to save:', $contentData);

            // Normalize/sync common alias keys (hero_* / cta_* <-> generic)
            $aliases = [
                'title' => ['hero_title'],
                'hero_title' => ['title'],
                'subtitle' => ['hero_description','content','description'],
                'hero_description' => ['subtitle','content','description'],
                'button_text' => ['cta_text'],
                'cta_text' => ['button_text'],
                'button_url' => ['cta_url'],
                'cta_url' => ['button_url']
            ];
            
            foreach ($aliases as $primary => $alts) {
                if (isset($contentData[$primary]) && !empty($contentData[$primary])) {
                    foreach ($alts as $alt) {
                        if (!isset($contentData[$alt]) || empty($contentData[$alt])) {
                            $contentData[$alt] = $contentData[$primary];
                        }
                    }
                }
            }

            // Store in both content_data and content for compatibility
            $section->update([
                'content_data' => $contentData,
                'content' => $contentData // Store as array, will be cast to JSON automatically
            ]);
            
            Log::info('Section updated successfully', [
                'section_id' => $section->id,
                'new_content_data' => $section->fresh()->content_data
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section content updated successfully',
                'content_data' => $section->fresh()->content_data,
                'content' => $section->fresh()->content
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating section content', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating section content: ' . $e->getMessage()
            ], 500);
        }
    }
}
