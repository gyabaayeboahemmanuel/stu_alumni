<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class SiteSetting extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    // Setting groups
    const GROUP_SOCIAL_MEDIA = 'social_media';
    const GROUP_CONTACT = 'contact';
    const GROUP_GENERAL = 'general';

    // Setting types
    const TYPE_TEXT = 'text';
    const TYPE_URL = 'url';
    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_IMAGE = 'image';

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = self::TYPE_TEXT, $group = self::GROUP_GENERAL, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key');
    }

    public function getAuditIdentifier(): string
    {
        return "Site Setting: " . $this->key;
    }
}

