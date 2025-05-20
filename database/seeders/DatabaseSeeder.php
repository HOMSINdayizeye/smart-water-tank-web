<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create default agent
        User::create([
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'password' => Hash::make('password123'),
            'role' => 'agent',
            'created_by' => 1, // Created by admin
        ]);

        // Create default client
        $client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'created_by' => 2, // Created by agent
        ]);

        // Create tank for client
        $client->tank()->create([
            'location' => 'Default Location',
            'capacity' => 1000,
            'current_level' => 500,
            'ph_level' => 7.0,
            'chloride_level' => 200,
            'fluoride_level' => 1.0,
            'nitrate_level' => 40,
            'status' => 'active',
        ]);

        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create default agent
        User::create([
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'password' => Hash::make('password123'),
            'role' => 'agent',
            'created_by' => 1, // Created by admin
        ]);

        // Create default client
        $client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'created_by' => 2, // Created by agent
        ]);

        // Create tank for client
        $client->tank()->create([
            'location' => 'Default Location',
            'capacity' => 1000,
            'current_level' => 500,
            'ph_level' => 7.0,
            'chloride_level' => 200,
            'fluoride_level' => 1.0,
            'nitrate_level' => 40,
            'status' => 'active',
        ]);
    }
}