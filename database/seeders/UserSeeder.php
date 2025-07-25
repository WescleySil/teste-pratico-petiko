<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'is_admin' => 1,
            ]
        );
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'testuser@test.com',
            'password' => Hash::make('12345678'),
            'is_admin' => 0,
        ]);
    }
}
