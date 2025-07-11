<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    /**
     * Display a listing of the jobs.
     */
    public function index()
    {
        $jobs = Job::with(['employer', 'applications'])->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('admin.jobs.create');
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Job::create($validated);

        return Redirect::route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified job.
     */
    public function show(Job $job)
    {
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $job->update($validated);

        return Redirect::route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return Redirect::route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
