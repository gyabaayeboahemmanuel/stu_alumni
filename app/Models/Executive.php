<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Executive extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'alumni_id',
        'position',
        'term_year',
        'start_date',
        'end_date',
        'is_current',
        'display_order',
        'bio',
        'achievements',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    // Relationships
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByTerm($query, $termYear)
    {
        return $query->where('term_year', $termYear);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('position');
    }

    // Position constants
    const POSITION_PRESIDENT = 'President';
    const POSITION_VICE_PRESIDENT = 'Vice President';
    const POSITION_SECRETARY = 'Secretary';
    const POSITION_TREASURER = 'Treasurer';

    public function getAuditIdentifier(): string
    {
        return $this->position . " - " . $this->term_year;
    }
}

