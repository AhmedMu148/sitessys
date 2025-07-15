<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use App\Models\PageSection;
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
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $sections = PageSection::where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->with('layout.type')
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.pages.sections.index', compact('page', 'sections', 'site'));
    }
    
    /**
     * Show the form for creating a new section
     */
    public function create($pageId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        // Get available section layouts for this site
        $sectionType = TplLayoutType::where('name', 'section')->first();
        $availableLayouts = TplLayout::where('site_id', $site->id)
            ->where('type_id', $sectionType->id)
            ->where('is_active', true)
            ->get();
        
        return view('admin.pages.sections.create', compact('page', 'availableLayouts', 'site'));
    }
    
    /**
     * Store a newly created section in storage
     */
    public function store(Request $request, $pageId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'layout_id' => 'required|exists:tpl_layouts,id',
            'content_data' => 'nullable|json',
            'settings' => 'nullable|json',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        // Get next sort order
        $maxSortOrder = PageSection::where('page_id', $pageId)->max('sort_order') ?? 0;
        
        $section = PageSection::create([
            'page_id' => $pageId,
            'layout_id' => $request->layout_id,
            'site_id' => $site->id,
            'name' => $request->name,
            'is_active' => true,
            'sort_order' => $maxSortOrder + 1,
            'content_data' => $request->content_data ? json_decode($request->content_data, true) : null,
            'settings' => $request->settings ? json_decode($request->settings, true) : null,
        ]);
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section created successfully!');
    }
    
    /**
     * Show the form for editing the specified section
     */
    public function edit($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section = PageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        // Get available section layouts
        $sectionType = TplLayoutType::where('name', 'section')->first();
        $availableLayouts = TplLayout::where('site_id', $site->id)
            ->where('type_id', $sectionType->id)
            ->where('is_active', true)
            ->get();
        
        return view('admin.pages.sections.edit', compact('page', 'section', 'availableLayouts', 'site'));
    }
    
    /**
     * Update the specified section in storage
     */
    public function update(Request $request, $pageId, $sectionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'layout_id' => 'required|exists:tpl_layouts,id',
            'content_data' => 'nullable|json',
            'settings' => 'nullable|json',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $section = PageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section->update([
            'name' => $request->name,
            'layout_id' => $request->layout_id,
            'content_data' => $request->content_data ? json_decode($request->content_data, true) : null,
            'settings' => $request->settings ? json_decode($request->settings, true) : null,
        ]);
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section updated successfully!');
    }
    
    /**
     * Toggle section active status
     */
    public function toggleActive($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $section = PageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section->update(['is_active' => !$section->is_active]);
        
        $status = $section->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Section {$status} successfully!");
    }
    
    /**
     * Update sections order
     */
    public function updateOrder(Request $request, $pageId)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.sort_order' => 'required|integer',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        foreach ($request->sections as $sectionData) {
            PageSection::where('id', $sectionData['id'])
                ->where('page_id', $pageId)
                ->where('site_id', $site->id)
                ->update(['sort_order' => $sectionData['sort_order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Remove the specified section from storage
     */
    public function destroy($pageId, $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $section = PageSection::where('id', $sectionId)
            ->where('page_id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();
        
        $section->delete();
        
        return redirect()->route('admin.pages.sections.index', $pageId)
            ->with('success', 'Section deleted successfully!');
    }
}
