<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   public function run(): void
    {
        // Create the Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create a Regular User for assigning tasks
        User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
