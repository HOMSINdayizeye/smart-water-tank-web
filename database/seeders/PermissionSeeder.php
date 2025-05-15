<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            ['name' => 'view_users', 'description' => 'View all users'],
            ['name' => 'create_users', 'description' => 'Create new users'],
            ['name' => 'update_users', 'description' => 'Update user information'],
            ['name' => 'delete_users', 'description' => 'Delete users'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to the first agent
        $agent = User::where('role', 'agent')->first();
        if ($agent) {
            $agent->permissions()->attach(Permission::all());
        }
    }
}