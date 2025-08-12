<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use App\Models\TplSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
