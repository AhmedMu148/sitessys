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
        $this->command->info('🚀 Starting database seeding...');
        
        try {
            // Step 1: Create roles and permissions first
            $this->command->info('📝 Creating roles and permissions...');
            $this->call(RolePermissionSeeder::class);
            
            // Step 2: Create layout types and default layouts
            $this->command->info('🎨 Creating layout types and templates...');
            $this->call(LayoutSeeder::class);
            
            // Step 3: Create default master templates
            $this->command->info('📄 Creating default templates...');
            $this->call(DefaultTemplateSeeder::class);
            $this->call(DefaultDataSeeder::class);
            
            // Step 4: Create single site with owner and team
            $this->command->info('🏢 Creating single site with owner and team...');
            $this->call(SingleSiteSeeder::class);
            
            $this->command->info('✅ Database seeding completed successfully!');
            $this->command->info('');
            $this->command->info('🔐 Updated Login Details:');
            $this->command->info('Check the SingleSiteSeeder output above for complete credentials.');
            $this->command->info('Main Owner: owner@spsystem.com / owner123');
            $this->command->info('Admin URL: http://localhost:8000/admin');
            $this->command->info('Public URL: http://localhost:8000/');
            $this->command->info('Cloudways URL: https://phplaravel-1399496-5687062.cloudwaysapps.com/');
            
            
        } catch (\Exception $e) {
            $this->command->error('❌ Seeding failed: ' . $e->getMessage());
            throw $e;
        }
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
