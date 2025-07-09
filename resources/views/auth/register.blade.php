@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h1 class="h4 mb-4 text-center">Register</h1>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="register-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="applicant">Applicant</option>
                                <option value="employer">Employer</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Create account</button>
                    </form>
                    <p class="small text-center mt-3 mb-0">Already have an account? <a href="/login">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const form = document.getElementById('register-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = new FormData(form);
    try {
        const res = await axios.post('/api/auth/register', data);
        alert('Registration successful! Please login');
        window.location.href = '/login';
    } catch (err) {
        alert(err.response?.data?.message || 'Registration failed');
    }
});
</script>
@endpush
