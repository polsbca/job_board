@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Employer Dashboard</h1>

    <div class="row">
        <!-- Sidebar Navigation -->
        <aside class="col-md-3 mb-4">
            <div class="list-group">
                <a href="#my-jobs" class="list-group-item list-group-item-action active" data-bs-toggle="tab">My Jobs</a>
                <a href="#applications" class="list-group-item list-group-item-action" data-bs-toggle="tab">Applications</a>
                <a href="#profile" class="list-group-item list-group-item-action" data-bs-toggle="tab">Profile</a>
            </div>
            <a href="{{ route('jobs.create') }}" class="btn btn-primary w-100 mt-3">
                <i class="fas fa-plus me-1"></i> Post New Job
            </a>
        </aside>

        <!-- Tab Content -->
        <div class="col-md-9">
            <div class="tab-content" id="dashboard-tabs">
                <!-- My Jobs Tab -->
                <div class="tab-pane fade show active" id="my-jobs">
                    <h2 class="h5 mb-3">My Job Listings</h2>
                    <div id="jobs-container" class="row g-3"></div>
                </div>

                <!-- Applications Tab -->
                <div class="tab-pane fade" id="applications">
                    <h2 class="h5 mb-3">Applications Received</h2>
                    <div id="applications-container" class="row g-3"></div>
                </div>

                <!-- Profile Tab -->
                <div class="tab-pane fade" id="profile">
                    <h2 class="h5 mb-3">Update Company Profile</h2>
                    <form id="profile-form">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company" value="{{ auth()->user()->company }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" value="{{ auth()->user()->website }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="4">{{ auth()->user()->bio }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load employer jobs and applications
loadMyJobs();
loadApplications();

async function loadMyJobs() {
    const container = document.getElementById('jobs-container');
    container.innerHTML = loader();
    try {
        const { data } = await axios.get('/api/employer/jobs');
        if (data.length === 0) {
            container.innerHTML = empty('You have not posted any jobs yet.');
        } else {
            container.innerHTML = data.map(job => jobCard(job)).join('');
        }
    } catch (error) {
        console.error(error);
        container.innerHTML = errorAlert();
    }
}

async function loadApplications() {
    const container = document.getElementById('applications-container');
    container.innerHTML = loader();
    try {
        const { data } = await axios.get('/api/employer/applications');
        if (data.length === 0) {
            container.innerHTML = empty('No applications received yet.');
        } else {
            container.innerHTML = data.map(app => applicationCard(app)).join('');
        }
    } catch (error) {
        console.error(error);
        container.innerHTML = errorAlert();
    }
}

function jobCard(job) {
    return `
        <div class="col-12">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><a href="/jobs/${job.id}" class="text-decoration-none">${job.title}</a></h5>
                    <p class="card-text small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i>${job.location}</p>
                    <p class="card-text text-success fw-bold mb-4 mt-auto">$${Number(job.salary).toLocaleString()}/yr</p>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="/jobs/${job.id}/edit" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit me-1"></i> Edit</a>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteJob(${job.id})"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>`;
}

function applicationCard(app) {
    return `
        <div class="col-12">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><a href="/applications/${app.id}" class="text-decoration-none">${app.applicant.name}</a></h5>
                    <h6 class="card-subtitle mb-2 text-muted">${app.job.title}</h6>
                    <p class="card-text small text-muted">Applied ${app.created_at_human}</p>
                    <a href="/applications/${app.id}" class="btn btn-outline-primary mt-auto">View Application</a>
                </div>
            </div>
        </div>`;
}

function deleteJob(id) {
    Swal.fire({
        title: 'Delete Job?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/jobs/${id}`);
                loadMyJobs();
                Swal.fire('Deleted!', 'Job has been deleted.', 'success');
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Failed to delete job.', 'error');
            }
        }
    });
}

// Helpers (same as applicant dashboard)
function loader() { return '<div class="col-12 text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'; }
function empty(msg) { return `<div class="col-12"><p class="text-center text-muted">${msg}</p></div>`; }
function errorAlert() { return '<div class="col-12"><div class="alert alert-danger">Failed to load data.</div></div>'; }

// Profile form submission
const profileForm = document.getElementById('profile-form');
profileForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    try {
        const formData = new FormData(profileForm);
        await axios.post('/api/profile/update', formData);
        Swal.fire({ icon: 'success', title: 'Profile updated!' });
    } catch (error) {
        console.error(error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update profile.' });
    }
});
</script>
@endpush
