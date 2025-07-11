@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Application #{{ $application->id }}</h1>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Applicant Details</div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $application->user->name }}</p>
            <p><strong>Email:</strong> {{ $application->user->email }}</p>
            <p><strong>Phone:</strong> {{ $application->user->phone ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Job Details</div>
        <div class="card-body">
            <p><strong>Title:</strong> {{ $application->job->title }}</p>
            <p><strong>Company:</strong> {{ $application->job->company }}</p>
            <p><strong>Location:</strong> {{ $application->job->location }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
            <span>Application</span>
            <span class="badge bg-{{ $application->getStatusBadgeColor() }}">{{ ucfirst($application->status) }}</span>
        </div>
        <div class="card-body">
            <p><strong>Cover Letter:</strong></p>
            <p>{{ $application->cover_letter }}</p>

            <p><strong>Resume:</strong> <a href="{{ Storage::url($application->resume_path) }}" target="_blank">Download</a></p>
        </div>
    </div>

    <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST" class="d-inline">
        @csrf
        @method('PUT')
        <div class="input-group mb-3" style="max-width: 300px;">
            <select name="status" class="form-select" required>
                <option value="pending" @selected($application->status==='pending')>Pending</option>
                <option value="reviewing" @selected($application->status==='reviewing')>Reviewing</option>
                <option value="accepted" @selected($application->status==='accepted')>Accepted</option>
                <option value="rejected" @selected($application->status==='rejected')>Rejected</option>
            </select>
            <button class="btn btn-primary">Update Status</button>
        </div>
    </form>

    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary mt-2">Back to list</a>
</div>
@endsection
