<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplLayoutType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeaderFooterController extends Controller
{
    /**
     * Display headers and footers for the current user's site
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found. Please create a site first.');
        }
        
        // Get layout types
        $navType = TplLayoutType::where('name', 'nav')->first();
        $footerType = TplLayoutType::where('name', 'footer')->first();
        
        // Get user's headers and footers
        $headers = TplLayout::where('site_id', $site->id)
            ->where('type_id', $navType->id)
            ->orderBy('sort_order')
            ->get();
            
        $footers = TplLayout::where('site_id', $site->id)
            ->where('type_id', $footerType->id)
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.layouts.headers-footers', compact('site', 'headers', 'footers'));
    }
    
    /**
     * Show the form for creating a new header or footer
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        $type = $request->get('type', 'header'); // Default to header
        
        if ($type === 'header') {
            return view('admin.layouts.create-header', compact('site'));
        } elseif ($type === 'footer') {
            return view('admin.layouts.create-footer', compact('site'));
        }
        
        return redirect()->route('admin.headers-footers.index')
            ->with('error', 'Invalid type specified.');
    }
    
    /**
     * Store a new header or footer
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:header,footer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'html_content' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }
        
        // Get the appropriate layout type
        $typeName = $request->type === 'header' ? 'nav' : 'footer';
        $layoutType = TplLayoutType::where('name', $typeName)->first();
        
        if (!$layoutType) {
            return redirect()->back()
                ->with('error', 'Layout type not found.');
        }
        
        // Create the layout
        $layout = TplLayout::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'type_id' => $layoutType->id,
            'name' => $request->name,
            'description' => $request->description,
            'data' => $request->html_content,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);
        
        $typeLabel = ucfirst($request->type);
        return redirect()->route('admin.headers-footers.index')
            ->with('success', $typeLabel . ' created successfully!');
    }
    
    /**
     * Show the form for editing a header or footer
     */
    public function edit($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $layout = TplLayout::where('id', $id)
            ->where('site_id', $site->id)
            ->with('type')
            ->firstOrFail();
        
        // Determine if it's a header or footer
        $isHeader = $layout->type->name === 'nav';
        $viewName = $isHeader ? 'admin.layouts.edit-header' : 'admin.layouts.edit-footer';
        
        return view($viewName, compact('layout', 'site'));
    }
    
    /**
     * Update a header or footer
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'html_content' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $layout = TplLayout::where('id', $id)
            ->where('site_id', $site->id)
            ->with('type')
            ->firstOrFail();
        
        $layout->update([
            'name' => $request->name,
            'description' => $request->description,
            'data' => $request->html_content,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        
        $typeLabel = $layout->type->name === 'nav' ? 'Header' : 'Footer';
        return redirect()->route('admin.headers-footers.index')
            ->with('success', $typeLabel . ' updated successfully!');
    }
    
    /**
     * Activate a header or footer
     */
    public function activate($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $layout = TplLayout::where('id', $id)
            ->where('site_id', $site->id)
            ->with('type')
            ->firstOrFail();
        
        // Update the active header or footer in the site
        if ($layout->type->name === 'nav') {
            $site->update(['active_header_id' => $layout->id]);
            $message = 'Header activated successfully!';
        } elseif ($layout->type->name === 'footer') {
            $site->update(['active_footer_id' => $layout->id]);
            $message = 'Footer activated successfully!';
        } else {
            return redirect()->back()
                ->with('error', 'Invalid layout type.');
        }
        
        return redirect()->back()
            ->with('success', $message);
    }
    
    /**
     * Delete a header or footer
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status', true)->first();
        
        $layout = TplLayout::where('id', $id)
            ->where('site_id', $site->id)
            ->with('type')
            ->firstOrFail();
        
        // Check if this is the active header or footer
        if ($layout->type->name === 'nav' && $site->active_header_id == $layout->id) {
            $site->update(['active_header_id' => null]);
        } elseif ($layout->type->name === 'footer' && $site->active_footer_id == $layout->id) {
            $site->update(['active_footer_id' => null]);
        }
        
        $typeLabel = $layout->type->name === 'nav' ? 'Header' : 'Footer';
        $layout->delete();
        
        return redirect()->route('admin.headers-footers.index')
            ->with('success', $typeLabel . ' deleted successfully!');
    }
}
