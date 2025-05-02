<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the role seeder first to create roles and permissions
        $this->call(RoleSeeder::class);

        // Then create users with roles
        $this->call(UserSeeder::class);

        // Create demo data for books and criteria
        $this->call(BookSeeder::class);
        $this->call(CriteriaSeeder::class);
    }
}
