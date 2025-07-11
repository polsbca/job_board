<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;

class DashboardController extends Controller
{
    /**
     * Show the applicant dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get recent applications
        $applications = Application::where('user_id', $user->id)
            ->with('job')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recommended jobs
        $recommendedJobs = Job::where('status', 'active')
            ->whereNotIn('id', $applications->pluck('job_id'))
            ->latest()
            ->take(3)
            ->get();
            
        // Safely get the count of saved jobs, defaulting to 0 if relationship doesn't exist
        $savedJobsCount = 0;
        if (method_exists($user, 'savedJobs')) {
            $savedJobsCount = $user->savedJobs()->count();
        }
        
        return view('applicant.dashboard', [
            'applications' => $applications,
            'recommendedJobs' => $recommendedJobs,
            'applicationCount' => $user->applications()->count(),
            'savedJobsCount' => $savedJobsCount,
        ]);
    }
}
