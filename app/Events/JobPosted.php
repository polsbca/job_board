<?php

namespace App\Events;

use App\Models\Job;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function broadcastOn()
    {
        return new Channel('jobs');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->job->id,
            'title' => $this->job->title,
            'company' => $this->job->company,
            'location' => $this->job->location,
            'salary' => $this->job->salary,
            'type' => $this->job->type,
            'created_at_human' => $this->job->created_at->diffForHumans(),
        ];
    }
}
