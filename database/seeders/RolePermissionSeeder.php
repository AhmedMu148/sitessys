<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Template management
            'view-templates',
            'create-templates',
            'edit-templates',
            'delete-templates',
            'manage-templates',
            
            // Site management
            'view-sites',
            'create-sites',
            'edit-sites',
            'delete-sites',
            'manage-sites',
            
            // Content management
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'manage-content',
            
            // Admin panel access
            'access-admin',
            'view-dashboard',
            
            // API access
            'access-api',
            'manage-tokens',
            
            // System settings
            'manage-settings',
            'view-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - Full access
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - Manage their own site and content
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'access-admin',
            'view-dashboard',
            'view-templates',
            'create-templates',
            'edit-templates',
            'delete-templates',
            'manage-templates',
            'view-sites',
            'edit-sites',
            'manage-sites',
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'manage-content',
            'access-api',
            'manage-tokens',
        ]);

        // Team Member - Limited access
        $teamMember = Role::firstOrCreate(['name' => 'team-member']);
        $teamMember->givePermissionTo([
            'access-admin',
            'view-dashboard',
            'view-content',
            'edit-content',
            'view-templates',
        ]);

        // User - Basic access
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo([
            'view-content',
        ]);
    }
}
