<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\TemplateCloneService;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating admin user...');
        
        try {
            // Create or update the admin user
            $admin = User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'System Administrator',
                    'password' => Hash::make('admin123'),
                    'subdomain' => 'admin',
                    'domain' => null,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Assign super-admin role
            if (!$admin->hasRole('super-admin')) {
                $admin->assignRole('super-admin');
                $this->command->info('✅ Super-admin role assigned to admin user');
            }

            // Clone template for admin user if no site exists
            if (!$admin->sites()->exists()) {
                $this->command->info('🏗️ Setting up default site and template for admin...');
                $templateCloneService = app(TemplateCloneService::class);
                $success = $templateCloneService->cloneDefaultTemplateForUser($admin);
                
                if ($success) {
                    $this->command->info('✅ Admin user setup completed with default template!');
                } else {
                    $this->command->warn('⚠️ Template cloning failed, but admin user was created');
                }
            } else {
                $this->command->info('✅ Admin user already has a site configured!');
            }

            $this->command->info('');
            $this->command->info('🔐 Admin Login Credentials:');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: admin123');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Failed to create admin user: ' . $e->getMessage());
            throw $e;
        }
    }
}
