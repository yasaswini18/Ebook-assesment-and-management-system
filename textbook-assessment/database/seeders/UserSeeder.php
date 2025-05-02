<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        ]);
        $admin->assignRole('admin');

        // Create evaluator user
        $evaluator = User::create([
            'name' => 'Evaluator User',
            'email' => 'evaluator@example.com',
            'password' => Hash::make('password'),
        ]);
        $evaluator->assignRole('evaluator');

        // Create guest user
        $guest = User::create([
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'password' => Hash::make('password'),
        ]);
        $guest->assignRole('guest');
    }
}
