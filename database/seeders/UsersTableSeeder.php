<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'bio' => 'System administrator with full access to the application.',
            'email_verified_at' => now(),
        ]);

        // Create employer users
        $employers = [
            [
                'name' => 'Tech Solutions Inc.',
                'email' => 'employer1@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'phone' => '+1987654321',
                'bio' => 'Leading technology solutions provider specializing in web and mobile applications.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Digital Innovations',
                'email' => 'employer2@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'phone' => '+1555123456',
                'bio' => 'Innovative digital agency focused on creating cutting-edge solutions.',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($employers as $employer) {
            User::create($employer);
        }

        // Create applicant users
        $applicants = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'phone' => '+1122334455',
                'bio' => 'Experienced web developer with 5+ years of experience in Laravel and Vue.js.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'phone' => '+12223334444',
                'bio' => 'UI/UX designer with a passion for creating beautiful and functional user interfaces.',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Alex Johnson',
                'email' => 'alex.johnson@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'phone' => '+13334445555',
                'bio' => 'Full-stack developer with expertise in modern JavaScript frameworks.',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($applicants as $applicant) {
            User::create($applicant);
        }
    }
}
