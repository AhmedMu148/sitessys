<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserTemplate;
use App\Services\TemplateCloneService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with different roles
        $this->createTestUsers();
        
        // Create test templates for users
        $this->createTestTemplates();
        
        $this->command->info('Test data created successfully!');
    }

    /**
     * Create test users with different roles and configurations
     */
    private function createTestUsers(): void
    {
        // Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'subdomain' => 'admin',
                'domain' => 'phplaravel-1399496-5687062.cloudwaysapps.com',
                'is_active' => true,
                'phone' => '+1-555-0001',
                'bio' => 'System administrator with full access to all features. Works on both localhost and Cloudways server.',
                'settings' => json_encode([
                    'theme' => 'dark',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'UTC',
                    'supported_domains' => [
                        'localhost',
                        'phplaravel-1399496-5687062.cloudwaysapps.com'
                    ]
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Business User
        $businessUser = User::firstOrCreate(
            ['email' => 'business@example.com'],
            [
                'name' => 'John Business',
                'password' => Hash::make('password'),
                'subdomain' => 'business',
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0103',
                'bio' => 'Business owner running a professional services company.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/New_York'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
            ]
        );
        $businessUser->assignRole('admin');

        // Portfolio User
        $portfolioUser = User::firstOrCreate(
            ['email' => 'portfolio@example.com'],
            [
                'name' => 'Jane Designer',
                'password' => Hash::make('password'),
                'subdomain' => 'portfolio',
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0204',
                'bio' => 'Creative designer and visual artist showcasing portfolio work.',
                'settings' => json_encode([
                    'theme' => 'dark',
                    'language' => 'en',
                    'notifications' => false,
                    'timezone' => 'America/Los_Angeles'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(8),
            ]
        );
        $portfolioUser->assignRole('admin');

        // Blog User
        $blogUser = User::firstOrCreate(
            ['email' => 'blogger@example.com'],
            [
                'name' => 'Mike Writer',
                'password' => Hash::make('password'),
                'subdomain' => 'blog',
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0305',
                'bio' => 'Content creator and blogger sharing insights on technology and business.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/Chicago'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(2),
            ]
        );
        $blogUser->assignRole('admin');

        // E-commerce User
        $shopUser = User::firstOrCreate(
            ['email' => 'shop@example.com'],
            [
                'name' => 'Lisa Shop Owner',
                'password' => Hash::make('password'),
                'subdomain' => 'shop',
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0406',
                'bio' => 'Online store owner selling handmade crafts and accessories.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/Denver'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(3),
            ]
        );
        $shopUser->assignRole('admin');

        // Team Member User
        $teamMember = User::firstOrCreate(
            ['email' => 'team@example.com'],
            [
                'name' => 'Alex Team Member',
                'password' => Hash::make('password'),
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0507',
                'bio' => 'Content editor and team member.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/New_York'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(4),
            ]
        );
        $teamMember->assignRole('team-member');

        // Regular User
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Tom Regular',
                'password' => Hash::make('password'),
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-0608',
                'bio' => 'Regular platform user.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => false,
                    'timezone' => 'UTC'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(3),
            ]
        );
        $regularUser->assignRole('user');

        // Inactive User (for testing)
        $inactiveUser = User::firstOrCreate(
            ['email' => 'inactive@example.com'],
            [
                'name' => 'Inactive User',
                'password' => Hash::make('password'),
                'subdomain' => 'inactive',
                'domain' => null,
                'is_active' => false,
                'phone' => '+1-555-0709',
                'bio' => 'Deactivated user account for testing.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => false,
                    'timezone' => 'UTC'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subWeeks(2),
            ]
        );
        $inactiveUser->assignRole('user');

        $this->command->info('✅ Test users created successfully!');
    }

    /**
     * Create test templates for users using TemplateCloneService
     */
    private function createTestTemplates(): void
    {
        // Get all test users that need templates
        $testUsers = [
            'admin@example.com',
            'business@example.com',
            'portfolio@example.com', 
            'blogger@example.com',
            'shop@example.com',
            'team@example.com',
            'user@example.com'
        ];

        foreach ($testUsers as $email) {
            $user = User::where('email', $email)->first();
            
            if ($user && !$user->sites()->exists()) {
                // Get service instance from Laravel container
                $templateCloneService = app(TemplateCloneService::class);
                $success = $templateCloneService->cloneDefaultTemplateForUser($user);
                
                if ($success) {
                    $this->command->info("✅ Template cloned for user: {$email}");
                } else {
                    $this->command->error("❌ Failed to clone template for user: {$email}");
                }
            } else if ($user && $user->sites()->exists()) {
                $this->command->info("ℹ️  User {$email} already has a site and template");
            }
        }

        $this->command->info('✅ Test templates created successfully!');
    }
}
