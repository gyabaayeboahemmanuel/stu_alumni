<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Notification extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'type',
        'recipient',
        'subject',
        'content',
        'sent_via',
        'status',
        'sent_at',
        'error_message',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Type constants
    const TYPE_REGISTRATION = 'registration';
    const TYPE_VERIFICATION = 'verification';
    const TYPE_EVENT = 'event';
    const TYPE_NEWSLETTER = 'newsletter';
    const TYPE_BROADCAST = 'broadcast';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    // Via constants
    const VIA_EMAIL = 'email';
    const VIA_SMS = 'sms';
    const VIA_WHATSAPP = 'whatsapp';
    const VIA_GEKYCHAT = 'gekychat';

    public function getAuditIdentifier(): string
    {
        return "Notification #" . $this->id;
    }
}

