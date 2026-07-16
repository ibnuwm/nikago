<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.restore',

            // Role management
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Wedding management
            'weddings.view',
            'weddings.create',
            'weddings.update',
            'weddings.delete',

            // Invitation management
            'invitations.view',
            'invitations.create',
            'invitations.update',
            'invitations.delete',
            'invitations.publish',

            // Guest management
            'guests.view',
            'guests.create',
            'guests.update',
            'guests.delete',

            // RSVP management
            'rsvps.view',
            'rsvps.manage',

            // Vendor management
            'vendors.view',
            'vendors.create',
            'vendors.update',
            'vendors.delete',

            // Marketplace
            'marketplace.view',
            'marketplace.create',
            'marketplace.update',
            'marketplace.delete',

            // Payment management
            'payments.view',
            'payments.process',
            'payments.refund',

            // Timeline management
            'timelines.view',
            'timelines.create',
            'timelines.update',
            'timelines.delete',

            // Subscription management
            'subscriptions.view',
            'subscriptions.manage',

            // CMS management
            'cms.view',
            'cms.create',
            'cms.update',
            'cms.delete',

            // CRM management
            'crm.view',
            'crm.create',
            'crm.update',
            'crm.delete',

            // Analytics
            'analytics.view',
            'analytics.export',

            // Seating management
            'seatings.view',
            'seatings.create',
            'seatings.update',
            'seatings.delete',

            // Notification management
            'notifications.view',
            'notifications.send',
            'notifications.manage',

            // System management
            'system.settings',
            'system.maintenance',
            'system.logs',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Reset cache before assigning permissions to roles
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            'super-admin' => [
                'description' => 'Full system access',
                'permissions' => $permissions,
            ],
            'admin' => [
                'description' => 'Wedding admin with full access to their wedding',
                'permissions' => [
                    'users.view',
                    'users.create',
                    'users.update',
                    'weddings.view',
                    'weddings.create',
                    'weddings.update',
                    'weddings.delete',
                    'invitations.view',
                    'invitations.create',
                    'invitations.update',
                    'invitations.delete',
                    'invitations.publish',
                    'guests.view',
                    'guests.create',
                    'guests.update',
                    'guests.delete',
                    'rsvps.view',
                    'rsvps.manage',
                    'vendors.view',
                    'vendors.create',
                    'vendors.update',
                    'vendors.delete',
                    'marketplace.view',
                    'marketplace.create',
                    'marketplace.update',
                    'marketplace.delete',
                    'payments.view',
                    'payments.process',
                    'timelines.view',
                    'timelines.create',
                    'timelines.update',
                    'timelines.delete',
                    'seatings.view',
                    'seatings.create',
                    'seatings.update',
                    'seatings.delete',
                    'analytics.view',
                    'analytics.export',
                    'notifications.view',
                    'notifications.send',
                    'crm.view',
                    'crm.create',
                    'crm.update',
                    'crm.delete',
                ],
            ],
            'planner' => [
                'description' => 'Wedding planner with limited access',
                'permissions' => [
                    'weddings.view',
                    'invitations.view',
                    'invitations.create',
                    'invitations.update',
                    'guests.view',
                    'guests.create',
                    'guests.update',
                    'rsvps.view',
                    'rsvps.manage',
                    'vendors.view',
                    'timelines.view',
                    'seatings.view',
                    'analytics.view',
                    'notifications.view',
                    'notifications.send',
                ],
            ],
            'vendor' => [
                'description' => 'Vendor with limited access',
                'permissions' => [
                    'weddings.view',
                    'marketplace.view',
                    'marketplace.create',
                    'marketplace.update',
                    'analytics.view',
                    'notifications.view',
                ],
            ],
            'guest' => [
                'description' => 'Wedding guest with minimal access',
                'permissions' => [
                    'weddings.view',
                    'invitations.view',
                    'rsvps.view',
                ],
            ],
        ];

        foreach ($roles as $roleName => $roleData) {
            $role = Role::findOrCreate($roleName);
            $role->syncPermissions($roleData['permissions']);
        }
    }
}
