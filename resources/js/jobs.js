document.addEventListener('DOMContentLoaded', function() {
    // Initialize filters form
    const filtersForm = document.getElementById('filters-form');
    if (filtersForm) {
        filtersForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadJobs();
        });
    }

    // Load jobs when page loads
    loadJobs();
});

async function loadJobs(page = 1) {
    try {
        const container = document.getElementById('jobs-container');
        if (!container) return;

        // Show loading state
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        // Get form data
        const formData = new FormData(document.getElementById('filters-form'));
        formData.append('page', page);

        // Convert FormData to object for easier handling
        const params = Object.fromEntries(formData.entries());

        // Convert to URLSearchParams for the API call
        const urlParams = new URLSearchParams(params);
        const response = await axios.post('/api/jobs/search', params);

        if (response.data) {
            // Update jobs container
            container.innerHTML = renderJobs(response.data.data);

            // Update pagination
            updatePagination(response.data);
        }
    } catch (error) {
        console.error('Error loading jobs:', error);
        const container = document.getElementById('jobs-container');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-danger">
                    Error loading jobs. Please try again later.
                </div>
            `;
        }
    }
}

function renderJobs(jobs) {
    if (!jobs || jobs.length === 0) {
        return `
            <div class="col-12 text-center py-5">
                <h4>No jobs found</h4>
                <p>Try adjusting your filters or check back later.</p>
            </div>
        `;
    }

    return jobs.map(job => `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">${job.title}</h5>
                    <p class="card-text mb-2">
                        <i class="fas fa-building me-1"></i> ${job.company}
                    </p>
                    <p class="card-text mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i> ${job.location}
                    </p>
                    <p class="card-text mb-2">
                        <i class="fas fa-clock me-1"></i> ${job.type}
                    </p>
                    ${job.salary ? `
                        <p class="card-text mb-2">
                            <i class="fas fa-dollar-sign me-1"></i> ${job.salary}
                        </p>
                    ` : ''}
                    <p class="card-text text-muted">${job.description.substring(0, 100)}...</p>
                    <div class="mt-3">
                        <a href="/jobs/${job.id}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function updatePagination(data) {
    const paginationContainer = document.getElementById('pagination');
    if (!paginationContainer || !data) return;

    const current_page = data.current_page;
    const last_page = data.last_page;
    const total = data.total;

    if (last_page <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }

    paginationContainer.innerHTML = `
        <nav aria-label="Job pagination">
            <ul class="pagination justify-content-center">
                ${current_page > 1 ? `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadJobs(${current_page - 1})">Previous</a>
                    </li>
                ` : ''}
                ${Array.from({length: last_page}, (_, i) => i + 1).map(pageNum => `
                    <li class="page-item ${pageNum === current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadJobs(${pageNum})">${pageNum}</a>
                    </li>
                `).join('')}
                ${current_page < last_page ? `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadJobs(${current_page + 1})">Next</a>
                    </li>
                ` : ''}
            </ul>
        </nav>
        <div class="text-center mt-3">
            <small class="text-muted">
                Showing ${current_page} of ${last_page} pages (${total} jobs total)
            </small>
        </div>
    `;
}
