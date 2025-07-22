<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\SiteConfig;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['getHeaderTemplates', 'getFooterTemplates']);
    }

    /**
     * Get header templates - returns predefined header templates
     */
    public function getHeaderTemplates()
    {
        try {
            $templates = [
                [
                    'id' => 1,
                    'name' => 'Simple Header',
                    'template_type' => 'simple',
                    'preview_image' => '/img/headers/simple.jpg',
                    'description' => 'Clean header with logo and navigation'
                ],
                [
                    'id' => 2,
                    'name' => 'Centered Header',
                    'template_type' => 'centered',
                    'preview_image' => '/img/headers/centered.jpg',
                    'description' => 'Centered logo with navigation below'
                ],
                [
                    'id' => 3,
                    'name' => 'Modern Header',
                    'template_type' => 'modern',
                    'preview_image' => '/img/headers/modern.jpg',
                    'description' => 'Modern header with hamburger menu'
                ],
                [
                    'id' => 4,
                    'name' => 'Minimal Header',
                    'template_type' => 'minimal',
                    'preview_image' => '/img/headers/minimal.jpg',
                    'description' => 'Minimal header design'
                ]
            ];
            
            return response()->json([
                'success' => true,
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load header templates',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Get footer templates - returns predefined footer templates
     */
    public function getFooterTemplates()
    {
        try {
            $templates = [
                [
                    'id' => 1,
                    'name' => 'Simple Footer',
                    'template_type' => 'simple',
                    'preview_image' => '/img/footers/simple.jpg',
                    'description' => 'Simple footer with links'
                ],
                [
                    'id' => 2,
                    'name' => 'Multi-Column Footer',
                    'template_type' => 'multi-column',
                    'preview_image' => '/img/footers/multi-column.jpg',
                    'description' => 'Footer with multiple columns'
                ],
                [
                    'id' => 3,
                    'name' => 'Centered Footer',
                    'template_type' => 'centered',
                    'preview_image' => '/img/footers/centered.jpg',
                    'description' => 'Centered footer with social links'
                ],
                [
                    'id' => 4,
                    'name' => 'Minimal Footer',
                    'template_type' => 'minimal',
                    'preview_image' => '/img/footers/minimal.jpg',
                    'description' => 'Clean minimal footer'
                ]
            ];
            
            return response()->json([
                'success' => true,
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load footer templates',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Update navigation links - stores in site configuration
     */
    public function updateNavigation(Request $request)
    {
        $request->validate([
            'links' => 'required|array|max:5',
            'links.*.text' => 'required|string|max:50',
            'links.*.url' => 'required|string'
        ]);

        try {
            $site = $request->get('site') ?? app('site');
            
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            // Store navigation in site configuration
            $siteConfig = SiteConfig::firstOrCreate(['site_id' => $site->id]);
            $config = $siteConfig->configuration ?? [];
            $config['navigation'] = [
                'header_links' => $request->links,
                'updated_at' => now()->toISOString()
            ];
            $siteConfig->configuration = $config;
            $siteConfig->save();

            return response()->json([
                'success' => true,
                'message' => 'Navigation updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update navigation',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Update footer links - stores in site configuration
     */
    public function updateFooter(Request $request)
    {
        $request->validate([
            'links' => 'required|array|max:10',
            'links.*.text' => 'required|string|max:50',
            'links.*.url' => 'required|string'
        ]);

        try {
            $site = $request->get('site') ?? app('site');
            
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            // Store footer links in site configuration
            $siteConfig = SiteConfig::firstOrCreate(['site_id' => $site->id]);
            $config = $siteConfig->configuration ?? [];
            $config['navigation'] = $config['navigation'] ?? [];
            $config['navigation']['footer_links'] = $request->links;
            $config['navigation']['updated_at'] = now()->toISOString();
            $siteConfig->configuration = $config;
            $siteConfig->save();

            return response()->json([
                'success' => true,
                'message' => 'Footer updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update footer',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Get navigation links for a position
     */
    public function getNavigationLinks(Request $request, $position = 'header')
    {
        try {
            $site = $request->get('site') ?? app('site') ?? null;
            
            if (!$site) {
                return response()->json([
                    'success' => true,
                    'links' => []
                ]);
            }
            
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            if (!$siteConfig || !isset($siteConfig->configuration['navigation'])) {
                return response()->json([
                    'success' => true,
                    'links' => []
                ]);
            }
            
            $key = $position === 'footer' ? 'footer_links' : 'header_links';
            $links = $siteConfig->configuration['navigation'][$key] ?? [];
            
            return response()->json([
                'success' => true,
                'links' => $links
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load navigation links',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Display the navigation index page
     */
    public function index()
    {
        try {
            $site = request()->get('site') ?? app('site') ?? null;
            
            $siteConfig = $site ? SiteConfig::where('site_id', $site->id)->first() : null;
            $navigationConfig = $siteConfig ? $siteConfig->configuration['navigation'] ?? [] : [];
            
            return response()->json([
                'success' => true,
                'navigation' => $navigationConfig
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load navigation configuration',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Preview a template
     */
    public function previewTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer',
            'template_type' => 'required|string|in:header,footer'
        ]);

        try {
            return response()->json([
                'success' => true,
                'preview_url' => "/templates/preview/{$request->template_type}/{$request->template_id}",
                'message' => 'Template preview generated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate template preview',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Reset navigation to defaults
     */
    public function resetToDefaults(Request $request)
    {
        try {
            $site = $request->get('site') ?? app('site');
            
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            // Default navigation configuration
            $defaultNavigation = [
                'header_links' => [
                    ['text' => 'Home', 'url' => '/'],
                    ['text' => 'About', 'url' => '/about'],
                    ['text' => 'Services', 'url' => '/services'],
                    ['text' => 'Contact', 'url' => '/contact']
                ],
                'footer_links' => [
                    ['text' => 'Privacy Policy', 'url' => '/privacy'],
                    ['text' => 'Terms of Service', 'url' => '/terms'],
                    ['text' => 'Support', 'url' => '/support']
                ],
                'updated_at' => now()->toISOString()
            ];

            // Update site configuration
            $siteConfig = SiteConfig::firstOrCreate(['site_id' => $site->id]);
            $config = $siteConfig->configuration ?? [];
            $config['navigation'] = $defaultNavigation;
            $siteConfig->configuration = $config;
            $siteConfig->save();

            return response()->json([
                'success' => true,
                'message' => 'Navigation reset to defaults successfully',
                'navigation' => $defaultNavigation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to reset navigation',
                'message' => app()->environment('local') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
}
