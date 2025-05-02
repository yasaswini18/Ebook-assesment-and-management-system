<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $evaluatorRole = Role::create(['name' => 'evaluator']);
        $guestRole = Role::create(['name' => 'guest']);

        // Create permissions
        $permissions = [
            // Book permissions
            'view books',
            'create books',
            'edit books',
            'delete books',

            // Criteria permissions
            'view criteria',
            'create criteria',
            'edit criteria',
            'delete criteria',

            // Evaluation permissions
            'view evaluations',
            'create evaluations',
            'edit evaluations',
            'delete evaluations',

            // Report permissions
            'view reports',
            'export reports',

            // Dashboard permissions
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Assign specific permissions to evaluator
        $evaluatorRole->givePermissionTo([
            'view books',
            'view criteria',
            'view evaluations',
            'create evaluations',
            'edit evaluations',
            'view reports',
            'export reports',
            'view dashboard',
        ]);

        // Assign limited permissions to guest
        $guestRole->givePermissionTo([
            'view books',
            'view reports',
            'view dashboard',
        ]);
    }
}
