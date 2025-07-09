<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get employer users
        $employers = User::where('role', 'employer')->get();
        
        if ($employers->isEmpty()) {
            $this->command->warn('No employer users found. Please run UsersTableSeeder first.');
            return;
        }

        $jobs = [
            [
                'title' => 'Senior Laravel Developer',
                'description' => 'We are looking for an experienced Laravel developer to join our team. You will be responsible for building and maintaining web applications using Laravel, Vue.js, and other modern web technologies.',
                'company' => 'Tech Solutions Inc.',
                'location' => 'San Francisco, CA',
                'category' => 'Web Development',
                'salary' => 105000.00, // Average of range
                'type' => 'full-time',
                'status' => 'active',
                'closing_date' => Carbon::now()->addDays(30),
            ],
            [
                'title' => 'Frontend Developer (React)',
                'description' => 'Join our frontend team to build beautiful and responsive user interfaces using React.js. Experience with TypeScript and modern CSS is a plus.',
                'company' => 'Digital Innovations',
                'location' => 'Remote',
                'category' => 'Frontend Development',
                'salary' => 97500.00, // Average of range
                'type' => 'full-time',
                'status' => 'active',
                'closing_date' => Carbon::now()->addDays(45),
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'We are seeking a talented UI/UX Designer to create amazing user experiences. The ideal candidate should have an eye for clean and artful design, possess superior UI skills and be able to translate high-level requirements into interaction flows and artifacts.',
                'company' => 'Tech Solutions Inc.',
                'location' => 'New York, NY',
                'category' => 'Design',
                'salary' => 87500.00, // Average of range
                'type' => 'full-time',
                'status' => 'active',
                'closing_date' => Carbon::now()->addDays(60),
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'Looking for a DevOps Engineer to help us build functional systems that improve customer experience. DevOps Engineer responsibilities include deploying product updates, identifying production issues and implementing integrations that meet customer needs.',
                'company' => 'Digital Innovations',
                'location' => 'Austin, TX',
                'category' => 'DevOps',
                'salary' => 120000.00, // Average of range
                'type' => 'full-time',
                'status' => 'active',
                'closing_date' => Carbon::now()->addDays(30),
            ],
            [
                'title' => 'Mobile App Developer (Flutter)',
                'description' => 'We are looking for a Flutter developer to build cross-platform mobile apps for iOS and Android. Experience with Firebase and state management solutions is a plus.',
                'company' => 'Tech Solutions Inc.',
                'location' => 'Remote',
                'category' => 'Mobile Development',
                'salary' => 95000.00, // Average of range
                'type' => 'full-time',
                'status' => 'inactive', // Changed from 'draft' to 'inactive' to match the database enum
                'closing_date' => Carbon::now()->addDays(90),
            ],
        ];

        foreach ($jobs as $jobData) {
            // Assign job to a random employer
            $employer = $employers->random();
            
            // Update company name to match employer's name if it's a company account
            if (str_contains(strtolower($employer->name), 'tech solutions')) {
                $jobData['company'] = 'Tech Solutions Inc.';
            } elseif (str_contains(strtolower($employer->name), 'digital')) {
                $jobData['company'] = 'Digital Innovations';
            } else {
                $jobData['company'] = $employer->name;
            }
            
            // Create job with the employer's ID
            $job = new Job($jobData);
            $job->user_id = $employer->id;
            
            // Ensure salary is a valid decimal
            if (is_string($job->salary)) {
                // If salary is still a string (e.g., from a range), extract the first number
                if (preg_match('/\$?([\d,]+)/', $job->salary, $matches)) {
                    $job->salary = (float) str_replace([',', '$'], '', $matches[1]);
                }
            }
            
            $job->save();
        }
        
        $this->command->info('Created ' . count($jobs) . ' sample job listings.');
    }
}
