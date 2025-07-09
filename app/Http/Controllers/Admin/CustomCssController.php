<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplCustomCss;
use App\Models\Site;

class CustomCssController extends Controller
{
    public function index()
    {
        $site = Site::where('status', true)->first();
        $customCss = TplCustomCss::where('site_id', $site->id)->paginate(10);
        
        return view('admin.custom-css.index', compact('customCss'));
    }
    
    public function create()
    {
        return view('admin.custom-css.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);
        
        $site = Site::where('status', true)->first();
        
        TplCustomCss::create([
            'site_id' => $site->id,
            'name' => $request->name,
            'content' => $request->content,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.custom-css.index')
            ->with('success', 'Custom CSS created successfully.');
    }
    
    public function edit(TplCustomCss $customCss)
    {
        return view('admin.custom-css.edit', compact('customCss'));
    }
    
    public function update(Request $request, TplCustomCss $customCss)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);
        
        $customCss->update([
            'name' => $request->name,
            'content' => $request->content,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.custom-css.index')
            ->with('success', 'Custom CSS updated successfully.');
    }
    
    public function destroy(TplCustomCss $customCss)
    {
        $customCss->delete();
        
        return redirect()->route('admin.custom-css.index')
            ->with('success', 'Custom CSS deleted successfully.');
    }
}
