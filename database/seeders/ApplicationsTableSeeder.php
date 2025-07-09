<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active jobs
        $jobs = Job::where('status', 'active')->get();
        
        if ($jobs->isEmpty()) {
            $this->command->warn('No active jobs found. Please run JobsTableSeeder first.');
            return;
        }
        
        // Get all applicants
        $applicants = User::where('role', 'applicant')->get();
        
        if ($applicants->isEmpty()) {
            $this->command->warn('No applicant users found. Please run UsersTableSeeder first.');
            return;
        }
        
        $applications = [];
        $statuses = ['pending', 'reviewing', 'accepted', 'rejected'];
        
        // Create 2-4 applications per job
        foreach ($jobs as $job) {
            $numApplications = rand(2, 4);
            $jobApplicants = $applicants->random(min($numApplications, $applicants->count()));
            
            foreach ($jobApplicants as $applicant) {
                $status = $statuses[array_rand($statuses)];
                $feedback = null;
                
                // Add feedback for rejected/accepted applications
                if ($status === 'rejected') {
                    $rejectionReasons = [
                        'We appreciate your application, but we have decided to move forward with other candidates who more closely match our requirements.',
                        'Thank you for your interest in this position. After careful consideration, we have decided not to move forward with your application at this time.',
                        'We were impressed with your background, but we have chosen to pursue other candidates whose experience more closely aligns with our needs.'
                    ];
                    $feedback = $rejectionReasons[array_rand($rejectionReasons)];
                } elseif ($status === 'accepted') {
                    $feedback = 'Congratulations! We are impressed with your application and would like to invite you for an interview. We will contact you shortly to schedule a time.';
                }
                
                $applications[] = [
                    'user_id' => $applicant->id,
                    'job_id' => $job->id,
                    'cover_letter' => $this->generateCoverLetter($applicant->name, $job->title, $job->company),
                    'resume_path' => 'resumes/' . strtolower(str_replace(' ', '_', $applicant->name)) . '_resume.pdf',
                    'status' => $status,
                    'feedback' => $feedback,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ];
            }
        }
        
        // Insert applications in chunks
        foreach (array_chunk($applications, 50) as $chunk) {
            Application::insert($chunk);
        }
        
        $this->command->info('Created ' . count($applications) . ' sample job applications.');
    }
    
    /**
     * Generate a sample cover letter.
     *
     * @param string $applicantName
     * @param string $jobTitle
     * @param string $companyName
     * @return string
     */
    private function generateCoverLetter(string $applicantName, string $jobTitle, string $companyName): string
    {
        return "Dear Hiring Manager,\n\n" .
        "I am writing to express my interest in the {$jobTitle} position at {$companyName}. With my background and skills, I am confident in my ability to make a significant contribution to your team.\n\n" .
        "In my previous roles, I have gained valuable experience that aligns well with the requirements for this position. I am particularly drawn to {$companyName} because of its reputation for [specific reason related to company].\n\n" .
        "I am excited about the opportunity to bring my unique skills and experiences to your team. I look forward to the possibility of discussing how my background, skills, and enthusiasm will be a great fit for your organization.\n\n" .
        "Thank you for considering my application. I look forward to the opportunity to discuss my qualifications further.\n\n" .
        "Sincerely,\n" .
        $applicantName;
    }
}
