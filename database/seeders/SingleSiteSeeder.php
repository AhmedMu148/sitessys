<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\TemplateCloneService;
use Illuminate\Support\Facades\Hash;

class SingleSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates a single site accessible on both localhost 
     * and the cloudways domain with a super admin owner and a small team.
     */
    public function run(): void
    {
        $this->command->info('🏗️ Creating single site with owner and team...');
        
        try {
            // 1. Create the Super Admin Owner (Site Owner)
            $owner = $this->createOwner();
            
            // 2. Create Team/Admin Members
            $this->createTeamMembers($owner);
            
            // 3. Create a sample regular user for the site
            $this->createRegularUser();
            
            $this->command->info('✅ Single site setup completed successfully!');
            $this->displayLoginCredentials();
            
        } catch (\Exception $e) {
            $this->command->error('❌ Failed to create single site setup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create the super admin owner
     */
    private function createOwner(): User
    {
        $this->command->info('👤 Creating super admin owner...');
        
        $owner = User::updateOrCreate(
            ['email' => 'owner@spsystem.com'],
            [
                'name' => 'Site Owner',
                'password' => Hash::make('owner123'),
                'role' => 'super-admin',
                'subdomain' => null, // No subdomain - accessible on main domains
                'domain' => null,    // Works on both localhost and cloudways domain
                'is_active' => true,
                'phone' => '+1-555-OWNER',
                'bio' => 'Super Administrator with full system access. Can manage the entire platform and add team members.',
                'settings' => json_encode([
                    'theme' => 'dark',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'UTC',
                    'access_domains' => [
                        'localhost',
                        '127.0.0.1',
                        'phplaravel-1399496-5687062.cloudwaysapps.com'
                    ]
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]
        );

        // Assign super-admin role
        if (!$owner->hasRole('super-admin')) {
            $owner->assignRole('super-admin');
        }

        // Create site and template for owner if doesn't exist
        if (!$owner->sites()->exists()) {
            $this->command->info('🏗️ Setting up site and template for owner...');
            $templateCloneService = app(TemplateCloneService::class);
            $success = $templateCloneService->cloneDefaultTemplateForUser($owner);
            
            if ($success) {
                $this->command->info('✅ Owner site setup completed!');
            } else {
                $this->command->warn('⚠️ Template cloning failed for owner');
            }
        }

        return $owner;
    }

    /**
     * Create team/admin members
     */
    private function createTeamMembers(User $owner): void
    {
        $this->command->info('👥 Creating team members...');
        
        // Admin Team Member 1
        $admin1 = User::updateOrCreate(
            ['email' => 'admin@spsystem.com'],
            [
                'name' => 'Admin Manager',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-ADMIN',
                'bio' => 'Site administrator with content and user management permissions.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/New_York'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
            ]
        );

        if (!$admin1->hasRole('admin')) {
            $admin1->assignRole('admin');
        }

        // Team Member 1
        $teamMember1 = User::updateOrCreate(
            ['email' => 'editor@spsystem.com'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('editor123'),
                'role' => 'team-member',
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-EDIT',
                'bio' => 'Content editor responsible for managing site content and pages.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'America/Los_Angeles'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(6),
            ]
        );

        if (!$teamMember1->hasRole('team-member')) {
            $teamMember1->assignRole('team-member');
        }

        // Team Member 2
        $teamMember2 = User::updateOrCreate(
            ['email' => 'designer@spsystem.com'],
            [
                'name' => 'UI Designer',
                'password' => Hash::make('designer123'),
                'role' => 'team-member',
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-DESIGN',
                'bio' => 'UI/UX designer responsible for template design and layout customization.',
                'settings' => json_encode([
                    'theme' => 'dark',
                    'language' => 'en',
                    'notifications' => false,
                    'timezone' => 'America/Chicago'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(12),
            ]
        );

        if (!$teamMember2->hasRole('team-member')) {
            $teamMember2->assignRole('team-member');
        }

        $this->command->info('✅ Team members created successfully!');
    }

    /**
     * Create a regular user for testing
     */
    private function createRegularUser(): void
    {
        $this->command->info('👤 Creating regular user...');
        
        $user = User::updateOrCreate(
            ['email' => 'user@spsystem.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'subdomain' => null,
                'domain' => null,
                'is_active' => true,
                'phone' => '+1-555-USER',
                'bio' => 'Regular site user with basic access permissions.',
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                    'timezone' => 'UTC'
                ]),
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
            ]
        );

        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        $this->command->info('✅ Regular user created successfully!');
    }

    /**
     * Display login credentials for the created users
     */
    private function displayLoginCredentials(): void
    {
        $this->command->info('');
        $this->command->info('🔐 LOGIN CREDENTIALS:');
        $this->command->info('===================');
        $this->command->info('');
        $this->command->info('🏢 SITE OWNER (Super Admin):');
        $this->command->info('Email: owner@spsystem.com');
        $this->command->info('Password: owner123');
        $this->command->info('Role: Super Admin (Full System Access)');
        $this->command->info('');
        $this->command->info('👨‍💼 ADMIN MANAGER:');
        $this->command->info('Email: admin@spsystem.com');
        $this->command->info('Password: admin123');
        $this->command->info('Role: Admin (Site Management)');
        $this->command->info('');
        $this->command->info('✏️ CONTENT EDITOR:');
        $this->command->info('Email: editor@spsystem.com');
        $this->command->info('Password: editor123');
        $this->command->info('Role: Team Member (Content Management)');
        $this->command->info('');
        $this->command->info('🎨 UI DESIGNER:');
        $this->command->info('Email: designer@spsystem.com');
        $this->command->info('Password: designer123');
        $this->command->info('Role: Team Member (Design & Templates)');
        $this->command->info('');
        $this->command->info('👤 REGULAR USER:');
        $this->command->info('Email: user@spsystem.com');
        $this->command->info('Password: user123');
        $this->command->info('Role: User (Basic Access)');
        $this->command->info('');
        $this->command->info('🌐 ACCESS DOMAINS:');
        $this->command->info('- localhost');
        $this->command->info('- phplaravel-1399496-5687062.cloudwaysapps.com');
        $this->command->info('');
    }
}
