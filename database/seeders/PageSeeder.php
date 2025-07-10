<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\TplPage;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $site = Site::where('status', true)->first();
        
        if (!$site) {
            return;
        }
        
        // Create all standard pages
        $pages = [
            [
                'name' => 'Home',
                'slug' => 'home',
                'sort_order' => 1,
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'sort_order' => 2,
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'sort_order' => 3,
            ],
            [
                'name' => 'Portfolio',
                'slug' => 'portfolio',
                'sort_order' => 4,
            ],
            [
                'name' => 'Pricing',
                'slug' => 'pricing',
                'sort_order' => 5,
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'sort_order' => 6,
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'sort_order' => 7,
            ],
            [
                'name' => 'Privacy Policy',
                'slug' => 'privacy',
                'sort_order' => 8,
            ],
            [
                'name' => 'Terms of Service',
                'slug' => 'terms',
                'sort_order' => 9,
            ],
            [
                'name' => 'Cookie Policy',
                'slug' => 'cookies',
                'sort_order' => 10,
            ],
        ];
        
        foreach ($pages as $pageData) {
            TplPage::create([
                'site_id' => $site->id,
                'name' => $pageData['name'],
                'slug' => $pageData['slug'],
                'sort_order' => $pageData['sort_order'],
                'status' => true
            ]);
        }
    }
}
