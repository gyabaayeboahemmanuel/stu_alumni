<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExecutiveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'term_year' => $this->term_year,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'is_current' => $this->is_current,
            'display_order' => $this->display_order,
            'bio' => $this->bio,
            'achievements' => $this->achievements,
            'created_at' => $this->created_at->toISOString(),
            
            // Relationships
            'alumni' => new AlumniResource($this->whenLoaded('alumni')),
        ];
    }
}
