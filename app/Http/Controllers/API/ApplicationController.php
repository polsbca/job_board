<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationCollection;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * Create a new ApplicationController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('can:update,application')->only(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ApplicationCollection
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Application::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('job_id'), function ($query) use ($request) {
                $query->where('job_id', $request->job_id);
            })
            ->when($user->role === 'applicant', function ($query) use ($user) {
                // Applicants can only see their own applications
                $query->where('user_id', $user->id);
            })
            ->when($user->role === 'employer', function ($query) use ($user) {
                // Employers can see applications for their jobs
                $query->whereHas('job', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            });

        $applications = $query->with(['user', 'job'])->latest()->paginate(10);

        return new ApplicationCollection($applications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_listings,id',
            'cover_letter' => 'required|string|min:50|max:2000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if job exists and is active
        $job = Job::findOrFail($request->job_id);
        if ($job->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot apply to this job. The job is not active.'
            ], 403);
        }

        // Check if user has already applied to this job
        $existingApplication = Application::where('user_id', $request->user()->id)
            ->where('job_id', $request->job_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already applied to this job'
            ], 409);
        }

        // Store the resume file
        $resumePath = $request->file('resume')->store('resumes', 'public');

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully',
            'data' => new ApplicationResource($application->load(['user', 'job']))
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \App\Http\Resources\ApplicationResource
     */
    public function show(Application $application)
    {
        $user = auth()->user();
        
        // Check if user is authorized to view this application
        if ($user->cannot('view', $application)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to view this application'
            ], 403);
        }

        return new ApplicationResource($application->load(['user', 'job']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Application $application)
    {
        $user = $request->user();
        
        // Only allow updating status for employers, and only specific fields for applicants
        if ($user->role === 'employer') {
            $validator = Validator::make($request->all(), [
                'status' => ['required', 'string', Rule::in(['reviewing', 'accepted', 'rejected'])],
                'feedback' => 'nullable|string|max:1000',
            ]);
        } else {
            // Applicants can only withdraw their application
            if ($application->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can only withdraw pending applications'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'status' => ['required', 'string', Rule::in(['withdrawn'])],
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user is authorized to update this application
        if ($user->cannot('update', $application)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this application'
            ], 403);
        }

        $application->update($request->only(['status', 'feedback']));

        return response()->json([
            'status' => 'success',
            'message' => 'Application updated successfully',
            'data' => new ApplicationResource($application->load(['user', 'job']))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Application $application)
    {
        $user = auth()->user();
        
        // Check if user is authorized to delete this application
        if ($user->cannot('delete', $application)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this application'
            ], 403);
        }

        // Delete the resume file
        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Application deleted successfully'
        ]);
    }
}
