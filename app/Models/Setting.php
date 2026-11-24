<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Simple key/value store for editable site content (homepage text,
 * contact details, social links). Cached forever and flushed on save.
 */
class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'site-settings';

    /** Editable settings, grouped for the admin form: key => [label, type]. */
    public const FIELDS = [
        'Homepage' => [
            'hero_tagline' => ['Hero tagline', 'text'],
            'hero_title' => ['Hero headline', 'text'],
            'hero_text' => ['Hero introduction', 'textarea'],
            'intro_title' => ['Introduction heading', 'text'],
            'intro_text' => ['Introduction text', 'textarea'],
        ],
        'Membership' => [
            'membership_intro' => ['Membership introduction', 'textarea'],
            'isoc_global_url' => ['Internet Society global membership link', 'url'],
        ],
        'Contact details' => [
            'contact_email' => ['Email address', 'email'],
            'contact_phone' => ['Phone number', 'text'],
            'contact_address' => ['Address', 'text'],
            'office_hours' => ['Office hours', 'text'],
        ],
        'Social links' => [
            'social_facebook' => ['Facebook URL', 'url'],
            'social_x' => ['X (Twitter) URL', 'url'],
            'social_linkedin' => ['LinkedIn URL', 'url'],
            'social_instagram' => ['Instagram URL', 'url'],
            'social_youtube' => ['YouTube URL', 'url'],
        ],
    ];

    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::allCached()[$key] ?? $default;
    }

    public static function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
