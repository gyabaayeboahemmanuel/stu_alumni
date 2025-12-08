<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use App\Traits\Searchable;

class Event extends Model
{
    
    use HasFactory, SoftDeletes, Auditable, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_end_date',
        'venue',
        'online_link',
        'event_type',
        'max_attendees',
        'featured_image',
        'is_published',
        'is_featured',
        'registration_deadline',
        'requires_approval',
        'price',
        'currency',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'requires_approval' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }

    // Methods
    public function getAvailableSpacesAttribute()
    {
        if (!$this->max_attendees) {
            return null; // Unlimited
        }
        return $this->max_attendees - $this->registrations()->count();
    }

    public function isRegistrationOpen()
    {
        return $this->registration_deadline > now();
    }

    public function getSearchableColumns(): array
    {
        return ['title', 'description', 'venue'];
    }

    public function getAuditIdentifier(): string
    {
        return $this->title . " (" . $this->event_date->format("Y-m-d") . ")";
    }
}

