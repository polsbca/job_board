<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\ApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Email verification notification
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!']);
    })->middleware(['auth:sanctum', 'throttle:6,1']);
});

// Public job search
Route::prefix('jobs')->group(function () {
    Route::post('/search', [JobController::class, 'search']); // filtering list
    Route::get('/similar', [JobController::class, 'similar']);
    Route::get('/', [JobController::class, 'index']);
    Route::get('/{job}', [JobController::class, 'show']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // Protected job routes
    Route::prefix('jobs')->group(function () {
        Route::post('/{job}/view', [JobController::class, 'trackView']);
        
        // Employer-only routes
        Route::middleware(['role:employer'])->group(function () {
            Route::post('/', [JobController::class, 'store']);
            Route::put('/{job}', [JobController::class, 'update']);
            Route::delete('/{job}', [JobController::class, 'destroy']);
            
            // Get applications for a specific job (employer's own jobs only)
            Route::get('/{job}/applications', [JobController::class, 'applications']);
        });

        // Applicant actions
        Route::middleware(['role:applicant'])->group(function () {
            Route::post('/{job}/save', [JobController::class, 'toggleSave']);
        });
    });
    
    // Saved jobs for applicants
    Route::middleware(['role:applicant'])->get('/saved-jobs', [JobController::class, 'saved']);

    // Employer dashboard routes
    Route::middleware(['role:employer'])->group(function () {
        Route::get('/employer/jobs', [JobController::class, 'myJobs']);
        Route::get('/employer/applications', [ApplicationController::class, 'index']);
    });

    // Applications routes
    Route::prefix('applications')->group(function () {
        // Protected routes (auth required)
        Route::middleware(['auth:sanctum'])->group(function () {
            // List all applications (admin only) or user's own applications (applicant)
            Route::get('/', [ApplicationController::class, 'index']);
            
            // Create new application (applicant only)
            Route::middleware(['role:applicant'])->post('/', [ApplicationController::class, 'store']);
            
            // Application details (owner, employer, or admin)
            Route::get('/{application}', [ApplicationController::class, 'show']);
            
            // Update application status (employer for their jobs or admin)
            Route::middleware(['role:employer,admin'])->put('/{application}', [ApplicationController::class, 'update']);
            
            // Withdraw application (applicant) or delete (admin)
            Route::delete('/{application}', [ApplicationController::class, 'destroy']);
            
            // Get applications for current user (applicant)
            Route::get('/my/applications', [ApplicationController::class, 'myApplications'])->middleware('role:applicant');
        });
    });
    
    // User profile routes
    Route::prefix('profile')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            // Update profile
            Route::put('/', [AuthController::class, 'updateProfile']);
            
            // Update password
            Route::put('/password', [AuthController::class, 'updatePassword']);
            
            // Upload profile photo
            Route::post('/photo', [AuthController::class, 'uploadPhoto']);
        });
    });
});
