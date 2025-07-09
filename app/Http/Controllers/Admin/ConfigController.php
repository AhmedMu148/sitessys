<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLang;

class ConfigController extends Controller
{
    public function index()
    {
        $site = Site::where('status', true)->first();
        $config = SiteConfig::where('site_id', $site->id)->with('language')->first();
        $languages = TplLang::where('status', true)->get();
        
        return view('admin.config.index', compact('site', 'config', 'languages'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_domain' => 'required|string|max:255',
            'lang_id' => 'required|exists:tpl_lang,id',
            'direction' => 'required|in:ltr,rtl',
        ]);
        
        $site = Site::where('status', true)->first();
        
        // Update site information
        $site->update([
            'name' => $request->site_name,
            'domain' => $request->site_domain,
        ]);
        
        // Update or create site config
        $config = SiteConfig::where('site_id', $site->id)->first();
        if ($config) {
            $config->update([
                'lang_id' => $request->lang_id,
                'direction' => $request->direction,
            ]);
        } else {
            SiteConfig::create([
                'site_id' => $site->id,
                'lang_id' => $request->lang_id,
                'direction' => $request->direction,
                'is_default' => true,
            ]);
        }
        
        return redirect()->route('admin.config.index')
            ->with('success', 'Site configuration updated successfully.');
    }
}
