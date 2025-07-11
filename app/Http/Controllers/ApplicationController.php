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
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $validated['job_id'],
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
        ]);

        // Store the resume file
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $application->update(['resume_path' => $path]);
        }

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
