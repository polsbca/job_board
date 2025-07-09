<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing data...');
        $this->call([
            // Clear data in reverse order of dependencies
            \Database\Seeders\ClearDataSeeder::class,
        ]);

        // Seed data
        $this->command->info('Seeding users...');
        $this->call([
            UsersTableSeeder::class,
        ]);

        $this->command->info('Seeding jobs...');
        $this->call([
            JobsTableSeeder::class,
        ]);

        $this->command->info('Seeding applications...');
        $this->call([
            ApplicationsTableSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
