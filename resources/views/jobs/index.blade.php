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
                            <label class="form-label">Salary Range</label>
                            <div class="d-flex gap-2">
                                <input type="number" class="form-control" name="salary_min" placeholder="Min" min="0">
                                <input type="number" class="form-control" name="salary_max" placeholder="Max" min="0">
                            </div>
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
// Initial load
loadJobs();

// Handle filters form
const filtersForm = document.getElementById('filters-form');
filtersForm.addEventListener('submit', function (e) {
    e.preventDefault();
    loadJobs();
});

async function loadJobs(page = 1) {
    const container = document.getElementById('jobs-container');
    container.innerHTML = `<div class="col-12 text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`;

    const formData = new FormData(filtersForm);
    formData.append('page', page);

    try {
        const response = await axios.post('/api/jobs/search', formData);
        const { data, current_page, last_page } = response.data;

        if (data.length === 0) {
            container.innerHTML = '<div class="col-12"><p class="text-center text-muted">No jobs found.</p></div>';
        } else {
            container.innerHTML = data.map(job => jobCardTemplate(job)).join('');
        }

        renderPagination(current_page, last_page);
    } catch (error) {
        console.error('Error loading jobs:', error);
        container.innerHTML = '<div class="col-12"><div class="alert alert-danger">Failed to load jobs.</div></div>';
    }
}

function jobCardTemplate(job) {
    return `
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="/jobs/${job.id}" class="text-decoration-none">${job.title}</a>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">${job.company}</h6>
                    <p class="card-text small text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i>${job.location}
                    </p>
                    <p class="card-text text-success fw-bold mb-4 mt-auto">$${Number(job.salary).toLocaleString()}/yr</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">${job.type}</span>
                        <small class="text-muted">${job.created_at_human}</small>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderPagination(current, last) {
    const wrapper = document.getElementById('pagination-wrapper');
    if (last <= 1) {
        wrapper.innerHTML = '';
        return;
    }
    let html = `<nav><ul class="pagination">`;
    for (let i = 1; i <= last; i++) {
        html += `<li class="page-item ${i === current ? 'active' : ''}"><a class="page-link" href="#" onclick="loadJobs(${i});return false;">${i}</a></li>`;
    }
    html += `</ul></nav>`;
    wrapper.innerHTML = html;
}
</script>
@endpush
