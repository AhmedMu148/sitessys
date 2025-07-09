<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplCustomScript;
use App\Models\Site;

class CustomScriptController extends Controller
{
    public function index()
    {
        $site = Site::where('status', true)->first();
        $customScripts = TplCustomScript::where('site_id', $site->id)->paginate(10);
        
        return view('admin.custom-scripts.index', compact('customScripts'));
    }
    
    public function create()
    {
        return view('admin.custom-scripts.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'location' => 'required|in:head,footer',
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);
        
        $site = Site::where('status', true)->first();
        
        TplCustomScript::create([
            'site_id' => $site->id,
            'name' => $request->name,
            'content' => $request->content,
            'location' => $request->location,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.custom-scripts.index')
            ->with('success', 'Custom script created successfully.');
    }
    
    public function edit(TplCustomScript $customScript)
    {
        return view('admin.custom-scripts.edit', compact('customScript'));
    }
    
    public function update(Request $request, TplCustomScript $customScript)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'location' => 'required|in:head,footer',
            'sort_order' => 'integer|min:0',
            'status' => 'boolean'
        ]);
        
        $customScript->update([
            'name' => $request->name,
            'content' => $request->content,
            'location' => $request->location,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.custom-scripts.index')
            ->with('success', 'Custom script updated successfully.');
    }
    
    public function destroy(TplCustomScript $customScript)
    {
        $customScript->delete();
        
        return redirect()->route('admin.custom-scripts.index')
            ->with('success', 'Custom script deleted successfully.');
    }
}
