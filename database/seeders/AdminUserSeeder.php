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
        // Create or update the admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'subdomain' => 'admin',
                'role' => 'super-admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign super-admin role
        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }

        // Clone template for admin user if no site exists
        if (!$admin->sites()->exists()) {
            $templateCloneService = app(TemplateCloneService::class);
            $templateCloneService->cloneDefaultTemplateForUser($admin);
            
            echo "✅ Admin user setup completed with default template!\n";
        } else {
            echo "✅ Admin user already has a site configured!\n";
        }

        echo "Admin Login Credentials:\n";
        echo "Email: admin@example.com\n";
        echo "Password: admin123\n";
    }
}
