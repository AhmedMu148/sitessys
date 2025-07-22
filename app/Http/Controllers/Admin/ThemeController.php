<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\ThemeCategory;
use App\Models\ThemePage;
use App\Models\TplPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Get all theme categories
     */
    public function getCategories()
    {
        $categories = ThemeCategory::where('status', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'description', 'icon']);

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Filter pages by theme category
     */
    public function filterPagesByTheme(Request $request, $categorySlug = null)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $query = TplPage::where('site_id', $site->id)
            ->with(['site', 'sections', 'themePage.category']);

        // Filter by theme category if specified
        if ($categorySlug && $categorySlug !== 'all') {
            $category = ThemeCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->whereHas('themePage.category', function($q) use ($category) {
                    $q->where('id', $category->id);
                });
            }
        }

        // Additional filters
        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = ThemeCategory::where('status', true)->orderBy('sort_order')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pages' => $pages,
                'categories' => $categories,
                'current_category' => $categorySlug
            ]);
        }

        return view('admin.pages.index', compact('pages', 'site', 'categories'));
    }

    /**
     * Get theme pages for a specific category
     */
    public function getThemePages($categoryId)
    {
        $themePages = ThemePage::where('category_id', $categoryId)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'description', 'preview_image', 'category_id']);

        return response()->json([
            'success' => true,
            'theme_pages' => $themePages
        ]);
    }

    /**
     * Preview theme for a page
     */
    public function previewPageTheme(Request $request, $pageId)
    {
        $request->validate([
            'theme_id' => 'required|exists:theme_pages,id'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $themePage = ThemePage::with('category')->findOrFail($request->theme_id);

        // Generate preview URL or data
        $previewData = $this->generateThemePreview($page, $themePage);

        return response()->json([
            'success' => true,
            'preview' => $previewData,
            'theme' => [
                'id' => $themePage->id,
                'name' => $themePage->name,
                'category' => $themePage->category->name,
                'preview_image' => $themePage->preview_image
            ]
        ]);
    }

    /**
     * Apply theme to a page
     */
    public function applyTheme(Request $request, $pageId)
    {
        $request->validate([
            'theme_id' => 'required|exists:theme_pages,id'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $page = TplPage::where('id', $pageId)
            ->where('site_id', $site->id)
            ->firstOrFail();

        $themePage = ThemePage::findOrFail($request->theme_id);

        // Update page theme
        $page->update([
            'page_theme_id' => $themePage->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Theme applied successfully.',
            'page' => [
                'id' => $page->id,
                'name' => $page->name,
                'theme' => [
                    'id' => $themePage->id,
                    'name' => $themePage->name
                ]
            ]
        ]);
    }

    /**
     * Get theme statistics
     */
    public function getThemeStats()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $stats = [];
        $categories = ThemeCategory::where('status', true)->get();

        foreach ($categories as $category) {
            $pageCount = TplPage::where('site_id', $site->id)
                ->whereHas('themePage.category', function($q) use ($category) {
                    $q->where('id', $category->id);
                })
                ->count();

            $stats[] = [
                'category' => $category->name,
                'slug' => $category->slug,
                'page_count' => $pageCount,
                'color' => $this->getCategoryColor($category->slug)
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Generate theme preview data
     */
    protected function generateThemePreview($page, $themePage)
    {
        // This would generate actual preview data
        // For now, return basic preview information
        return [
            'preview_url' => route('home') . '?preview=1&theme=' . $themePage->id,
            'preview_image' => $themePage->preview_image,
            'layout_changes' => [
                'header_style' => $themePage->header_style ?? 'default',
                'color_scheme' => $themePage->color_scheme ?? 'default',
                'layout_style' => $themePage->layout_style ?? 'default'
            ]
        ];
    }

    /**
     * Get color for theme category
     */
    protected function getCategoryColor($slug)
    {
        $colors = [
            'business' => '#007bff',
            'portfolio' => '#28a745',
            'ecommerce' => '#ffc107',
            'seo-services' => '#dc3545',
            'blog' => '#6f42c1',
            'landing' => '#fd7e14',
            'default' => '#6c757d'
        ];

        return $colors[$slug] ?? $colors['default'];
    }

    /**
     * Bulk update page themes
     */
    public function bulkUpdateThemes(Request $request)
    {
        $request->validate([
            'page_ids' => 'required|array',
            'page_ids.*' => 'exists:tpl_pages,id',
            'theme_id' => 'required|exists:theme_pages,id'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $updatedCount = TplPage::whereIn('id', $request->page_ids)
            ->where('site_id', $site->id)
            ->update(['page_theme_id' => $request->theme_id]);

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} pages updated successfully.",
            'updated_count' => $updatedCount
        ]);
    }
}
