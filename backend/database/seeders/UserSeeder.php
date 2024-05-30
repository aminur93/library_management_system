<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password'),
        ])->assignRole('editor');
    }
}
