@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="jumbotron bg-light p-5 rounded-lg m-3">
        <h1 class="display-4">Find Your Dream Job</h1>
        <p class="lead">Search thousands of job listings to find the next step in your career.</p>
        
        <!-- Search Form -->
        <form action="{{ route('jobs.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Job title, keywords, or company">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="location" class="form-control form-control-lg" placeholder="Location">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">Search</button>
            </div>
        </form>
    </div>

    <!-- Featured Jobs -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Jobs</h2>
            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">View All Jobs</a>
        </div>

        <div class="row" id="featured-jobs">
            <!-- Jobs will be loaded here via AJAX -->
            <div class="col-12 text-center my-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="mb-5">
        <h2 class="mb-4">Browse by Category</h2>
        <div class="row g-4">
            @php
                $categories = [
                    'Web Development' => 'code',
                    'Design' => 'paint-brush',
                    'Marketing' => 'bullhorn',
                    'Customer Service' => 'headset',
                    'Sales' => 'dollar-sign',
                    'Healthcare' => 'heartbeat',
                    'Education' => 'graduation-cap',
                    'Finance' => 'chart-line'
                ];
            @endphp
            
            @foreach(array_chunk($categories, 4, true) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $category => $icon)
                        <div class="col-md-3">
                            <a href="{{ route('jobs.index', ['category' => $category]) }}" class="text-decoration-none">
                                <div class="card h-100 hover-shadow">
                                    <div class="card-body text-center">
                                        <i class="fas fa-{{ $icon }} fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{ $category }}</h5>
                                        <p class="text-muted">
                                            {{ rand(10, 50) }} jobs available
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5 bg-light rounded-3 mb-5">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h4>Create an Account</h4>
                    <p class="text-muted">Sign up as a job seeker or employer to get started.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h4>Find Jobs or Candidates</h4>
                    <p class="text-muted">Search and apply for jobs or find the perfect candidate.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-briefcase fa-2x"></i>
                    </div>
                    <h4>Get Hired or Hire</h4>
                    <p class="text-muted">Connect with potential employers or employees.</p>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load featured jobs via AJAX
    axios.get('/api/jobs', {
        params: {
            featured: true,
            limit: 4
        }
    })
    .then(function(response) {
        const jobsContainer = document.getElementById('featured-jobs');
        const jobs = response.data.data;
        
        if (jobs.length === 0) {
            jobsContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted">No featured jobs available at the moment.</p></div>';
            return;
        }
        
        let html = '';
        jobs.forEach(job => {
            html += `
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${job.title}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">${job.company}</h6>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i> ${job.location}<br>
                                <i class="fas fa-dollar-sign text-muted me-1"></i> $${job.salary.toLocaleString()}/year
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">${job.type}</span>
                                <a href="/jobs/${job.id}" class="btn btn-sm btn-outline-primary">View Job</a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Posted ${new Date(job.created_at).toLocaleDateString()}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        
        jobsContainer.innerHTML = html;
    })
    .catch(function(error) {
        console.error('Error loading featured jobs:', error);
        document.getElementById('featured-jobs').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">Error loading featured jobs. Please try again later.</div>
            </div>
        `;
    });
});
</script>
@endpush

<style>
.hover-shadow {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.jumbotron {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.5rem !important;
}
</style>
@endsection
