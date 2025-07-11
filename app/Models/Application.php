<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'job_id',
        'cover_letter',
        'resume_path',
        'status',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job that the application is for.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Scope a query to only include applications with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include applications for a specific job.
     */
    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    /**
     * Get the URL to the resume file.
     */
    public function getResumeUrlAttribute()
    {
        return $this->resume_path ? asset('storage/' . $this->resume_path) : null;
    }

    /**
     * Determine the Bootstrap badge color class for the application's status.
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'pending'   => 'secondary', // gray
            'reviewing' => 'info',      // blue
            'accepted'  => 'success',   // green
            'rejected'  => 'danger',    // red
            default     => 'secondary',
        };
    }
}
