@extends('components.admin-layout')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Edit Job</h1>

    <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
            @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="5" class="form-control" required>{{ old('description', $job->description) }}</textarea>
            @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Company</label>
                <input type="text" name="company" class="form-control" value="{{ old('company', $job->company) }}" required>
                @error('company')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Salary</label>
                <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary', $job->salary) }}" required>
                @error('salary')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $job->location) }}" required>
            @error('location')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $job->category) }}" required>
                @error('category')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status', $job->status)=='active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $job->status)=='inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Job</button>
        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
