@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">Job Listings</h1>
        </div>
    </div>
    <div class="row">
        <!-- Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0">Filters</h2>
                </div>
                <div class="card-body">
                    <form id="filters-form">
                        <div class="mb-3">
                            <label class="form-label">Keyword</label>
                            <input type="text" class="form-control" name="keyword" placeholder="Job title, company...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" placeholder="City, state, or remote">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Type</label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimum Salary</label>
                            <input type="number" class="form-control" name="salary" placeholder="Enter minimum salary" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Job List -->
        <div class="col-lg-9">
            <div id="jobs-container" class="row g-3">
                <!-- Jobs will load here via AJAX -->
                <div class="col-12 text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div id="pagination-wrapper" class="mt-4 d-flex justify-content-center"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Make filtersForm globally accessible
let filtersForm;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    filtersForm = document.getElementById('filters-form');
    
    // Handle filters form
    filtersForm.addEventListener('submit', function (e) {
        e.preventDefault();
        loadJobs();
    });

    // Initial load
    loadJobs();
});

async function loadJobs(page = 1) {
    const container = document.getElementById('jobs-container');
    container.innerHTML = `<div class="col-12 text-center my-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>`;

    // Create form data from the filters form
    const formData = new FormData(filtersForm || document.createElement('form'));
    formData.append('page', page);

    try {
        const response = await axios.post('/api/jobs/search', formData);
        const { data, current_page, last_page } = response.data;

        if (data.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        No jobs found matching your criteria.
                    </div>
                </div>
            `;
            document.getElementById('pagination-wrapper').innerHTML = '';
        } else {
            container.innerHTML = data.map(jobCardTemplate).join('');
            
            // Update pagination
            const pagination = document.getElementById('pagination-wrapper');
            pagination.innerHTML = `
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        ${current_page > 1 ? `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="loadJobs(${current_page - 1})">Previous</a>
                            </li>
                        ` : ''}
                        ${Array.from({ length: last_page }, (_, i) => i + 1)
                            .map(pageNum => `
                                <li class="page-item ${pageNum === current_page ? 'active' : ''}">
                                    <a class="page-link" href="#" onclick="loadJobs(${pageNum})">${pageNum}</a>
                                </li>
                            `)
                            .join('')}
                        ${current_page < last_page ? `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="loadJobs(${current_page + 1})">Next</a>
                            </li>
                        ` : ''}
                    </ul>
                </nav>
            `;
        }
    } catch (error) {
        console.error('Error loading jobs:', error);
        container.innerHTML = `
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    Error loading jobs. Please try again later.
                </div>
            </div>
        `;
        document.getElementById('pagination-wrapper').innerHTML = '';
    }
}

function jobCardTemplate(job) {
    // Fallbacks for missing fields
    const title = job.title ?? 'Untitled Job';
    const company = job.company ?? 'Unknown Company';
    const location = job.location ?? 'N/A';
    const type = job.type ?? 'N/A';
    const category = job.category ?? '';
    const description = (job.description ?? '').substring(0, 100);
    // Build job detail URL
    const detailUrl = `/jobs/${job.id}`;

    return `
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">${title}</h5>
                    <p class="card-text mb-2">
                        <span class="text-primary">${company}</span>
                        <br>
                        <small class="text-muted">${location}</small>
                    </p>
                    <div class="mb-2">
                        <span class="badge bg-primary">${type}</span>
                        ${category ? `<span class="badge bg-secondary">${category}</span>` : ''}
                    </div>
                    <div class="card-text flex-grow-1">
                        <p>${description}...</p>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                ${new Date(job.created_at).toLocaleDateString()}
                            </small>
                            <a href="${detailUrl}" class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>
@endpush
