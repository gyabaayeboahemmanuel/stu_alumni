<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'industry' => $this->industry,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'logo_url' => $this->logo_path ? asset('storage/' . $this->logo_path) : null,
            'is_verified' => $this->is_verified,
            'is_featured' => $this->is_featured,
            'verified_at' => $this->verified_at?->toISOString(),
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'alumni' => new AlumniResource($this->whenLoaded('alumni')),
        ];
    }
}
