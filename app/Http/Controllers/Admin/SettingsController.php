<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        $languages = TplLang::all();
        
        return view('admin.settings.index', compact('site', 'config', 'languages'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'url' => 'nullable|url',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site basic info
        $site->update([
            'site_name' => $request->site_name,
            'url' => $request->url
        ]);

        // Update site config
        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $settings = $request->input('settings', []);
        $data = $request->input('data', []);
        
        $config->update([
            'settings' => $settings,
            'data' => $data,
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Show language settings
     */
    public function languageEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create([
                'site_id' => $site->id,
                'settings' => ['timezone' => 'UTC'],
                'tpl_name' => 'business',
                'language_code' => ['languages' => ['en'], 'primary' => 'en']
            ]);
        }

        $languages = TplLang::all();

        return view('admin.settings.languages', compact('site', 'config', 'languages'));
    }

    /**
     * Update language settings
     */
    public function languageUpdate(Request $request)
    {
        $request->validate([
            'languages' => 'required|array|min:1',
            'languages.*' => 'exists:tpl_langs,code',
            'primary_language' => 'required|string|in:' . implode(',', $request->input('languages', ['en']))
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'language_code' => [
                'languages' => $request->input('languages'),
                'primary' => $request->input('primary_language')
            ]
        ]);

        return redirect()->back()->with('success', 'Language settings updated successfully.');
    }

    /**
     * Show general settings
     */
    public function generalEdit()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create([
                'site_id' => $site->id,
                'settings' => ['timezone' => 'UTC'],
                'tpl_name' => 'business'
            ]);
        }

        return view('admin.settings.general', compact('site', 'config'));
    }

    /**
     * Update general settings
     */
    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'url' => 'nullable|url',
            'tpl_name' => 'required|string|max:50',
            'settings.timezone' => 'required|string',
            'data.logo' => 'nullable|string',
            'data.description' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Update site basic info
        $site->update([
            'site_name' => $request->site_name,
            'url' => $request->url
        ]);

        // Update site config
        $config = SiteConfig::where('site_id', $site->id)->first();
        if (!$config) {
            $config = SiteConfig::create(['site_id' => $site->id]);
        }

        $config->update([
            'tpl_name' => $request->tpl_name,
            'settings' => $request->input('settings', ['timezone' => 'UTC']),
            'data' => $request->input('data', [])
        ]);

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }
}
