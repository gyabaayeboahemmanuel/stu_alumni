<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventRegistrationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'registration_date' => $this->registration_date->toISOString(),
            'status' => $this->status,
            'notes' => $this->notes,
            'attended' => $this->attended,
            'attended_at' => $this->attended_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            
            // Relationships
            'event' => new EventResource($this->whenLoaded('event')),
            'alumni' => new AlumniResource($this->whenLoaded('alumni')),
        ];
    }
}
