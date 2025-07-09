<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'job_id' => $this->job_id,
            'cover_letter' => $this->cover_letter,
            'resume_path' => $this->resume_path,
            'resume_url' => $this->resume_url,
            'status' => $this->status,
            'feedback' => $this->feedback,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new \App\Http\Resources\UserResource($this->whenLoaded('user')),
            'job' => new \App\Http\Resources\JobResource($this->whenLoaded('job')),
        ];
    }
}
