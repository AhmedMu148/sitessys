<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplDesign;
use App\Models\TplPage;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use App\Models\Site;
use App\Models\TplLang;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designs = TplDesign::with(['page', 'layout', 'layoutType', 'site'])
            ->orderBy('page_id')
            ->orderBy('sort_order')
            ->paginate(15);
            
        return view('admin.designs.index', compact('designs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = TplPage::where('status', true)->get();
        $layouts = TplLayout::where('status', true)->with('type')->get();
        $layoutTypes = TplLayoutType::where('status', true)->get();
        $sites = Site::where('status', true)->get();
        $languages = TplLang::where('status', true)->get();
        
        return view('admin.designs.create', compact('pages', 'layouts', 'layoutTypes', 'sites', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'page_id' => 'required|exists:tpl_pages,id',
            'layout_id' => 'required|exists:tpl_layouts,id',
            'layout_type_id' => 'required|exists:tpl_layout_types,id',
            'lang_code' => 'required|string|max:5',
            'sort_order' => 'integer|min:0',
            'data' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        
        // Parse JSON data if provided
        if ($request->has('data') && !empty($request->data)) {
            try {
                $data['data'] = json_decode($request->data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return back()->withErrors(['data' => 'Invalid JSON format in data field.'])->withInput();
                }
            } catch (\Exception $e) {
                return back()->withErrors(['data' => 'Invalid JSON format in data field.'])->withInput();
            }
        } else {
            $data['data'] = null;
        }
        
        // Handle checkbox status
        $data['status'] = $request->has('status') ? 1 : 0;

        TplDesign::create($data);
        
        return redirect()->route('admin.designs.index')
            ->with('success', 'Design created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TplDesign $design)
    {
        $design->load(['page', 'layout', 'layoutType', 'site']);
        return view('admin.designs.show', compact('design'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TplDesign $design)
    {
        $pages = TplPage::where('status', true)->get();
        $layouts = TplLayout::where('status', true)->with('type')->get();
        $layoutTypes = TplLayoutType::where('status', true)->get();
        $sites = Site::where('status', true)->get();
        $languages = TplLang::where('status', true)->get();
        
        return view('admin.designs.edit', compact('design', 'pages', 'layouts', 'layoutTypes', 'sites', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TplDesign $design)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'page_id' => 'required|exists:tpl_pages,id',
            'layout_id' => 'required|exists:tpl_layouts,id',
            'layout_type_id' => 'required|exists:tpl_layout_types,id',
            'lang_code' => 'required|string|max:5',
            'sort_order' => 'integer|min:0',
            'data' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $data = $request->all();
        
        // Parse JSON data if provided
        if ($request->has('data') && !empty($request->data)) {
            try {
                $data['data'] = json_decode($request->data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return back()->withErrors(['data' => 'Invalid JSON format in data field.'])->withInput();
                }
            } catch (\Exception $e) {
                return back()->withErrors(['data' => 'Invalid JSON format in data field.'])->withInput();
            }
        } else {
            $data['data'] = null;
        }
        
        // Handle checkbox status
        $data['status'] = $request->has('status') ? 1 : 0;

        $design->update($data);
        
        return redirect()->route('admin.designs.index')
            ->with('success', 'Design updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TplDesign $design)
    {
        $design->delete();
        
        return redirect()->route('admin.designs.index')
            ->with('success', 'Design deleted successfully.');
    }
}
