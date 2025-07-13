<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplSection;
use App\Models\SiteConfig;
use App\Models\TplSite;
use App\Models\TplLayout;

class SiteContentController extends Controller
{
    /**
     * Show the site content management dashboard
     */
    public function index()
    {
        // For now, get the first admin's site
        // Later this will be based on authenticated user
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : null;
        
        if (!$site) {
            return redirect()->route('admin.sites.create')->with('error', 'No site found. Please create a site first.');
        }
        
        $stats = [
            'pages' => TplPage::where('site_id', $site->id)->count(),
            'sections' => TplSection::where('site_id', $site->id)->count(),
            'config_items' => SiteConfig::where('site_id', $site->id)->count(),
        ];
        
        $recentPages = TplPage::where('site_id', $site->id)->latest()->take(5)->get();
        
        return view('admin.site-content.index', compact('site', 'stats', 'recentPages'));
    }

    /**
     * Show pages management
     */
    public function pages()
    {
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : null;
        
        if (!$site) {
            return redirect()->route('admin.sites.create')->with('error', 'No site found.');
        }
        
        $pages = TplPage::where('site_id', $site->id)->paginate(10);
        
        return view('admin.site-content.pages', compact('site', 'pages'));
    }

    /**
     * Show sections management
     */
    public function sections()
    {
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : null;
        
        if (!$site) {
            return redirect()->route('admin.sites.create')->with('error', 'No site found.');
        }
        
        $sections = TplSection::where('site_id', $site->id)->paginate(10);
        
        return view('admin.site-content.sections', compact('site', 'sections'));
    }

    /**
     * Show site configuration
     */
    public function config()
    {
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : null;
        
        if (!$site) {
            return redirect()->route('admin.sites.create')->with('error', 'No site found.');
        }
        
        $config = SiteConfig::where('site_id', $site->id)->first();
        
        return view('admin.site-content.config', compact('site', 'config'));
    }

    /**
     * Update site configuration
     */
    public function updateConfig(Request $request)
    {
        $user = \App\Models\User::where('role', 'admin')->first();
        $site = $user ? $user->primarySite() : null;
        
        if (!$site) {
            return redirect()->route('admin.sites.create')->with('error', 'No site found.');
        }
        
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'keyword' => 'required|string',
        ]);
        
        $config = SiteConfig::updateOrCreate(
            ['site_id' => $site->id],
            [
                'data' => [
                    'title' => $request->title,
                    'description' => $request->description,
                    'keyword' => $request->keyword,
                    'logo' => $request->logo ?? '/logo.png',
                    'favicon' => $request->favicon ?? '/favicon.ico',
                ],
                'lang_id' => '1,2'
            ]
        );
        
        return redirect()->route('admin.site-content.config')->with('success', 'Site configuration updated successfully!');
    }
}
