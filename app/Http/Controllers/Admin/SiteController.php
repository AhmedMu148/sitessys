<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\SiteSocial;
use App\Models\SiteContact;
use App\Models\SiteSeoInt;
use App\Models\TplSection;
use App\Models\TplPage;
use App\Models\TplSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = Site::with('user')->paginate(10);
        return view('admin.sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.sites.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'site_name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        DB::transaction(function () use ($request) {
            $site = Site::create($request->all());
            
            // Create site setup using the new schema
            $this->createSiteSetup($site);
        });

        return redirect()->route('admin.sites.index')->with('success', 'Site created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        $site->load(['user', 'config', 'social', 'contact', 'seoIntegrations', 'sections', 'pages', 'tplSite']);
        return view('admin.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.sites.edit', compact('site', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'site_name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        $site->update($request->all());
        return redirect()->route('admin.sites.index')->with('success', 'Site updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('admin.sites.index')->with('success', 'Site deleted successfully!');
    }

    /**
     * Create complete site setup according to the new schema
     */
    private function createSiteSetup(Site $site)
    {
        // Create site config
        SiteConfig::create([
            'site_id' => $site->id,
            'data' => [
                'logo' => '/logo.png',
                'favicon' => '/favicon.ico',
                'title' => $site->site_name,
                'keyword' => 'website, business, services',
                'description' => 'Welcome to ' . $site->site_name
            ],
            'lang_id' => '1,2' // English and Arabic
        ]);

        // Create social data
        SiteSocial::create([
            'site_id' => $site->id,
            'data' => [
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'linkedin' => ''
            ]
        ]);

        // Create contact data
        SiteContact::create([
            'site_id' => $site->id,
            'data' => [
                'email' => 'contact@' . str_replace(['http://', 'https://'], '', $site->domain ?? 'example.com'),
                'phone' => '',
                'address' => ''
            ]
        ]);

        // Create SEO integration placeholder
        SiteSeoInt::create([
            'site_id' => $site->id,
            'int_name' => 'ses',
            'data' => [
                'username' => '',
                'api_key' => '',
                'email' => '',
                'balance' => '0',
                'api_link' => ''
            ],
            'status' => false
        ]);

        // Create 60 sections (30 per language: 6 pages × 5 sections)
        $sectionData = [
            'en' => [
                'title' => 'Section Title',
                'content' => 'Section content in English',
                'button_text' => 'Learn More'
            ],
            'ar' => [
                'title' => 'عنوان القسم',
                'content' => 'محتوى القسم باللغة العربية',
                'button_text' => 'اعرف المزيد'
            ]
        ];

        for ($i = 1; $i <= 60; $i++) {
            TplSection::create([
                'site_id' => $site->id,
                'data' => $sectionData
            ]);
        }

        // Create 6 pages
        $pageLinks = [
            'Home' => '/',
            'About' => '/about',
            'Services' => '/services',
            'Portfolio' => '/portfolio',
            'Blog' => '/blog',
            'Contact' => '/contact'
        ];

        $createdPages = [];
        foreach ($pageLinks as $name => $link) {
            $sectionIds = [];
            for ($i = 0; $i < 5; $i++) {
                $sectionIds[] = rand(1, 60);
            }
            
            $createdPages[] = TplPage::create([
                'site_id' => $site->id,
                'name' => $name,
                'link' => $link,
                'section_id' => implode(',', $sectionIds)
            ]);
        }

        // Create tpl_site configuration
        TplSite::create([
            'site_id' => $site->id,
            'nav' => 1, // First layout (nav)
            'pages' => array_slice(array_column($createdPages, 'id'), 0, 4), // First 4 pages
            'footer' => 8 // Last layout (footer)
        ]);
    }
}
