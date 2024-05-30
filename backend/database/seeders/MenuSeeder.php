<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'permission_id' => 1,
                'title' => 'Dashboard',
                'icon' => 'mdi mdi-view-dashboard',
                'route' => '/dashboard',
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => false,
            ],

            [
                'permission_id' => 18,
                'title' => 'Author',
                'icon' => 'mdi mdi-account-tie',
                'route' => '/author',
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => false,
            ],

            [
                'permission_id' => 22,
                'title' => 'Member',
                'icon' => 'mdi mdi-account-group',
                'route' => '/member',
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => false,
            ],
            [
                'permission_id' => 14,
                'title' => 'Book',
                'icon' => 'mdi mdi-book-open-variant',
                'route' => '/book',
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => false,
            ],
            [
                'permission_id' => 26,
                'title' => 'Borrowed Book',
                'icon' => 'mdi mdi-book-open',
                'route' => '/borrow-book',
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => false,
            ],
            [
                'permission_id' => null,
                'title' => 'User Management',
                'icon' => 'mdi mdi-account-supervisor',
                'route' => null,
                'header_menu' => false,
                'sidebar_menu' => true,
                'dropdown' => true,
            ]
        ];

        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
