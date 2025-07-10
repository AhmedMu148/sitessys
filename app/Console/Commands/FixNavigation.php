<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use App\Models\TplDesign;
use App\Models\TplPage;
use App\Models\TplLayoutType;

class FixNavigation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:navigation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix navigation consistency across all pages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing navigation consistency across all pages...');
        
        $site = Site::where('status', true)->first();
        if (!$site) {
            $this->error('No active site found!');
            return 1;
        }
        
        $navType = TplLayoutType::where('name', 'nav')->first();
        if (!$navType) {
            $this->error('Navigation layout type not found!');
            return 1;
        }
        
        $pages = TplPage::where('site_id', $site->id)->get();
        
        // Get the home page navigation as the template
        $homePage = TplPage::where('site_id', $site->id)
            ->where('slug', 'home')
            ->first();
            
        if (!$homePage) {
            $this->error('Home page not found!');
            return 1;
        }
        
        $templateNav = TplDesign::where('site_id', $site->id)
            ->where('page_id', $homePage->id)
            ->where('layout_type_id', $navType->id)
            ->first();
            
        if (!$templateNav) {
            $this->error('Template navigation not found on home page!');
            return 1;
        }
        
        // Standard navbar data to use
        $standardNavData = $templateNav->data ?? [
            'brand' => 'TechCorp',
            'menu_items' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'About', 'url' => '/about'],
                ['title' => 'Services', 'url' => '/services'],
                ['title' => 'Contact', 'url' => '/contact']
            ],
            'cta_text' => 'Get Started',
            'cta_url' => '#contact'
        ];
        
        $count = 0;
        
        // Update all navbar designs
        foreach($pages as $page) {
            $navDesign = TplDesign::where('site_id', $site->id)
                ->where('page_id', $page->id)
                ->where('layout_type_id', $navType->id)
                ->first();
            
            if ($navDesign) {
                $navDesign->data = $standardNavData;
                $navDesign->save();
                $count++;
            } else {
                // Create navigation for pages that don't have one
                TplDesign::create([
                    'site_id' => $site->id,
                    'page_id' => $page->id,
                    'layout_id' => $templateNav->layout_id,
                    'layout_type_id' => $navType->id,
                    'lang_code' => $templateNav->lang_code ?? 'en',
                    'sort_order' => 1,
                    'data' => $standardNavData,
                    'status' => true
                ]);
                $count++;
            }
        }
        
        $this->info("Navigation updated for {$count} pages.");
        return 0;
    }
}
