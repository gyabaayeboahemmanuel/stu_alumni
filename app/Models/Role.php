<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Role extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Predefined roles
    const SUPER_ADMIN = 'Super Admin';
    const ALUMNI_ADMIN = 'Alumni Admin';
    const CONTENT_EDITOR = 'Content Editor';
    const VERIFICATION_OFFICER = 'Verification Officer';
    const ALUMNI = 'Alumni';

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function getAuditIdentifier(): string
    {
        return $this->name;
    }
}