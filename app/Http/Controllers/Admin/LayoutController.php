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
     * Display a listing of the resource, optionally filtered by layout type.
     *
     * Supports a `type` query parameter with values `nav`, `section` or `footer`.
     * Adds `withQueryString()` so pagination preserves the filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Read optional "type" filter from the query string
        $typeFilter = $request->query('type');

        // Build a base query that eager-loads the "type" relation
        $query = TplLayout::with('type');

        if ($typeFilter) {
            // Find the matching layout type by its "name"
            $type = TplLayoutType::where('name', $typeFilter)->first();
            if ($type) {
                // Apply a where clause to filter by type_id
                $query->where('type_id', $type->id);
            }
        }

        // Paginate results and keep the query string (so "?type=nav" persists)
        $layouts = $query->paginate(10);
        $layouts->withQueryString();

        // Pass both the layouts and the current filter to the view
        return view('admin.layouts.index', compact('layouts', 'typeFilter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retrieve all active layout types for the select dropdown
        $types = TplLayoutType::where('status', true)->get();
        return view('admin.layouts.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'type_id'       => 'required|exists:tpl_layout_types,id',
            'name'          => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status'        => 'boolean'
        ]);

        $data = $request->all();

        // If user uploaded a preview image, store it
        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request
                ->file('preview_image')
                ->store('layout_previews', 'public');
        }

        // Create the new layout record
        TplLayout::create($data);

        // Redirect back with success message
        return redirect()
            ->route('admin.layouts.index')
            ->with('success', 'Layout created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TplLayout  $layout
     * @return \Illuminate\View\View
     */
    public function show(TplLayout $layout)
    {
        // Show details for a single layout
        return view('admin.layouts.show', compact('layout'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TplLayout  $layout
     * @return \Illuminate\View\View
     */
    public function edit(TplLayout $layout)
    {
        // Retrieve active types for the edit form
        $types = TplLayoutType::where('status', true)->get();
        return view('admin.layouts.edit', compact('layout', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TplLayout     $layout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TplLayout $layout)
    {
        // Validate incoming data
        $request->validate([
            'type_id'       => 'required|exists:tpl_layout_types,id',
            'name'          => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status'        => 'boolean'
        ]);

        $data = $request->all();

        // If a new preview image is uploaded, delete the old one then store the new
        if ($request->hasFile('preview_image')) {
            if ($layout->preview_image) {
                Storage::disk('public')->delete($layout->preview_image);
            }
            $data['preview_image'] = $request
                ->file('preview_image')
                ->store('layout_previews', 'public');
        }

        // Update the layout record
        $layout->update($data);

        // Redirect with a success message
        return redirect()
            ->route('admin.layouts.index')
            ->with('success', 'Layout updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Deletes any stored preview image first.
     *
     * @param  \App\Models\TplLayout  $layout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TplLayout $layout)
    {
        // Delete the preview image file if it exists
        if ($layout->preview_image) {
            Storage::disk('public')->delete($layout->preview_image);
        }

        // Delete the layout record
        $layout->delete();

        // Redirect back with success
        return redirect()
            ->route('admin.layouts.index')
            ->with('success', 'Layout deleted successfully.');
    }
}
