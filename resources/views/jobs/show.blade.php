@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Job Info -->
        <div class="col-lg-8 mb-4">
            <h1 class="mb-1">{{ $job->title }}</h1>
            <h5 class="text-muted mb-3">{{ $job->company }}</h5>
            <p>
                <span class="badge bg-primary me-2">{{ ucfirst($job->type) }}</span>
                <span class="badge bg-secondary me-2">{{ $job->category }}</span>
                <i class="fas fa-map-marker-alt text-muted me-1"></i>{{ $job->location }}
            </p>
            <p class="h5 text-success">${{ number_format($job->salary) }}/year</p>
            <hr>
            <h4>Description</h4>
            <p>{!! nl2br(e($job->description)) !!}</p>

            @if($job->requirements)
                <h4 class="mt-4">Requirements</h4>
                <p>{!! nl2br(e($job->requirements)) !!}</p>
            @endif

            @if($job->benefits)
                <h4 class="mt-4">Benefits</h4>
                <p>{!! nl2br(e($job->benefits)) !!}</p>
            @endif

            <!-- Application form for applicants -->
            @auth
                @if(auth()->user()->role === 'applicant')
                    @if($hasApplied)
                        <div class="alert alert-success">You have already applied to this job.</div>
                    @else
                        <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->id }}">
                            <div class="mb-3">
                                <label class="form-label">Cover Letter</label>
                                <textarea class="form-control" name="cover_letter" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Resume (PDF/DOC)</label>
                                <input type="file" class="form-control" name="resume" accept=".pdf,.doc,.docx" required>
                            </div>
                            <button class="btn btn-primary" type="submit">Apply Now</button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">Company Info</h5>
                    <p class="small text-muted mb-0">{{ $job->employer->bio ?? 'No information provided.' }}</p>
                    @if($job->employer->website)
                        <a href="{{ $job->employer->website }}" target="_blank" class="d-block mt-2">Website</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
