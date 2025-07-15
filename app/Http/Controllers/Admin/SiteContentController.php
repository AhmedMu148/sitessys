<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\PageSection;
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
        // Get current authenticated user
        $user = auth()->user();
        
        // If no authenticated user, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get user's site or create a default one
        $site = Site::where('user_id', $user->id)->where('status', true)->first();
        
        if (!$site) {
            // Create a default site for the user
            $site = Site::create([
                'user_id' => $user->id,
                'site_name' => $user->name . "'s Site",
                'domain' => null,
                'status' => true
            ]);
        }
        
        $stats = [
            'pages' => TplPage::where('site_id', $site->id)->count(),
            'sections' => PageSection::where('site_id', $site->id)->count(),
            'config_items' => SiteConfig::where('site_id', $site->id)->count(),
        ];
        
        $recentPages = TplPage::where('site_id', $site->id)->latest()->take(5)->get();
        
        return view('admin.site-content.index', compact('site', 'stats', 'recentPages'));
    }

    /**
     * Show site configuration
     */
    public function config()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $site = Site::where('user_id', $user->id)->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')->with('error', 'No site found. Please set up your site first.');
        }
        
        $config = SiteConfig::where('site_id', $site->id)->first();
        
        return view('admin.site-content.config', compact('site', 'config'));
    }

    /**
     * Update site configuration
     */
    public function updateConfig(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $site = Site::where('user_id', $user->id)->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')->with('error', 'No site found. Please set up your site first.');
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
