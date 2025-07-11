@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Applicant Dashboard</h1>
            <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Applications</h6>
                            <h2 class="mb-0">{{ $applicationCount }}</h2>
                        </div>
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('applications.index') }}" class="text-white">
                        View all applications <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Saved Jobs</h6>
                            <h2 class="mb-0">{{ $savedJobsCount }}</h2>
                        </div>
                        <i class="fas fa-bookmark fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="#" class="text-white">
                        View saved jobs <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Applications</h5>
            <a href="{{ route('applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            @if($applications->isEmpty())
                <div class="text-center p-4">
                    <p class="text-muted mb-4">You haven't applied to any jobs yet.</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($applications as $application)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('jobs.show', $application->job) }}">{{ $application->job->title }}</a>
                                    </h6>
                                    <p class="mb-1 text-muted">
                                        {{ $application->job->company_name }} • {{ $application->job->location }}
                                    </p>
                                    <small class="text-muted">
                                        Applied on {{ $application->created_at->format('M d, Y') }}
                                        <span class="mx-2">•</span>
                                        Status: 
                                        <span class="badge bg-{{ $application->status === 'submitted' ? 'primary' : ($application->status === 'under_review' ? 'info' : ($application->status === 'accepted' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </small>
                                </div>
                                <a href="{{ route('jobs.show', $application->job) }}" class="btn btn-sm btn-outline-primary">View Job</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recommended Jobs -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recommended Jobs</h5>
        </div>
        <div class="card-body">
            @if($recommendedJobs->isEmpty())
                <p class="text-muted mb-0">No recommended jobs at the moment. Check back later!</p>
            @else
                <div class="row g-4">
                    @foreach($recommendedJobs as $job)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none">
                                            {{ $job->title }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small mb-2">
                                        {{ $job->company_name }} • {{ $job->location }}
                                    </p>
                                    <div class="mb-3">
                                        <span class="badge bg-primary">{{ $job->type }}</span>
                                        @if($job->category)
                                            <span class="badge bg-secondary">{{ $job->category }}</span>
                                        @endif
                                    </div>
                                    <p class="card-text small text-muted">
                                        {{ Str::limit($job->description, 100) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-primary w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
