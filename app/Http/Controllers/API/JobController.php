<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobCollection;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    /**
     * Create a new JobController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('can:update,job')->only(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\JobCollection
     */
    public function index(Request $request)
    {
        $query = Job::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->has('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->has('location'), function ($query) use ($request) {
                $query->where('location', 'like', '%' . $request->location . '%');
            })
            ->when($request->has('q'), function ($query) use ($request) {
                $search = $request->q;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%");
                });
            });

        // If not an admin, only show active jobs to non-owners
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('status', 'active');
        }

        // For employers, show their own jobs regardless of status
        if ($request->user() && $request->user()->role === 'employer') {
            $query->orWhere('user_id', $request->user()->id);
        }

        $jobs = $query->latest()->paginate(10);

        return new JobCollection($jobs);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'salary' => 'required|string|max:100',
            'type' => ['required', 'string', Rule::in(['full-time', 'part-time', 'contract', 'temporary', 'internship', 'volunteer'])],
            'closing_date' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $job = $request->user()->jobs()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Job created successfully',
            'data' => new JobResource($job->load('user'))
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \App\Http\Resources\JobResource
     */
    public function show(Job $job)
    {
        // Only allow viewing if job is active or user is the owner/admin
        if ($job->status !== 'active' && 
            !(auth()->check() && 
             (auth()->user()->id === $job->user_id || auth()->user()->isAdmin()))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to this job'
            ], 403);
        }

        return new JobResource($job->load(['user', 'applications']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'company' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|required|string|max:100',
            'type' => ['sometimes', 'required', 'string', Rule::in(['full-time', 'part-time', 'contract', 'temporary', 'internship', 'volunteer'])],
            'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'active', 'paused', 'closed'])],
            'closing_date' => 'sometimes|required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user is authorized to update this job
        if ($request->user()->cannot('update', $job)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this job'
            ], 403);
        }

        $job->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Job updated successfully',
            'data' => new JobResource($job->load('user'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Job $job)
    {
        // Check if user is authorized to delete this job
        if (auth()->user()->cannot('delete', $job)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this job'
            ], 403);
        }

        $job->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Job deleted successfully'
        ]);
    }
}
