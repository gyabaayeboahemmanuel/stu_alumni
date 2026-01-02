<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class YearGroup extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'start_year',
        'end_year',
        'description',
        'whatsapp_link',
        'telegram_link',
        'gekychat_link',
        'is_active',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get alumni in this year group
     */
    public function alumni()
    {
        return Alumni::whereBetween('year_of_completion', [$this->start_year, $this->end_year]);
    }

    /**
     * Check if a graduation year falls within this group
     */
    public function includesYear($year)
    {
        return $year >= $this->start_year && $year <= $this->end_year;
    }

    /**
     * Get active year groups
     */
    public static function active()
    {
        return self::where('is_active', true)->orderBy('start_year', 'desc');
    }

    /**
     * Get year groups for a specific graduation year
     */
    public static function forGraduationYear($year)
    {
        return self::active()
            ->where('start_year', '<=', $year)
            ->where('end_year', '>=', $year)
            ->get();
    }

    /**
     * Get display name with year range
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->start_year} - {$this->end_year})";
    }

    /**
     * Check if has any social links
     */
    public function hasSocialLinks()
    {
        return !empty($this->whatsapp_link) || 
               !empty($this->telegram_link) || 
               !empty($this->gekychat_link);
    }

    public function getAuditIdentifier(): string
    {
        return "Year Group: " . $this->name;
    }
}

