<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlumniResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_names' => $this->other_names,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'year_of_completion' => $this->year_of_completion,
            'programme' => $this->programme,
            'qualification' => $this->qualification,
            'current_employer' => $this->current_employer,
            'job_title' => $this->job_title,
            'industry' => $this->industry,
            'country' => $this->country,
            'city' => $this->city,
            'postal_address' => $this->postal_address,
            'website' => $this->website,
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'profile_photo_url' => $this->profile_photo_path ? 
                asset('storage/' . $this->profile_photo_path) : 
                'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=FFFFFF&background=1E40AF',
            'verification_status' => $this->verification_status,
            'verification_source' => $this->verification_source,
            'verified_at' => $this->verified_at?->toISOString(),
            'is_visible_in_directory' => $this->is_visible_in_directory,
            'registration_method' => $this->registration_method,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'businesses' => BusinessResource::collection($this->whenLoaded('businesses')),
            'event_registrations' => EventRegistrationResource::collection($this->whenLoaded('eventRegistrations')),
            'executive_positions' => ExecutiveResource::collection($this->whenLoaded('executivePositions')),
        ];
    }
}
