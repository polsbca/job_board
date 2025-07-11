@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="h4 mb-4">My Applications</h1>
            
            @if($applications->isEmpty())
                <div class="alert alert-info">
                    You haven't applied for any jobs yet. <a href="{{ route('jobs.index') }}">Browse available jobs</a>.
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Applied At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <a href="{{ route('jobs.show', $application->job) }}">
                                                    {{ $application->job->title }}
                                                </a>
                                            </td>
                                            <td>{{ $application->job->company_name }}</td>
                                            <td>{{ $application->job->location }}</td>
                                            <td>
                                                <span class="badge bg-{{ $application->getStatusBadgeColor() }}">
                                                    {{ $application->status }}
                                                </span>
                                            </td>
                                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('jobs.show', $application->job) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    View Job
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $applications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
