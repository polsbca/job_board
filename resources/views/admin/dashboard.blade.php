<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }} 
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row gy-4">

            <!-- Stats Cards -->
            <div class="row g-4">
                @php
                    $cards = [
                        ['title' => 'Total Jobs', 'count' => $stats['totalJobs'], 'color' => 'blue'],
                        ['title' => 'Total Users', 'count' => $stats['totalUsers'], 'color' => 'green'],
                        ['title' => 'Total Applications', 'count' => $stats['totalApplications'], 'color' => 'yellow'],
                        ['title' => 'Pending Applications', 'count' => $stats['pendingApplications'], 'color' => 'red'],
                    ];
                @endphp

                @foreach($cards as $index => $card)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background-color:var(--bs-{{ $card['color'] }}-100, #e9ecef);color:var(--bs-{{ $card['color'] }}-600,#0d6efd);">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='currentColor' viewBox='0 0 24 24'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted small">{{ $card['title'] }}</div>
                                    <div class="fs-4 fw-bold">{{ $card['count'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recent Job Listings -->
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h3 class="h5 mb-0">Recent Job Listings</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJobs as $job)
                                    <tr>
                                        <td><a href="#" class="fw-semibold text-primary">{{ $job->title }}</a></td>
                                        <td>{{ $job->company }}</td>
                                        <td>{{ $job->location }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ ucfirst($job->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No recent jobs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h3 class="h5 mb-0">Recent Applications</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Applicant</th>
                                    <th>Job Title</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $application)
                                    <tr>
                                        <td class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <span class="fw-bold">{{ substr($application->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $application->user->name }}</div>
                                                <div class="text-muted small">{{ $application->user->email }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $application->job->title }}</td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'reviewed' => 'info',
                                                    'accepted' => 'success',
                                                    'rejected' => 'danger',
                                                ];
                                                $bsColor = $statusColors[$application->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $bsColor }}">{{ ucfirst($application->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No recent applications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
