<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplLang;
use App\Models\ThemeCategory;
use App\Models\Site;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'sites' => Site::count(),
            'users' => User::count(),
            'pages' => TplPage::count(),
            'layouts' => TplLayout::count(),
            'designs' => ThemeCategory::count(), // Use categories as design count
            'languages' => TplLang::count(),
        ];

        // Get recent pages for dashboard
        $recentPages = TplPage::with('site')->latest()->take(5)->get();

        // Get recent sites
        $recentSites = Site::with('user')->latest()->take(5)->get();

        // Get recent updates (simulated data)
        $recentUpdates = [
            [
                'icon' => 'file-text',
                'title' => 'Home Page Updated',
                'description' => 'Hero section content has been modified',
                'time' => '2 minutes ago'
            ],
            [
                'icon' => 'layout',
                'title' => 'New Layout Created',
                'description' => 'Features layout has been added',
                'time' => '1 hour ago'
            ],
            [
                'icon' => 'grid',
                'title' => 'Design Modified',
                'description' => 'Contact form design updated',
                'time' => '3 hours ago'
            ],
            [
                'icon' => 'settings',
                'title' => 'Site Configuration',
                'description' => 'Language settings updated',
                'time' => '1 day ago'
            ],
        ];

        return view('admin.dashboard', compact('stats', 'recentPages', 'recentSites', 'recentUpdates'));
    }
}
