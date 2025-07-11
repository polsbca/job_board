<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications.
     */
    public function index()
    {
        $applications = Application::with(['user', 'job'])->latest()->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Display the specified application details.
     */
    public function show(Application $application)
    {
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Update an application's status.
     */
    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,accepted,rejected',
        ]);

        $application->update(['status' => $validated['status']]);

        return Redirect::back()->with('success', 'Application status updated.');
    }
}
