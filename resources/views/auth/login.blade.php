@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h1 class="h4 mb-4 text-center">Login</h1>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="login-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="small text-center mt-3 mb-0">Don't have an account? <a href="/register">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const form = document.getElementById('login-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = new FormData(form);
    try {
        const res = await axios.post('/api/auth/login', data);
        const token = res.data.token;
        localStorage.setItem('token', token);
        // set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        // redirect based on role
        const role = res.data.user.role;
        if(role === 'employer') window.location.href = '/dashboard/employer';
        else window.location.href = '/dashboard/applicant';
    } catch (err) {
        alert(err.response?.data?.message || 'Login failed');
    }
});
</script>
@endpush
