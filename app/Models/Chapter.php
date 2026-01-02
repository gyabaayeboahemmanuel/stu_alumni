<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Chapter extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'region',
        'city',
        'country',
        'description',
        'president_id',
        'contact_email',
        'contact_phone',
        'meeting_location',
        'whatsapp_link',
        'is_active',
        'is_approved',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the president of this chapter
     */
    public function president()
    {
        return $this->belongsTo(Alumni::class, 'president_id');
    }

    /**
     * Get all members of this chapter
     */
    public function members()
    {
        return $this->hasMany(Alumni::class, 'chapter_id');
    }

    /**
     * Get active chapters
     */
    public static function active()
    {
        return self::where('is_active', true)->where('is_approved', true);
    }

    /**
     * Get pending approval chapters
     */
    public static function pending()
    {
        return self::where('is_approved', false);
    }

    /**
     * Get member count
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Get full location
     */
    public function getFullLocationAttribute()
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Check if alumni is president
     */
    public function isPresident($alumniId)
    {
        return $this->president_id == $alumniId;
    }

    public function getAuditIdentifier(): string
    {
        return "Chapter: " . $this->name;
    }
}

