@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Applicant Dashboard</h1>

    <div class="row">
        <!-- Sidebar Navigation -->
        <aside class="col-md-3 mb-4">
            <div class="list-group">
                <a href="#applications" class="list-group-item list-group-item-action active" data-bs-toggle="tab">My Applications</a>
                <a href="#saved-jobs" class="list-group-item list-group-item-action" data-bs-toggle="tab">Saved Jobs</a>
                <a href="#profile" class="list-group-item list-group-item-action" data-bs-toggle="tab">Profile</a>
            </div>
        </aside>

        <!-- Tab Content -->
        <div class="col-md-9">
            <div class="tab-content" id="dashboard-tabs">
                <!-- Applications Tab -->
                <div class="tab-pane fade show active" id="applications">
                    <h2 class="h5 mb-3">My Applications</h2>
                    <div id="applications-container" class="row g-3"></div>
                </div>

                <!-- Saved Jobs Tab -->
                <div class="tab-pane fade" id="saved-jobs">
                    <h2 class="h5 mb-3">Saved Jobs</h2>
                    <div id="saved-jobs-container" class="row g-3"></div>
                </div>

                <!-- Profile Tab -->
                <div class="tab-pane fade" id="profile">
                    <h2 class="h5 mb-3">Update Profile</h2>
                    <form id="profile-form">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
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
// Load applications and saved jobs on page load
loadApplications();
loadSavedJobs();

async function loadApplications() {
    const container = document.getElementById('applications-container');
    container.innerHTML = loader();
    try {
        const { data } = await axios.get('/api/applications');
        if (data.length === 0) {
            container.innerHTML = empty('You have not applied to any jobs yet.');
        } else {
            container.innerHTML = data.map(app => applicationCard(app)).join('');
        }
    } catch (error) {
        console.error(error);
        container.innerHTML = errorAlert();
    }
}

async function loadSavedJobs() {
    const container = document.getElementById('saved-jobs-container');
    container.innerHTML = loader();
    try {
        const { data } = await axios.get('/api/saved-jobs');
        if (data.length === 0) {
            container.innerHTML = empty('No saved jobs.');
        } else {
            container.innerHTML = data.map(job => jobCard(job)).join('');
        }
    } catch (error) {
        console.error(error);
        container.innerHTML = errorAlert();
    }
}

function applicationCard(app) {
    return `
        <div class="col-12">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><a href="/jobs/${app.job.id}" class="text-decoration-none">${app.job.title}</a></h5>
                    <h6 class="card-subtitle mb-2 text-muted">${app.job.company}</h6>
                    <p class="card-text small text-muted">Applied ${app.created_at_human}</p>
                    <a href="/applications/${app.id}" class="btn btn-outline-primary mt-auto">View Application</a>
                </div>
            </div>
        </div>`;
}

function jobCard(job) {
    return `
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><a href="/jobs/${job.id}" class="text-decoration-none">${job.title}</a></h5>
                    <h6 class="card-subtitle mb-2 text-muted">${job.company}</h6>
                    <p class="card-text small text-muted mb-4 mt-auto"><i class="fas fa-map-marker-alt me-1"></i>${job.location}</p>
                    <a href="/jobs/${job.id}" class="btn btn-outline-primary mt-auto">View Job</a>
                </div>
            </div>
        </div>`;
}

// Helpers
function loader() {
    return '<div class="col-12 text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
}
function empty(msg) {
    return `<div class="col-12"><p class="text-center text-muted">${msg}</p></div>`;
}
function errorAlert() {
    return '<div class="col-12"><div class="alert alert-danger">Failed to load data.</div></div>';
}

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
