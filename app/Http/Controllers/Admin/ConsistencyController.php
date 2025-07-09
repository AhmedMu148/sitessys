<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TplDesign;
use App\Models\TplPage;
use App\Models\TplLayoutType;
use App\Models\Site;

class ConsistencyController extends Controller
{
    public function index()
    {
        $site = Site::where('status', true)->first();
        $pages = TplPage::where('site_id', $site->id)->get();
        
        // Get navbar and footer consistency status
        $navType = TplLayoutType::where('name', 'nav')->first();
        $footerType = TplLayoutType::where('name', 'footer')->first();
        
        $navDesigns = TplDesign::where('site_id', $site->id)
            ->where('layout_type_id', $navType->id)
            ->get();
            
        $footerDesigns = TplDesign::where('site_id', $site->id)
            ->where('layout_type_id', $footerType->id)
            ->get();
        
        // Check for consistency
        $navBrands = $navDesigns->pluck('data.brand')->unique();
        $footerBrands = $footerDesigns->pluck('data.brand')->unique();
        
        $navConsistent = $navBrands->count() <= 1;
        $footerConsistent = $footerBrands->count() <= 1;
        
        return view('admin.consistency.index', compact(
            'pages', 'navDesigns', 'footerDesigns', 
            'navConsistent', 'footerConsistent'
        ));
    }
    
    public function fixNavbar(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'cta_text' => 'required|string|max:255',
            'cta_url' => 'required|string|max:255',
        ]);
        
        $site = Site::where('status', true)->first();
        $navType = TplLayoutType::where('name', 'nav')->first();
        $pages = TplPage::where('site_id', $site->id)->get();
        
        // Standard navbar data
        $standardNavData = [
            'brand' => $request->brand,
            'menu_items' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'About', 'url' => '/about'],
                ['title' => 'Services', 'url' => '/services'],
                ['title' => 'Contact', 'url' => '/contact']
            ],
            'cta_text' => $request->cta_text,
            'cta_url' => $request->cta_url
        ];
        
        // Update all navbar designs
        foreach($pages as $page) {
            $navDesign = TplDesign::where('page_id', $page->id)
                ->where('layout_type_id', $navType->id)
                ->first();
            
            if ($navDesign) {
                $navDesign->data = $standardNavData;
                $navDesign->save();
            }
        }
        
        return redirect()->route('admin.consistency.index')
            ->with('success', 'Navbar consistency updated across all pages.');
    }
    
    public function fixFooter(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
        ]);
        
        $site = Site::where('status', true)->first();
        $footerType = TplLayoutType::where('name', 'footer')->first();
        $pages = TplPage::where('site_id', $site->id)->get();
        
        // Standard footer data
        $standardFooterData = [
            'brand' => $request->brand,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'social_links' => [
                ['name' => 'Facebook', 'url' => 'https://facebook.com/techcorp', 'icon' => 'facebook'],
                ['name' => 'Twitter', 'url' => 'https://twitter.com/techcorp', 'icon' => 'twitter'],
                ['name' => 'LinkedIn', 'url' => 'https://linkedin.com/company/techcorp', 'icon' => 'linkedin'],
                ['name' => 'Instagram', 'url' => 'https://instagram.com/techcorp', 'icon' => 'instagram']
            ],
            'quick_links' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'About', 'url' => '/about'],
                ['title' => 'Services', 'url' => '/services'],
                ['title' => 'Contact', 'url' => '/contact']
            ],
            'legal_links' => [
                ['title' => 'Privacy Policy', 'url' => '/privacy'],
                ['title' => 'Terms of Service', 'url' => '/terms'],
                ['title' => 'Cookie Policy', 'url' => '/cookies']
            ]
        ];
        
        // Update all footer designs
        foreach($pages as $page) {
            $footerDesign = TplDesign::where('page_id', $page->id)
                ->where('layout_type_id', $footerType->id)
                ->first();
            
            if ($footerDesign) {
                $footerDesign->data = $standardFooterData;
                $footerDesign->save();
            }
        }
        
        return redirect()->route('admin.consistency.index')
            ->with('success', 'Footer consistency updated across all pages.');
    }
}
