<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplDesign;
use App\Models\TplLang;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'pages' => TplPage::count(),
            'layouts' => TplLayout::count(),
            'designs' => TplDesign::count(),
            'languages' => TplLang::count(),
        ];

        // Get recent pages
        $recentPages = TplPage::latest()->take(5)->get();

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

        return view('admin.dashboard', compact('stats', 'recentPages', 'recentUpdates'));
    }
}
