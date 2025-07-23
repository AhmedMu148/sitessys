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

        // Load sections with their layouts for preview images
        $page->load(['sections.layout']);

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
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to page.'], 403);
            }
            abort(403, 'Unauthorized access to page.');
        }
        
        // Don't allow deletion of home page
        if ($page->slug === 'home') {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete the home page.'], 400);
            }
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot delete the home page.');
        }

        try {
            // Store page name for success message
            $pageName = $page->name;

            // Delete associated sections first
            $page->sections()->delete();

            // Delete the page
            $page->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Page '{$pageName}' has been deleted successfully."
                ]);
            }

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Page deletion failed: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete page. Please try again.'
                ], 500);
            }

            return redirect()->route('admin.pages.index')
                ->with('error', 'Failed to delete page. Please try again.');
        }
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

    /**
     * Toggle page status (Active/Inactive)
     */
    public function toggleStatus(Request $request, TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to page.'], 403);
        }
        
        $page->update(['status' => !$page->status]);
        
        return response()->json([
            'success' => true, 
            'message' => $page->status ? 'Page activated successfully.' : 'Page deactivated successfully.',
            'status' => $page->status
        ]);
    }

    /**
     * Toggle page display in navigation
     */
    public function toggleNav(Request $request, TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to page.'], 403);
        }
        
        // Check navigation limit (max 5 pages)
        if (!$page->show_in_nav) {
            $navCount = TplPage::where('site_id', $site->id)->where('show_in_nav', true)->count();
            if ($navCount >= 5) {
                return response()->json(['success' => false, 'message' => 'Navigation menu is limited to 5 pages maximum.'], 400);
            }
        }
        
        $page->update(['show_in_nav' => !$page->show_in_nav]);
        
        return response()->json([
            'success' => true, 
            'message' => $page->show_in_nav ? 'Page added to navigation.' : 'Page removed from navigation.',
            'show_in_nav' => $page->show_in_nav
        ]);
    }

    /**
     * Toggle page display in footer
     */
    public function toggleFooter(Request $request, TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to page.'], 403);
        }
        
        // Get current footer links from site config
        $siteConfig = SiteConfig::where('site_id', $site->id)->first();
        $footerLinks = $siteConfig ? ($siteConfig->footer_links ?? []) : [];
        
        $pageInFooter = in_array($page->id, array_column($footerLinks, 'page_id'));
        
        if (!$pageInFooter) {
            // Check footer limit (max 10 links)
            if (count($footerLinks) >= 10) {
                return response()->json(['success' => false, 'message' => 'Footer is limited to 10 links maximum.'], 400);
            }
            
            // Add to footer
            $footerLinks[] = [
                'page_id' => $page->id,
                'title' => $page->name,
                'url' => $page->link,
                'order' => count($footerLinks) + 1
            ];
            
            $message = 'Page added to footer.';
        } else {
            // Remove from footer
            $footerLinks = array_filter($footerLinks, function($link) use ($page) {
                return $link['page_id'] !== $page->id;
            });
            $footerLinks = array_values($footerLinks); // Reset array keys
            
            $message = 'Page removed from footer.';
        }
        
        // Update site config
        if (!$siteConfig) {
            SiteConfig::create([
                'site_id' => $site->id,
                'footer_links' => $footerLinks
            ]);
        } else {
            $siteConfig->update(['footer_links' => $footerLinks]);
        }
        
        return response()->json([
            'success' => true, 
            'message' => $message,
            'in_footer' => !$pageInFooter
        ]);
    }
}
