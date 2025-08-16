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

        // Get recent pages and sites for dashboard
        $recentPages = TplPage::with('site')->latest()->take(5)->get();
        // Prepare a simple array for blade to avoid null-property access in compiled views and include URLs
        $recentPagesSimple = $recentPages->map(function ($p) {
            return [
                'name' => $p->name ?? '—',
                'site' => optional($p->site)->name ?? '—',
                'updated_human' => $p->updated_at ? $p->updated_at->diffForHumans() : ($p->created_at ? $p->created_at->diffForHumans() : '—'),
                'url' => route('admin.pages.edit', $p->id),
            ];
        })->toArray();
        $recentSites = Site::with('user')->latest()->take(5)->get();

        // Build recent updates from real records (pages + sites) and fall back to a small simulated list
        $updates = [];

        foreach ($recentPages as $p) {
            $updates[] = [
                'icon' => 'file-text',
                'title' => $p->name,
                'description' => 'Page updated on "' . (optional($p->site)->name ?? 'Unknown') . '"',
                'timestamp' => $p->updated_at ?? $p->created_at,
                'url' => route('admin.pages.edit', $p->id),
            ];
        }

        foreach ($recentSites as $s) {
            $updates[] = [
                'icon' => 'globe',
                'title' => $s->name,
                'description' => 'Site created by ' . (optional($s->user)->name ?? 'system'),
                'timestamp' => $s->created_at,
                'url' => route('admin.sites.edit', $s->id),
            ];
        }

        // If there are no updates from DB, provide a minimal simulated feed
        if (empty($updates)) {
            $updates = [
                ['icon' => 'file-text', 'title' => 'Home Page Updated', 'description' => 'Hero section content has been modified', 'timestamp' => now()->subMinutes(2)],
                ['icon' => 'layout', 'title' => 'New Layout Created', 'description' => 'Features layout has been added', 'timestamp' => now()->subHour()],
            ];
        }

        // Normalize and sort updates by timestamp desc, then format time for display
        usort($updates, function ($a, $b) {
            return $b['timestamp']->getTimestamp() <=> $a['timestamp']->getTimestamp();
        });

        $recentUpdates = array_map(function ($u) {
            return [
                'icon' => $u['icon'],
                'title' => $u['title'],
                'description' => $u['description'],
                'time' => $u['timestamp']->diffForHumans(),
                'url' => $u['url'] ?? null,
            ];
        }, array_slice($updates, 0, 6));

        // Monthly activity for the line chart: pages created per month this year
        $monthlyActivity = [];
        $year = now()->year;
        for ($m = 1; $m <= 12; $m++) {
            $monthlyActivity[] = TplPage::whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
        }

    return view('admin.dashboard', compact('stats', 'recentPagesSimple', 'recentPages', 'recentSites', 'recentUpdates', 'monthlyActivity'));
    }
}
