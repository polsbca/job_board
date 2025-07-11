<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a new job application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:job_listings,id',
            'cover_letter' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Store the resume file first so we have the path
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
        } else {
            // This should not happen due to validation, but set path to null as fallback
            $path = null;
        }

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $validated['job_id'],
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
            'resume_path' => $path,
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Display a listing of the user's applications.
     */
    public function index()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with('job')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }
}
