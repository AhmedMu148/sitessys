<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplLang;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = TplLang::paginate(10);
        
        return view('admin.languages.index', compact('languages'));
    }
    
    public function create()
    {
        return view('admin.languages.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:tpl_lang,code',
            'dir' => 'required|in:ltr,rtl',
            'status' => 'boolean'
        ]);
        
        TplLang::create([
            'name' => $request->name,
            'code' => $request->code,
            'dir' => $request->dir,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }
    
    public function edit(TplLang $language)
    {
        return view('admin.languages.edit', compact('language'));
    }
    
    public function update(Request $request, TplLang $language)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:tpl_lang,code,' . $language->id,
            'dir' => 'required|in:ltr,rtl',
            'status' => 'boolean'
        ]);
        
        $language->update([
            'name' => $request->name,
            'code' => $request->code,
            'dir' => $request->dir,
            'status' => $request->status ?? true,
        ]);
        
        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }
    
    public function destroy(TplLang $language)
    {
        $language->delete();
        
        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }
}
