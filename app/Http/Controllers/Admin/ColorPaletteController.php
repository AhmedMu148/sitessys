<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplColorPalette;
use App\Models\Site;

class ColorPaletteController extends Controller
{
    public function index()
    {
        $site = Site::where('status', true)->first();
        $colors = TplColorPalette::where('site_id', $site->id)->paginate(10);
        
        return view('admin.color-palette.index', compact('colors'));
    }
    
    public function create()
    {
        return view('admin.color-palette.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'required|string|max:7',
            'is_primary' => 'boolean',
            'status' => 'boolean'
        ]);
        
        $site = Site::where('status', true)->first();
        
        // If this is set as primary, update other primary colors
        if ($request->is_primary) {
            TplColorPalette::where('site_id', $site->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }
        
        TplColorPalette::create([
            'site_id' => $site->id,
            'name' => $request->name,
            'color_code' => $request->color_code,
            'is_primary' => $request->is_primary ?? false,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.color-palette.index')
            ->with('success', 'Color palette created successfully.');
    }
    
    public function edit(TplColorPalette $colorPalette)
    {
        return view('admin.color-palette.edit', compact('colorPalette'));
    }
    
    public function update(Request $request, TplColorPalette $colorPalette)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'required|string|max:7',
            'is_primary' => 'boolean',
            'status' => 'boolean'
        ]);
        
        $site = Site::where('status', true)->first();
        
        // If this is set as primary, update other primary colors
        if ($request->is_primary) {
            TplColorPalette::where('site_id', $site->id)
                ->where('is_primary', true)
                ->where('id', '!=', $colorPalette->id)
                ->update(['is_primary' => false]);
        }
        
        $colorPalette->update([
            'name' => $request->name,
            'color_code' => $request->color_code,
            'is_primary' => $request->is_primary ?? false,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.color-palette.index')
            ->with('success', 'Color palette updated successfully.');
    }
    
    public function destroy(TplColorPalette $colorPalette)
    {
        $colorPalette->delete();
        
        return redirect()->route('admin.color-palette.index')
            ->with('success', 'Color palette deleted successfully.');
    }
}
