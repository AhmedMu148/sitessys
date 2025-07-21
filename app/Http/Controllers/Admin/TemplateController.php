<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplSite;
use App\Models\TplLayout;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $layouts = TplLayout::where('status', true)
            ->orderBy('layout_type')
            ->orderBy('sort_order')
            ->paginate(10);
            
        return view('admin.templates.index', compact('layouts', 'site'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        return view('admin.templates.create', compact('site'));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'layout_type' => 'required|in:header,footer,section',
            'content' => 'required|string',
        ]);

        TplLayout::create([
            'tpl_id' => strtolower(str_replace(' ', '_', $request->name)),
            'layout_type' => $request->layout_type,
            'name' => $request->name,
            'description' => $request->description,
            'content' => ['html' => $request->content],
            'status' => true,
            'sort_order' => TplLayout::where('layout_type', $request->layout_type)->count() + 1,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified template
     */
    public function show(TplLayout $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(TplLayout $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, TplLayout $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'layout_type' => 'required|in:header,footer,section',
            'content' => 'required|string',
        ]);

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => ['html' => $request->content],
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified template
     */
    public function destroy(TplLayout $template)
    {
        $template->delete();
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Show navigation settings
     */
    public function navEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $headers = TplLayout::where('layout_type', 'header')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        return view('admin.templates.nav', compact('site', 'headers', 'tplSite'));
    }

    /**
     * Update navigation settings
     */
    public function navUpdate(Request $request)
    {
        $request->validate([
            'active_header_id' => 'nullable|exists:tpl_layouts,id',
            'links' => 'array|max:3',
            'links.*.url' => 'required_with:links.*.label|string',
            'links.*.label' => 'required_with:links.*.url|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site active header
        if ($request->active_header_id) {
            $site->update(['active_header_id' => $request->active_header_id]);
        }

        // Update navigation links
        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        $links = array_filter($request->input('links', []), function($link) {
            return !empty($link['url']) && !empty($link['label']);
        });

        $tplSite->update([
            'nav_data' => ['links' => array_values($links)]
        ]);

        return redirect()->back()->with('success', 'Navigation settings updated successfully.');
    }

    /**
     * Show footer settings
     */
    public function footerEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $footers = TplLayout::where('layout_type', 'footer')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        return view('admin.templates.footer', compact('site', 'footers', 'tplSite'));
    }

    /**
     * Update footer settings
     */
    public function footerUpdate(Request $request)
    {
        $request->validate([
            'active_footer_id' => 'nullable|exists:tpl_layouts,id',
            'links' => 'array|max:10',
            'links.*.url' => 'required_with:links.*.label|string',
            'links.*.label' => 'required_with:links.*.url|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site active footer
        if ($request->active_footer_id) {
            $site->update(['active_footer_id' => $request->active_footer_id]);
        }

        // Update footer links
        $tplSite = TplSite::where('site_id', $site->id)->first();
        if (!$tplSite) {
            $tplSite = TplSite::create(['site_id' => $site->id]);
        }

        $links = array_filter($request->input('links', []), function($link) {
            return !empty($link['url']) && !empty($link['label']);
        });

        $tplSite->update([
            'footer_data' => ['links' => array_values($links)]
        ]);

        return redirect()->back()->with('success', 'Footer settings updated successfully.');
    }

    /**
     * Show color settings
     */
    public function colorsEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create([
                'site_id' => $site->id,
                'settings' => ['timezone' => 'UTC'],
                'tpl_name' => 'business',
                'language_code' => ['languages' => ['en'], 'primary' => 'en']
            ]);
        }

        return view('admin.templates.colors', compact('site', 'config'));
    }

    /**
     * Update color settings
     */
    public function colorsUpdate(Request $request)
    {
        $request->validate([
            'colors' => 'array',
            'colors.nav.background' => 'nullable|string',
            'colors.nav.text' => 'nullable|string',
            'colors.hero.background' => 'nullable|string',
            'colors.hero.text' => 'nullable|string',
            'colors.footer.background' => 'nullable|string',
            'colors.footer.text' => 'nullable|string',
            'colors.button.primary' => 'nullable|string',
            'colors.button.secondary' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'tpl_colors' => $request->input('colors', [])
        ]);

        return redirect()->back()->with('success', 'Color settings updated successfully.');
    }
}
