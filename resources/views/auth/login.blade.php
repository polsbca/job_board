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
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="small text-center mt-3 mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
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
    
    try {
        const response = await axios.post('/api/auth/login', {
            email: form.email.value,
            password: form.password.value,
            remember_me: form.remember?.checked
        });

        // Store the token
        const token = response.data.authorization.token;
        localStorage.setItem('token', token);
        
        // Set default auth header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        // Redirect based on role
        const role = response.data.user.role;
        if (role === 'employer') {
            window.location.href = '/dashboard/employer';
        } else if (role === 'applicant') {
            window.location.href = '/dashboard/applicant';
        } else if (role === 'admin') {
            window.location.href = '/admin/dashboard';
        } else {
            window.location.href = '/dashboard';
        }
    } catch (error) {
        let errorMessage = 'Login failed. Please try again.';
        if (error.response) {
            // Server responded with a status other than 2xx
            errorMessage = error.response.data.message || errorMessage;
            if (error.response.data.errors) {
                errorMessage = Object.values(error.response.data.errors).flat().join(' ');
            }
        } else if (error.request) {
            // Request was made but no response received
            errorMessage = 'No response from server. Please check your connection.';
        }
        alert(errorMessage);
    }
});
</script>
@endpush
