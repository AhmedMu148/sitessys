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
    public function update(Request $request, $pageId, $sectionId)
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
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $section->update([
            'tpl_layouts_id' => $request->tpl_layouts_id,
            'name' => $request->name,
            'content' => $request->input('content', []),
            'custom_styles' => $request->custom_styles,
            'custom_scripts' => $request->custom_scripts,
        ]);
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully!',
                'section' => $section
            ]);
        }
        
        return redirect()->route('admin.pages.sections.index', $pageId)
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
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        $section = TplPageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $section->delete();
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section deleted successfully!');
    }

    /**
     * Upload image for section
     */
    public function uploadImage(Request $request, $pageId, $sectionId)
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
        $request->validate([
            'section_orders' => 'required|array',
            'section_orders.*' => 'integer|exists:tpl_page_sections,id'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Update sort orders
        foreach ($request->section_orders as $index => $sectionId) {
            TplPageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sections reordered successfully.'
        ]);
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
     * Get section content for specific language
     */
    public function getContent($pageId, $sectionId, $language = 'en')
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

        $content = $section->content[$language] ?? [];

        return response()->json([
            'success' => true,
            'content' => $content,
            'section' => [
                'id' => $section->id,
                'name' => $section->name,
                'template' => $section->layout->name
            ]
        ]);
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
