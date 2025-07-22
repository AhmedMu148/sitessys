<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TplPage;
use App\Models\Site;
use App\Models\ThemePage;
use App\Models\ThemeCategory;
use App\Models\SiteConfig;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $query = TplPage::where('site_id', $site->id)
            ->with(['site', 'sections', 'themePage.category']);
        
        // Filter by theme category
        if ($request->has('theme') && $request->theme !== 'all') {
            $query->whereHas('themePage.category', function($q) use ($request) {
                $q->where('slug', $request->theme);
            });
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }
        
        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $pages = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get theme categories for filter dropdown
        $categories = ThemeCategory::where('status', true)->orderBy('sort_order')->get();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pages' => $pages,
                'categories' => $categories
            ]);
        }
            
        return view('admin.pages.index', compact('pages', 'site', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $themes = ThemePage::with('category')->where('status', true)->orderBy('sort_order')->get();
        $categories = ThemeCategory::where('status', true)->orderBy('sort_order')->get();
        
        return view('admin.pages.create', compact('site', 'themes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'page_theme_id' => 'nullable|exists:theme_pages,id',
            'show_in_nav' => 'boolean',
            'status' => 'boolean',
            'data' => 'array',
            'data.*.title' => 'nullable|string|max:255',
            'data.*.meta' => 'nullable|string|max:500'
        ]);

        $slug = Str::slug($request->name);
        
        // Check for unique slug within the site
        $existingPage = TplPage::where('site_id', $site->id)->where('slug', $slug)->first();
        if ($existingPage) {
            $slug = $slug . '-' . time();
        }

        $page = TplPage::create([
            'site_id' => $site->id,
            'name' => $request->name,
            'slug' => $slug,
            'link' => '/' . $slug,
            'page_theme_id' => $request->page_theme_id,
            'show_in_nav' => $request->boolean('show_in_nav'),
            'status' => $request->boolean('status', true),
            'data' => $request->input('data', [])
        ]);
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        $page->load(['site', 'sections.layout.type']);
        
        return view('admin.pages.show', compact('page', 'site'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }

        $themes = ThemePage::with('category')->where('status', true)->orderBy('sort_order')->get();
        $categories = ThemeCategory::where('status', true)->orderBy('sort_order')->get();
        
        // Get supported languages from site config
        $siteConfig = SiteConfig::where('site_id', $site->id)->first();
        $languages = $siteConfig ? $siteConfig->getSupportedLanguages() : ['en'];
        
        return view('admin.pages.edit', compact('page', 'site', 'themes', 'categories', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'page_theme_id' => 'nullable|exists:theme_pages,id',
            'show_in_nav' => 'boolean',
            'status' => 'boolean',
            'data' => 'array',
            'data.*.title' => 'nullable|string|max:255',
            'data.*.meta' => 'nullable|string|max:500'
        ]);

        $slug = Str::slug($request->name);
        
        // Check for unique slug within the site (excluding current page)
        $existingPage = TplPage::where('site_id', $site->id)
            ->where('slug', $slug)
            ->where('id', '!=', $page->id)
            ->first();
        if ($existingPage) {
            $slug = $slug . '-' . time();
        }

        $page->update([
            'name' => $request->name,
            'slug' => $slug,
            'link' => '/' . $slug,
            'page_theme_id' => $request->page_theme_id,
            'show_in_nav' => $request->boolean('show_in_nav'),
            'status' => $request->boolean('status'),
            'data' => $request->input('data', [])
        ]);
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        // Don't allow deletion of home page
        if ($page->slug === 'home') {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot delete the home page.');
        }
        
        $page->delete();
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * View page in frontend
     */
    public function view(TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        return redirect()->to(url($page->link));
    }
}
