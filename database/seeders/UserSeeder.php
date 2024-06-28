<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Bang Admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('admin'),
            ],
            [
                'name' => 'Bang User',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => bcrypt('user'),
            ],
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
