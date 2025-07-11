<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_listings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'company',
        'location',
        'category',
        'salary',
        'type',
        'status',
        'closing_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'salary' => 'decimal:2',
        'closing_date' => 'datetime',
    ];

    /**
     * Get the user that owns the job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applications for the job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    /**
     * Get the users who have saved this job.
     */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')
            ->withPivot('notes')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active jobs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereNull('closing_date')
                            ->orWhere('closing_date', '>=', now());
                    });
    }

    /**
     * Scope a query to only include jobs of a specific type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include jobs in a specific category.
     */
    public function scopeInCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include jobs in a specific location.
     */
    public function scopeInLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope a query to only include jobs with salary greater than or equal to the given amount.
     */
    public function scopeMinSalary(Builder $query, float $amount): Builder
    {
        return $query->where('salary', '>=', $amount);
    }
}
