<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin = User::create([
            'name' => 'Amos',
            'email' => 'homsindayizeye@gmail.com',
            'password' => Hash::make('Ami@1234'),
            'role' => 'admin',
        ]);
        

        // Create agent user
        $agent = User::create([
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'created_by' => $admin->id,
        ]);

        // Create client users
        for ($i = 1; $i <= 5; $i++) {
            $client = User::create([
                'name' => "Client User $i",
                'email' => "client$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'client',
                'created_by' => $agent->id,
            ]);
            

            // Create tank for each client
            Tank::create([
                'user_id' => $client->id,
                'location' => "Location $i",
                'capacity' => rand(500, 2000),
                'current_level' => rand(100, 1500),
                'ph_level' => rand(60, 85) / 10,
                'chloride_level' => rand(100, 300),
                'fluoride_level' => rand(10, 25) / 10,
                'nitrate_level' => rand(20, 80),
                'status' => 'active',
            ]);
        }
    }
}