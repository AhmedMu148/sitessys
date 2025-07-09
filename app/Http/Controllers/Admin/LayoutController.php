<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use Illuminate\Support\Facades\Storage;

class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layouts = TplLayout::with('type')->paginate(10);
        return view('admin.layouts.index', compact('layouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TplLayoutType::where('status', true)->get();
        return view('admin.layouts.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_id' => 'required|exists:tpl_layout_types,id',
            'name' => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')->store('layout_previews', 'public');
        }
        
        TplLayout::create($data);
        
        return redirect()->route('admin.layouts.index')
            ->with('success', 'Layout created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TplLayout $layout)
    {
        return view('admin.layouts.show', compact('layout'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TplLayout $layout)
    {
        $types = TplLayoutType::where('status', true)->get();
        return view('admin.layouts.edit', compact('layout', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TplLayout $layout)
    {
        $request->validate([
            'type_id' => 'required|exists:tpl_layout_types,id',
            'name' => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('preview_image')) {
            if ($layout->preview_image) {
                Storage::disk('public')->delete($layout->preview_image);
            }
            $data['preview_image'] = $request->file('preview_image')->store('layout_previews', 'public');
        }
        
        $layout->update($data);
        
        return redirect()->route('admin.layouts.index')
            ->with('success', 'Layout updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TplLayout $layout)
    {
        if ($layout->preview_image) {
            Storage::disk('public')->delete($layout->preview_image);
        }
        
        $layout->delete();
        
        return redirect()->route('admin.layouts.index')
            ->with('success', 'Layout deleted successfully.');
    }
}
