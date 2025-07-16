<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TplPage;
use App\Models\Site;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $pages = TplPage::where('site_id', $site->id)
            ->with(['site', 'sections'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);
            
        return view('admin.pages.index', compact('pages', 'site'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        return view('admin.pages.create', compact('site'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                'unique:tpl_pages,slug,NULL,id,site_id,' . $site->id
            ],
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'show_in_nav' => 'boolean',
            'meta_data.title' => 'nullable|string|max:255',
            'meta_data.description' => 'nullable|string|max:500',
            'meta_data.keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);
        $data['site_id'] = $site->id;
        $data['is_active'] = $request->has('is_active');
        $data['show_in_nav'] = $request->has('show_in_nav');
        
        // Handle meta data
        if ($request->has('meta_data')) {
            $metaData = array_filter($request->input('meta_data'), function($value) {
                return !empty($value);
            });
            $data['meta_data'] = !empty($metaData) ? $metaData : null;
        }
        
        TplPage::create($data);
        
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
        $site = $user->sites()->where('status', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        return view('admin.pages.edit', compact('page', 'site'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                'unique:tpl_pages,slug,' . $page->id . ',id,site_id,' . $site->id
            ],
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'show_in_nav' => 'boolean',
            'meta_data.title' => 'nullable|string|max:255',
            'meta_data.description' => 'nullable|string|max:500',
            'meta_data.keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['show_in_nav'] = $request->has('show_in_nav');
        
        // Handle meta data
        if ($request->has('meta_data')) {
            $metaData = array_filter($request->input('meta_data'), function($value) {
                return !empty($value);
            });
            $data['meta_data'] = !empty($metaData) ? $metaData : null;
        }
        
        $page->update($data);
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TplPage $page)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        // Check if page belongs to user's site
        if ($page->site_id !== $site->id) {
            abort(403, 'Unauthorized access to page.');
        }
        
        // Don't allow deletion of home page
        if ($page->slug === 'home') {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot delete the home page.');
        }
        
        $page->delete();
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
