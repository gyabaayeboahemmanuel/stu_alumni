<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'student_id',
        'password',
        'role_id',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function alumni()
    {
        return $this->hasOne(Alumni::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function isAdmin()
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->hasRole(Role::SUPER_ADMIN) || 
               $this->hasRole(Role::ALUMNI_ADMIN) ||
               $this->hasRole(Role::CONTENT_EDITOR) ||
               $this->hasRole(Role::VERIFICATION_OFFICER);
    }

    /**
     * Safe access to alumni profile photo - returns null for admin users
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->alumni) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=1E40AF';
        }

        return $this->alumni->profile_photo_path
            ? asset('storage/' . $this->alumni->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=1E40AF';
    }

    /**
     * Find user for authentication by email, phone, or student_id
     */
    public static function findForAuth($identifier)
    {
        // Try to find by email first
        $user = static::where('email', $identifier)->first();
        if ($user) {
            return $user;
        }

        // Try to find by phone
        $user = static::where('phone', $identifier)->first();
        if ($user) {
            return $user;
        }

        // Try to find by student_id
        $user = static::where('student_id', $identifier)->first();
        if ($user) {
            return $user;
        }

        return null;
    }
}
