<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-waste-types',
            'manage-deposits',
            'manage-withdrawals',
            'view-reports',
            'deposit-waste',
            'withdraw-balance',
            'view-history'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $nasabahRole = Role::create(['name' => 'nasabah']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([
            'manage-users',
            'manage-waste-types', 
            'manage-deposits',
            'manage-withdrawals',
            'view-reports'
        ]);

        $nasabahRole->givePermissionTo([
            'deposit-waste',
            'withdraw-balance',
            'view-history'
        ]);
    }
}
