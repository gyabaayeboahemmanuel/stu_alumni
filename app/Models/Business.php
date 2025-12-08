<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use App\Traits\Searchable;

class Business extends Model
{
    use HasFactory, SoftDeletes, Auditable, Searchable;

    protected $fillable = [
        'alumni_id',
        'name',
        'slug',
        'description',
        'industry',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'logo_path',
        'is_verified',
        'is_featured',
        'verified_at',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_REJECTED = 'rejected';

    public function getSearchableColumns(): array
    {
        return ['name', 'description', 'industry', 'email', 'city', 'country'];
    }

    public function getAuditIdentifier(): string
    {
        return $this->name . " (" . $this->industry . ")";
    }
}

