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
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
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
    
    // Disable the submit button to prevent multiple submissions
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Creating account...';
    
    try {
        // Get form data as an object
        const formData = {
            name: form.querySelector('[name="name"]').value,
            email: form.querySelector('[name="email"]').value,
            password: form.querySelector('[name="password"]').value,
            password_confirmation: form.querySelector('[name="password_confirmation"]').value,
            role: form.querySelector('[name="role"]').value
        };
        
        console.log('Sending registration request with data:', formData);
        
        const response = await axios({
            method: 'post',
            url: '/api/auth/register',
            data: formData,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        console.log('Registration response:', response);
        
        // Check if the response indicates success (status code 2xx)
        if (response.status >= 200 && response.status < 300) {
            // Set CSRF token for future requests if available
            if (response.data.csrf_token) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.csrf_token;
            }
            
            // Show success message
            alert('Registration successful! Redirecting...');
            
            // Redirect to applications page
            window.location.href = '/applications';
            return;
        }
        
        // If we get here, the response status is not in the 2xx range
        throw new Error('Unexpected response status: ' + response.status);
        
    } catch (error) {
        console.error('Registration error:', error);
        
        // Log detailed error information
        if (error.response) {
            // The request was made and the server responded with a status code
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
            console.error('Response headers:', error.response.headers);
            
            let errorMessage = 'Registration failed. ';
            
            // Handle validation errors
            if (error.response.data?.errors) {
                const errors = error.response.data.errors;
                errorMessage += 'Please fix the following errors:\n\n';
                Object.entries(errors).forEach(([field, messages]) => {
                    const fieldName = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    errorMessage += `• ${fieldName}: ${Array.isArray(messages) ? messages[0] : messages}\n`;
                });
            } 
            // Handle other error messages
            else if (error.response.data?.message) {
                errorMessage += error.response.data.message;
            } else if (error.response.status === 422) {
                errorMessage += 'Validation error occurred. Please check your input.';
            } else {
                errorMessage += 'An unexpected error occurred. Please try again.';
            }
            
            alert(errorMessage);
        } 
        // Handle network errors
        else if (error.request) {
            console.error('No response received:', error.request);
            alert('Unable to connect to the server. Please check your internet connection and try again.');
        } 
        // Handle other errors
        else {
            console.error('Error:', error.message);
            alert('An error occurred. Please try again.');
        }
    } finally {
        // Re-enable the submit button
        submitButton.disabled = false;
        submitButton.textContent = 'Create account';
    }
});
</script>
@endpush
