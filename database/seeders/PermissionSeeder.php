<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permissions = [
            'create account',
            'edit user info',
            'delete user',
            'view user',
            'view all users',
            'create tank',
            'edit tank info',
            'view notification',
            'view reports',
            'edit reports',
            'manage alerts',
            'view alerts',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}