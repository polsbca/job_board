@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Job #{{ $job->id }}</h1>
        <div>
            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning me-2">Edit</a>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $job->title }}</h4>
            <p class="text-muted mb-2">{{ $job->company }} &middot; {{ $job->location }}</p>
            <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($job->status) }}</span>
            <hr>
            <p>{!! nl2br(e($job->description)) !!}</p>
            <ul class="list-unstyled mb-0">
                <li><strong>Category:</strong> {{ $job->category }}</li>
                <li><strong>Salary:</strong> {{ number_format($job->salary,2) }}</li>
                <li><strong>Type:</strong> {{ ucfirst($job->type) }}</li>
                <li><strong>Posted:</strong> {{ $job->created_at->toDayDateTimeString() }}</li>
                <li><strong>Closing Date:</strong> {{ $job->closing_date ? $job->closing_date->toFormattedDateString() : 'N/A' }}</li>
            </ul>
        </div>
    </div>

    <h4>Applications ({{ $job->applications->count() }})</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Applicant</th>
                    <th>Cover Letter</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($job->applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->user->name }}<br><small class="text-muted">{{ $application->user->email }}</small></td>
                        <td>{{ Str::limit($application->cover_letter, 50) }}</td>
                        <td><span class="badge bg-{{ $application->getStatusBadgeColor() }}">{{ ucfirst($application->status) }}</span></td>
                        <td>{{ $application->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
