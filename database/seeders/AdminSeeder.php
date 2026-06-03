<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin account
        User::firstOrCreate(
            ['email' => 'admin@traveltracker.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ]
        );

        // Create a sample staff account
        User::firstOrCreate(
            ['email' => 'staff@traveltracker.com'],
            [
                'name'     => 'Staff User',
                'password' => Hash::make('staff1234'),
                'role'     => 'staff',
            ]
        );
    }
}
