<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'event_date' => $this->event_date->toISOString(),
            'event_end_date' => $this->event_end_date?->toISOString(),
            'venue' => $this->venue,
            'online_link' => $this->online_link,
            'event_type' => $this->event_type,
            'max_attendees' => $this->max_attendees,
            'available_spaces' => $this->available_spaces,
            'featured_image_url' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'registration_deadline' => $this->registration_deadline?->toISOString(),
            'requires_approval' => $this->requires_approval,
            'price' => $this->price,
            'currency' => $this->currency,
            'is_registration_open' => $this->isRegistrationOpen(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'registrations' => EventRegistrationResource::collection($this->whenLoaded('registrations')),
            'registrations_count' => $this->whenLoaded('registrations', function() {
                return $this->registrations->count();
            }),
        ];
    }
}
