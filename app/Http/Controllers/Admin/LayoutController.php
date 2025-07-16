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
        // Get current user's site
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        $site = $user->sites()->where('status', true)->first();
        if (!$site) {
            return redirect()->route('admin.dashboard')->with('error', 'No site found. Please set up your site first.');
        }

        // Read optional "type" filter from the query string
        $typeFilter = $request->query('type');

        // Build a base query that eager-loads the "type" relation and filters by user's site
        $query = TplLayout::with('type')->where('site_id', $site->id);

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
        // Get current user's site
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        $site = $user->sites()->where('status', true)->first();
        if (!$site) {
            return redirect()->route('admin.dashboard')->with('error', 'No site found. Please set up your site first.');
        }

        // Validate incoming data
        $request->validate([
            'type_id'       => 'required|exists:tpl_layout_types,id',
            'name'          => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status'        => 'boolean'
        ]);

        $data = $request->all();
        
        // Set the user and site for the new layout
        $data['user_id'] = $user->id;
        $data['site_id'] = $site->id;
        $data['data'] = $data['html_template'] ?? ''; // Map html_template to data field

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
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to view this layout.');
        }

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
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to edit this layout.');
        }

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
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to update this layout.');
        }

        // Validate incoming data
        $request->validate([
            'type_id'       => 'required|exists:tpl_layout_types,id',
            'name'          => 'required|string|max:255',
            'html_template' => 'required|string',
            'preview_image' => 'nullable|image|max:2048',
            'status'        => 'boolean'
        ]);

        $data = $request->all();

        // إذا كان الحقل في قاعدة البيانات اسمه data وليس html_template
        $data['data'] = $data['html_template'];
        unset($data['html_template']);

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
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to delete this layout.');
        }

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

    /**
     * Deactivate the specified layout (set status = false).
     */
    public function deactivate(TplLayout $layout)
    {
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to deactivate this layout.');
        }

        $layout->status = false;
        $layout->save();
        return redirect()->route('admin.layouts.index')->with('success', 'Layout deactivated successfully.');
    }

    /**
     * Activate the specified layout (set status = true).
     */
    public function activate(TplLayout $layout)
    {
        // Verify user owns this layout's site
        $user = auth()->user();
        if (!$user || $layout->site_id !== $user->sites()->where('status', true)->first()?->id) {
            abort(403, 'Unauthorized to activate this layout.');
        }

        $layout->status = true;
        $layout->save();
        return redirect()->route('admin.layouts.index')->with('success', 'Layout activated successfully.');
    }
}
