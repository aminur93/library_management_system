<?php

namespace Database\Seeders;

use App\Models\MenuDropdown;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuDropDownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu_dropdowns = [

            [
                'menu_id' => 6,
                'permission_id' => 2,
                'title' => 'User',
                'icon' => 'mdi mdi-plus',
                'route' => '/user',
            ],

            [
                'menu_id' => 6,
                'permission_id' => 6,
                'title' => 'Role',
                'icon' => 'mdi mdi-plus',
                'route' => '/role',
            ],

            [
                'menu_id' => 6,
                'permission_id' => 10,
                'title' => 'Permission',
                'icon' => 'mdi mdi-plus',
                'route' => '/permission',
            ]
        ];

        foreach ($menu_dropdowns as $menu_dropdown) {
            MenuDropdown::create($menu_dropdown);
        }
    }
}
