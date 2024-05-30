<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'permission' => Permission::all()->pluck('id')],
            ['name' => 'editor', 'permission' => [1,26,27,28,29,30,31]],
            // Add more roles as needed
        ];

        foreach ($roles as $key => $roleData) {
            $role = Role::create(['name' => $roleData['name']]);

            // Filter out non-existent permissions
            $permissions = Permission::whereIn('id', $roleData['permission'])->pluck('id');

            if ($permissions->isEmpty()) {
                // Log or handle the case where no valid permissions were found
                continue; // Skip role creation if no valid permissions found
            }

            $role->syncPermissions($permissions);
        }
    }
}
