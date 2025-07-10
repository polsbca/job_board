<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function broadcastOn()
    {
        // Broadcast to employer's private channel
        return new Channel('employer.' . $this->application->job->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'applicant_name' => $this->application->user->name,
            'applicant_email' => $this->application->user->email,
            'status' => $this->application->status,
            'created_at_human' => $this->application->created_at->diffForHumans(),
        ];
    }
}
