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

        // Get navigation configuration for the modals
        $navigationConfig = $this->getNavigationConfig($site);
        
        // Get social media configuration
        $socialMediaConfig = $this->getSocialMediaConfig($site);
        
        // Get available pages for navigation
        $availablePages = $this->getAvailablePages($site);

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
            // Create a new TplSite record if it doesn't exist
            $tplSite = TplSite::create([
                'site_id' => $site->id,
                'nav_data' => ['links' => [], 'show_auth' => false],
                'footer_data' => ['links' => [], 'show_auth' => false],
            ]);
        }
        
        return [
            'header_links' => $tplSite->nav_data['links'] ?? [],
            'footer_links' => $tplSite->footer_data['links'] ?? [],
            'show_auth_in_header' => $tplSite->nav_data['show_auth'] ?? false,
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

            Log::info('getSectionContent - returning data:', [
                'section_id' => $sectionId,
                'content_data' => $contentData,
                'original_content' => $section->content,
                'layout_name' => $section->layout->name
            ]);

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
            // Debug: Write to file for debugging
            file_put_contents(storage_path('logs/section_update_debug.log'), 
                "[" . date('Y-m-d H:i:s') . "] UpdateSectionContent called\n" .
                "Page ID: $pageId\n" .
                "Section ID: $sectionId\n" .
                "Request Data: " . json_encode($request->all()) . "\n" .
                "Headers: " . json_encode($request->headers->all()) . "\n\n", 
                FILE_APPEND | LOCK_EX
            );
            
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
                'content_data' => $section->content_data,
                'content' => $section->content
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
