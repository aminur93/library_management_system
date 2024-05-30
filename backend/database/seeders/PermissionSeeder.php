<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'dashboard-list'],

            ['name' => 'user-list'],
            ['name' => 'user-create'],
            ['name' => 'user-edit'],
            ['name' => 'user-delete'],

            ['name' => 'role-list'],
            ['name' => 'role-create'],
            ['name' => 'role-edit'],
            ['name' => 'role-delete'],

            ['name' => 'permision-list'],
            ['name' => 'permision-create'],
            ['name' => 'permision-edit'],
            ['name' => 'permision-delete'],

            ['name' => 'book-list'],
            ['name' => 'book-create'],
            ['name' => 'book-edit'],
            ['name' => 'book-delete'],

            ['name' => 'author-list'],
            ['name' => 'author-create'],
            ['name' => 'author-edit'],
            ['name' => 'author-delete'],

            ['name' => 'member-list'],
            ['name' => 'member-create'],
            ['name' => 'member-edit'],
            ['name' => 'member-delete'],

            ['name' => 'borrowBook-list'],
            ['name' => 'borrowBook-create'],
            ['name' => 'borrowBook-edit'],
            ['name' => 'borrowBook-delete'],
            ['name' => 'borrowBook-status'],
        ];

        // Insert permissions into the database
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
