<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'company' => $this->company,
            'location' => $this->location,
            'category' => $this->category,
            'salary' => $this->salary,
            'type' => $this->type,
            'status' => $this->status,
            'closing_date' => $this->closing_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'applications_count' => $this->whenCounted('applications'),
            'user' => new \App\Http\Resources\UserResource($this->whenLoaded('user')),
        ];
    }
}
