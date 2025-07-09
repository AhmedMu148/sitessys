<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplPage;
use App\Models\Site;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = TplPage::with('site')->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sites = Site::where('status', true)->get();
        return view('admin.pages.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tpl_pages,slug',
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);

        TplPage::create($request->all());
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TplPage $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TplPage $page)
    {
        $sites = Site::where('status', true)->get();
        return view('admin.pages.edit', compact('page', 'sites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TplPage $page)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tpl_pages,slug,' . $page->id,
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);

        $page->update($request->all());
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TplPage $page)
    {
        $page->delete();
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
