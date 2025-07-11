@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Jobs</h1>

    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary mb-3">Create Job</a>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Status</th>
                <th>Posted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company }}</td>
                    <td>{{ $job->location }}</td>
                    <td><span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($job->status) }}</span></td>
                    <td>{{ $job->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No jobs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $jobs->links() }}
</div>
@endsection
