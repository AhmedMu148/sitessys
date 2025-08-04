<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplPageSection;
use App\Models\SiteConfig;
use App\Models\SiteImgMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PageSectionController extends Controller
{
    /**
     * Display page sections for a specific page
     */
    public function index($pageId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $sections = TplPageSection::where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->with('layout')
            ->orderBy('sort_order')
            ->get();

        $templates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.pages.sections.index', compact('page', 'sections', 'site', 'templates'));
    }
    
    /**
     * Show the form for creating a new section
     */
    public function create($pageId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $templates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.pages.sections.create', compact('page', 'templates', 'site'));
    }
    
    /**
     * Store a newly created section in storage
     */
    public function store(Request $request, $pageId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tpl_layouts_id' => 'required|exists:tpl_layouts,id',
            'content' => 'nullable|array',
            'custom_styles' => 'nullable|string',
            'custom_scripts' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        // Get next sort order
        $maxSortOrder = TplPageSection::where('page_id', $pageId)->max('sort_order') ?? 0;
        
        $section = TplPageSection::create([
            'page_id' => $pageId,
            'site_id' => $site->id,
            'tpl_layouts_id' => $request->tpl_layouts_id,
            'name' => $request->name,
            'content' => $request->input('content', []),
            'custom_styles' => $request->custom_styles,
            'custom_scripts' => $request->custom_scripts,
            'status' => true,
            'sort_order' => $maxSortOrder + 1,
        ]);
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section created successfully!',
                'section' => $section
            ]);
        }
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section created successfully!');
    }
    
    /**
     * Show the form for editing the specified section
     */
    public function edit($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->with('layout')
            ->firstOrFail();

        $templates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        // Get supported languages from site config
        $siteConfig = SiteConfig::where('site_id', $site->id)->first();
        $languages = $siteConfig ? $siteConfig->getSupportedLanguages() : ['en'];
        
        return view('admin.pages.sections.edit', compact('page', 'section', 'templates', 'site', 'languages'));
    }
    
    /**
     * Update the specified section in storage
     */
    public function update(Request $request, $page_id, $section_id)
    {
        $validation = [
            'tpl_layouts_id' => 'sometimes|exists:tpl_layouts,id',
            'name' => 'sometimes|string|max:255',
            'content' => 'nullable|array',
            'content_data' => 'nullable|json',
            'settings' => 'nullable|json',
            'custom_styles' => 'nullable|string',
            'custom_scripts' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
        
        $request->validate($validation);
        
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $section = TplPageSection::where('id', $section_id)
            ->where('page_id', $page_id)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Prepare update data
        $updateData = [];
        
        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }
        
        if ($request->has('tpl_layouts_id')) {
            $updateData['tpl_layouts_id'] = $request->tpl_layouts_id;
        }
        
        if ($request->has('content')) {
            $updateData['content'] = $request->input('content', []);
        }
        
        if ($request->has('content_data')) {
            $updateData['content_data'] = json_decode($request->content_data, true);
        }
        
        if ($request->has('settings')) {
            $updateData['settings'] = json_decode($request->settings, true);
        }
        
        if ($request->has('custom_styles')) {
            $updateData['custom_styles'] = $request->custom_styles;
        }
        
        if ($request->has('custom_scripts')) {
            $updateData['custom_scripts'] = $request->custom_scripts;
        }

        // Handle image uploads
        $contentData = $updateData['content_data'] ?? $section->content_data ?? [];
        
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $imagePath = $mainImage->store('sections/images', 'public');
            $contentData['main_image'] = '/storage/' . $imagePath;
        }
        
        if ($request->hasFile('background_image')) {
            $bgImage = $request->file('background_image');
            $bgImagePath = $bgImage->store('sections/backgrounds', 'public');
            $contentData['background_image'] = '/storage/' . $bgImagePath;
        }
        
        if (!empty($contentData)) {
            $updateData['content_data'] = $contentData;
        }

        $section->update($updateData);
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully!',
                'section' => $section
            ]);
        }
        
        return redirect()->route('admin.pages.sections.index', $page_id)
            ->with('success', 'Section updated successfully!');
    }

    /**
     * Toggle section status (active/inactive)
     */
    public function toggle($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $section->update(['status' => !$section->status]);
        
        $message = $section->status ? 'Section activated successfully!' : 'Section deactivated successfully!';
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', $message);
    }

    /**
     * Show section order form
     */
    public function order($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $allSections = TplPageSection::where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.pages.sections.order', compact('page', 'section', 'allSections', 'site'));
    }

    /**
     * Update section order
     */
    public function updateOrder(Request $request, $pageId, $sectionId)
    {
        $request->validate([
            'sort_order' => 'required|integer|min:0'
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $section->update(['sort_order' => $request->sort_order]);
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section order updated successfully!');
    }
    
    /**
     * Delete the specified section
     */
    public function destroy(Request $request, $pageId, $sectionId)
    {
        try {
            // Find the section
            $section = TplPageSection::find($sectionId);
            
            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found.'
                ], 404);
            }
            
            // Instead of setting page_id to null, we'll delete the section completely
            // Or we can move it to a "deleted" status while keeping it in the database
            $section->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Section removed from page successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section removed from page successfully!');
    }

    /**
     * Get available sections that can be added to the page
     */
    public function getAvailableSections(Request $request, $pageId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Get sections that belong to this site but not to this page (soft deleted from page)
        $availableSections = TplPageSection::where('site_id', $site->id)
            ->where(function($query) use ($pageId) {
                $query->whereNull('page_id')
                      ->orWhere('page_id', '!=', $pageId);
            })
            ->with('layout')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $availableSections
        ]);
    }

    /**
     * Restore a section to the page
     */
    public function restoreToPage(Request $request, $pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $section = TplPageSection::where('id', $sectionId)
            ->where('site_id', $site->id)
            ->whereNull('page_id') // Only restore sections that are not assigned to any page
            ->firstOrFail();

        // Get next sort order for this page
        $maxSortOrder = TplPageSection::where('page_id', $pageId)->max('sort_order') ?? 0;

        // Restore section to this page
        $section->update([
            'page_id' => $pageId,
            'status' => true,
            'sort_order' => $maxSortOrder + 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section restored to page successfully!',
            'section' => $section
        ]);
    }

    /**
     * Upload image for section
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Add image to media collection
        $media = $section->addMedia($request->file('image'))
            ->toMediaCollection('section_images');

        // Update section content with image URL
        $locale = app()->getLocale();
        $content = $section->content;
        if (!isset($content[$locale])) {
            $content[$locale] = [];
        }
        $content[$locale]['image'] = $media->getUrl();
        $section->update(['content' => $content]);

        // Create or update site_img_media record
        SiteImgMedia::updateOrCreate(
            [
                'site_id' => $site->id,
                'section_id' => $section->id
            ],
            [
                'max_files' => 10,
                'allowed_types' => ['image/*']
            ]
        );

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }

    /**
     * Reorder sections for a page
     */
    public function reorder(Request $request, $pageId)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Reorder request received', [
                'page_id' => $pageId,
                'section_orders' => $request->input('section_orders', [])
            ]);
            
            // Check if we're receiving section_orders as array of objects or array of IDs
            $sectionOrders = $request->input('section_orders', []);
            
            if (empty($sectionOrders)) {
                return response()->json(['error' => 'No section orders provided.'], 400);
            }
            
            // Handle both formats: array of objects with id/order or simple array of IDs
            foreach ($sectionOrders as $orderData) {
                if (is_array($orderData) && isset($orderData['id']) && isset($orderData['order'])) {
                    // Format: [{"id": 1, "order": 2}, {"id": 2, "order": 1}]
                    $sectionId = $orderData['id'];
                    $newOrder = $orderData['order'];
                    
                    // Log each update
                    Log::info('Updating section order', [
                        'section_id' => $sectionId,
                        'new_order' => $newOrder,
                        'page_id' => $pageId
                    ]);
                    
                    // Update the section order
                    $updated = TplPageSection::where('id', $sectionId)
                        ->where('page_id', $pageId)
                        ->update(['sort_order' => $newOrder]);
                        
                    Log::info('Section update result', ['updated_rows' => $updated]);
                } else {
                    // Format: [1, 2, 3] (section IDs in order)
                    Log::error('Invalid section order format', ['order_data' => $orderData]);
                    return response()->json(['error' => 'Invalid section order format.'], 400);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Sections reordered successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Reorder error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update section content with language support
     */
    public function updateContent(Request $request, $pageId, $sectionId)
    {
        $request->validate([
            'language' => 'required|string|size:2',
            'content' => 'required|array',
            'content.title' => 'nullable|string|max:255',
            'content.description' => 'nullable|string',
            'content.image' => 'nullable|string|max:255',
            'content.button_text' => 'nullable|string|max:100',
            'content.button_url' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Get current content
        $allContent = $section->content ?? [];
        
        // Update content for the specified language
        $allContent[$request->language] = $request->content;
        
        $section->update(['content' => $allContent]);

        return response()->json([
            'success' => true,
            'message' => 'Section content updated successfully.',
            'content' => $allContent[$request->language]
        ]);
    }

    /**
     * Toggle section status (active/inactive)
     */
    public function toggleStatus(Request $request, $pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $section->update(['status' => !$section->status]);

        return response()->json([
            'success' => true,
            'message' => 'Section status updated successfully.',
            'status' => $section->status
        ]);
    }

    /**
     * Duplicate a section
     */
    public function duplicate(Request $request, $pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $originalSection = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Get next sort order
        $maxSortOrder = TplPageSection::where('page_id', $pageId)->max('sort_order') ?? 0;

        // Create duplicate
        $duplicatedSection = TplPageSection::create([
            'page_id' => $pageId,
            'site_id' => $site->id,
            'tpl_layouts_id' => $originalSection->tpl_layouts_id,
            'name' => $originalSection->name . ' (Copy)',
            'content' => $originalSection->content,
            'custom_styles' => $originalSection->custom_styles,
            'custom_scripts' => $originalSection->custom_scripts,
            'status' => $originalSection->status,
            'sort_order' => $maxSortOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section duplicated successfully.',
            'section' => [
                'id' => $duplicatedSection->id,
                'name' => $duplicatedSection->name,
                'sort_order' => $duplicatedSection->sort_order
            ]
        ]);
    }

    /**
     * Get section content for specific language and editing modal
     */
    public function getContent($page_id, $section_id, $language = 'en')
    {
        try {
            $user = Auth::user();
            $site = $user->sites()->where('status_id', true)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found.'
                ], 404);
            }

            $section = TplPageSection::where('id', $section_id)
                ->where('page_id', $page_id)
                ->where('site_id', $site->id)
                ->with(['layout' => function($query) {
                    $query->select('*');
                }])
                ->firstOrFail();

            // Enhanced section data for the advanced modal
            $sectionData = [
                'id' => $section->id,
                'name' => $section->name,
                'tpl_layouts_id' => $section->tpl_layouts_id,
                'content' => $section->content,
                'content_data' => $section->content_data,
                'settings' => $section->settings,
                'custom_styles' => $section->custom_styles,
                'custom_scripts' => $section->custom_scripts,
                'status' => $section->status,
                'sort_order' => $section->sort_order,
                'layout' => [
                    'id' => $section->layout->id,
                    'name' => $section->layout->name,
                    'tpl_id' => $section->layout->tpl_id,
                    'preview_image' => $section->layout->preview_image,
                    'configurable_fields' => $section->layout->configurable_fields,
                    'default_config' => $section->layout->default_config,
                    'path' => $section->layout->path
                ]
            ];

            return response()->json([
                'success' => true,
                'section' => $sectionData,
                'content' => $section->content[$language] ?? [],
                'message' => 'Section data loaded successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load section data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export section configuration
     */
    public function exportSection($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->with('layout')
            ->firstOrFail();

        $exportData = [
            'name' => $section->name,
            'template_id' => $section->layout->tpl_id,
            'content' => $section->content,
            'custom_styles' => $section->custom_styles,
            'custom_scripts' => $section->custom_scripts,
            'exported_at' => now()->toISOString(),
            'version' => '1.0'
        ];

        return response()->json([
            'success' => true,
            'export_data' => $exportData
        ]);
    }

    /**
     * Preview a section
     */
    public function preview($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->with(['layout', 'page'])
            ->firstOrFail();

        $page = $section->page;

        return view('admin.pages.sections.preview', compact('section', 'page', 'site'));
    }
}
