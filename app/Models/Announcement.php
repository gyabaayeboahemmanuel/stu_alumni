<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use App\Traits\Searchable;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, Auditable, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'category_id',
        'author_id',
        'featured_image',
        'is_published',
        'is_pinned',
        'published_at',
        'visibility',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(AnnouncementCategory::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeAlumniOnly($query)
    {
        return $query->where('visibility', 'alumni');
    }

    public function getSearchableColumns(): array
    {
        return ['title', 'content', 'excerpt'];
    }

    public function getAuditIdentifier(): string
    {
        return $this->title;
    }
}

