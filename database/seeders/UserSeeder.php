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
    // public function run(): void
    // {

    //     if (!Role::where('name', 'admin')->exists()) {
    //         Role::create(['name' => 'admin']);
    //     }

    //     if (!Role::where('name', 'user')->exists()) {
    //         Role::create(['name' => 'user']);
    //     }

    //     $admin = User::firstOrCreate([
    //         'email' => 'admin@gmail.com',
    //     ], [
    //         'name' => 'Admin',
    //         'password' => bcrypt('123')
    //     ]);

    //     if (!$admin->hasRole('admin')) {
    //         $admin->assignRole('admin');
    //     }

    //     $user = User::firstOrCreate([
    //         'email' => 'user@gmail.com',
    //     ], [
    //         'name' => 'Penumpang',
    //         'password' => bcrypt('123')
    //     ]);

    //     if (!$user->hasRole('user')) {
    //         $user->assignRole('user');
    //     }
    // }
}
