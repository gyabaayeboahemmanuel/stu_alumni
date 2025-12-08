<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'featured_image_url' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'is_published' => $this->is_published,
            'is_pinned' => $this->is_pinned,
            'published_at' => $this->published_at?->toISOString(),
            'visibility' => $this->visibility,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Relationships
            'author' => new UserResource($this->whenLoaded('author')),
            'category' => new AnnouncementCategoryResource($this->whenLoaded('category')),
        ];
    }
}
