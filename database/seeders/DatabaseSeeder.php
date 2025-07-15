<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\SiteSocial;
use App\Models\SiteContact;
use App\Models\SiteSeoInt;
use App\Models\TplLang;
use App\Models\TplLayoutType;
use App\Models\TplLayout;
use App\Models\PageSection;
use App\Models\TplPage;
use App\Models\TplSite;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DefaultTemplateSeeder::class,
            LayoutSeeder::class,      // Create layout types and layouts first
            DefaultDataSeeder::class,  // This should create users and sites
            TestDataSeeder::class,    // Uses TemplateCloneService for proper setup
        ]);
    }

    /**
     * Create a new site setup with all required data
     * This method is now replaced by TemplateCloneService
     * which is called automatically when users are created
     */
    public function createSiteSetup($userName = 'Admin User', $userEmail = 'admin@example.com', $siteName = 'My Site', $domain = 'localhost')
    {
        // This method is deprecated - use TemplateCloneService instead
        // The TestDataSeeder will automatically create proper sites using TemplateCloneService
        $this->command->info('Site setup is now handled by TemplateCloneService automatically.');
        
        // If you need to create a site manually, use:
        // $user = User::create(['name' => $userName, 'email' => $userEmail, 'password' => bcrypt('password')]);
        // $templateCloneService = app(\App\Services\TemplateCloneService::class);
        // $templateCloneService->cloneDefaultTemplateForUser($user);
        
        return null;
    }
}
