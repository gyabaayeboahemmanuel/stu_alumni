<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class SISIntegration extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'student_id',
        'request_data',
        'response_data',
        'status',
        'verified_at',
        'error_message',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function alumni()
    {
        return $this->hasOne(Alumni::class, 'student_id', 'student_id');
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_NOT_FOUND = 'not_found';

    public function getAuditIdentifier(): string
    {
        return "SIS Integration for " . $this->student_id;
    }
}

