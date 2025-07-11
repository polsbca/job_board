@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Applications</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Job</th>
                <th>Applicant</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>{{ $application->id }}</td>
                    <td>{{ $application->job->title }}</td>
                    <td>{{ $application->user->name }}</td>
                    <td>
                        <span class="badge bg-{{ $application->getStatusBadgeColor() }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                    <td>{{ $application->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No applications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $applications->links() }}
</div>
@endsection
