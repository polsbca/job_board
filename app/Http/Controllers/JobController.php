<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        // Get all unique categories from jobs
        $categories = Job::select('category')
            ->distinct()
            ->pluck('category')
            ->filter() // Remove any null or empty values
            ->values() // Reset array keys
            ->toArray();

        // Get all jobs with pagination
        $jobs = Job::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('jobs.index', compact('jobs', 'categories'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'nullable|decimal:2',
            'category' => 'nullable|string',
            'type' => 'required|in:full-time,part-time,contract,freelance,internship',
        ]);

        $job = Job::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'company' => $validated['company'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'salary' => $validated['salary'],
            'category' => $validated['category'],
            'type' => $validated['type'],
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job listing created successfully');
    }

    /**
     * Display the specified job.
     */
    public function show(Job $job)
    {
        // Load user relationship with bio and website
        $job->load('user');
        
        // Check if current user has applied (only for applicants)
        $hasApplied = false;
        if (auth()->check() && auth()->user()->role === 'applicant') {
            $hasApplied = $job->applications()->where('user_id', auth()->id())->exists();
        }
        
        return view('jobs.show', compact('job', 'hasApplied'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'nullable|decimal:2',
            'category' => 'nullable|string',
            'type' => 'required|in:full-time,part-time,contract,internship',
        ]);

        $job->update([
            'title' => $validated['title'],
            'company' => $validated['company'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'salary' => $validated['salary'],
            'category' => $validated['category'],
            'type' => $validated['type'],
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job listing updated successfully');
    }

    /**
     * Remove the specified job.
     */
    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully');
    }

    /**
     * Search jobs with filters.
     */
    public function search(Request $request)
    {
        $query = Job::query()->with('user');

        // Apply filters
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('company', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('salary')) {
            $query->where('salary', '>=', $request->salary);
        }

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $request->page);

        return response()->json([
            'data' => $jobs->items(),
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'total' => $jobs->total(),
        ]);
    }
}
