<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use App\Traits\Searchable;

class Alumni extends Model
{
    use HasFactory, SoftDeletes, Auditable, Searchable;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'student_id',
        'first_name',
        'last_name',
        'other_names',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'year_of_completion',
        'programme',
        'qualification',
        'current_employer',
        'job_title',
        'industry',
        'country',
        'city',
        'postal_address',
        'website',
        'linkedin',
        'twitter',
        'facebook',
        'profile_photo_path',
        'verification_status',
        'verification_source',
        'verified_at',
        'is_visible_in_directory',
        'registration_method',
        'proof_document_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'verified_at' => 'datetime',
        'is_visible_in_directory' => 'boolean',
    ];

    protected $attributes = [
        'verification_status' => 'unverified',
        'is_visible_in_directory' => true,
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the chapter this alumni belongs to
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function executivePositions()
    {
        return $this->hasMany(Executive::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeVisibleInDirectory($query)
    {
        return $query->where('is_visible_in_directory', true);
    }

    // Methods
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name . ' ' . ($this->other_names ?? ''));
    }

    public function markAsVerified($source = 'manual')
    {
        $this->update([
            'verification_status' => 'verified',
            'verification_source' => $source,
            'verified_at' => now(),
        ]);
    }

    public function getSearchableColumns(): array
    {
        return ['first_name', 'last_name', 'email', 'student_id', 'programme', 'current_employer', 'city', 'country'];
    }

    public function getAuditIdentifier(): string
    {
        return $this->full_name . " (" . $this->student_id . ")";
    }

    /**
     * Check if professional information is incomplete
     */
    public function hasIncompleteProfessionalInfo(): bool
    {
        return empty($this->current_employer) || 
               empty($this->job_title) || 
               empty($this->industry);
    }
}

