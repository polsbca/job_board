<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalJobs' => Job::count(),
            'totalUsers' => User::count(),
            'totalApplications' => Application::count(),
            'pendingApplications' => Application::where('status', 'pending')->count(),
        ];

        $recentJobs = Job::latest()->take(5)->get();
        $recentApplications = Application::with(['user', 'job'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentJobs', 'recentApplications'));
    }
}
