@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
            @error('password_confirmation')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="applicant" {{ old('role', $user->role)=='applicant' ? 'selected' : '' }}>Applicant</option>
                <option value="employer" {{ old('role', $user->role)=='employer' ? 'selected' : '' }}>Employer</option>
                <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
