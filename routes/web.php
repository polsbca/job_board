<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Public jobs listing
Route::get('/jobs', [\App\Http\Controllers\JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/create', [\App\Http\Controllers\JobController::class, 'create'])->name('jobs.create');
Route::post('/jobs', [\App\Http\Controllers\JobController::class, 'store'])->name('jobs.store');
Route::get('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{job}/edit', [\App\Http\Controllers\JobController::class, 'edit'])->name('jobs.edit');
Route::put('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'update'])->name('jobs.update');
Route::delete('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'destroy'])->name('jobs.destroy');

// Auth pages
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::view('/register', 'auth.register')->name('register');

// Dashboard and Profile routes
Route::middleware(['auth'])->group(function () {
    // Dashboard route - redirects based on user role
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('applicant')) {
            return redirect()->route('applicant.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Applications routes for regular users
    Route::post('/applications', [\App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications', [\App\Http\Controllers\ApplicationController::class, 'index'])->name('applications.index');
    
    // Applicant dashboard
    Route::get('/dashboard/applicant', [\App\Http\Controllers\Applicant\DashboardController::class, 'index'])
        ->name('applicant.dashboard');
});

// Admin routes
Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Jobs routes
    Route::get('/jobs', [\App\Http\Controllers\Admin\JobController::class, 'index'])->name('admin.jobs.index');
    Route::get('/jobs/create', [\App\Http\Controllers\Admin\JobController::class, 'create'])->name('admin.jobs.create');
    Route::post('/jobs', [\App\Http\Controllers\Admin\JobController::class, 'store'])->name('admin.jobs.store');
    Route::get('/jobs/{job}', [\App\Http\Controllers\Admin\JobController::class, 'show'])->name('admin.jobs.show');
    Route::get('/jobs/{job}/edit', [\App\Http\Controllers\Admin\JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/jobs/{job}', [\App\Http\Controllers\Admin\JobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/jobs/{job}', [\App\Http\Controllers\Admin\JobController::class, 'destroy'])->name('admin.jobs.destroy');
    
    // Users routes
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Applications routes
    Route::get('/applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/applications/{application}', [\App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('admin.applications.show');
    Route::put('/applications/{application}/status', [\App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('admin.applications.updateStatus');
});
