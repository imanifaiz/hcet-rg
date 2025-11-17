<?php

namespace Database\Seeders;

use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeedDefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #1. Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@grtech.com',
            'password' => Hash::make('password'),
            'user_type' => UserTypes::ADMIN,
        ]);

        #2. Normal User
        User::create([
            'name' => 'User 1',
            'email' => 'user@grtech.com',
            'password' => Hash::make('password'),
            'user_type' => UserTypes::NORMAL_USER,
        ]);
    }
}
