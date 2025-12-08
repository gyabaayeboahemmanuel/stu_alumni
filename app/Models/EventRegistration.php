<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class EventRegistration extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'event_id',
        'alumni_id',
        'registration_date',
        'status',
        'notes',
        'attended',
        'attended_at',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'attended' => 'boolean',
        'attended_at' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WAITLISTED = 'waitlisted';

    public function getAuditIdentifier(): string
    {
        return "Registration #" . $this->id;
    }
}

