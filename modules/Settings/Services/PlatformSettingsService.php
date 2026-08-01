<?php

namespace Modules\Settings\Services;

use App\Models\PlatformSetting;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Cache;

class PlatformSettingsService
{
    public const CACHE_KEY = 'platform_settings_bag';

    /** @var array<string, mixed> */
    public const DEFAULTS = [
        'general.site_name' => 'يَطْمَئِن',
        'general.support_email' => '',
        'platform.maintenance_mode' => false,
        'platform.registration_open' => true,
        'authentication.email_verification_required' => true,
        'email.from_name' => '',
        'email.from_address' => '',
        'telegram.bot_token' => '',
        'telegram.enabled' => false,
        'payments.manual_enabled' => true,
        'media.max_upload_mb' => 200,
        'storage.disk' => 'local_private',
        'cache.driver' => 'redis',
        'queue.driver' => 'redis',
        'seo.meta_description' => '',
        'theme.primary' => '#2A9D8F',
        'languages.ui' => 'ar',
        'security.force_https' => true,
        'backup.enabled' => true,
        'maintenance.message' => 'المنصة قيد الصيانة مؤقتاً.',
    ];

    public function __construct(private readonly AuditLogger $audit)
    {
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $stored = PlatformSetting::query()->pluck('value', 'key')->all();
            $merged = self::DEFAULTS;
            foreach ($stored as $key => $value) {
                $merged[$key] = $this->castValue($key, $value);
            }

            return $merged;
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return $all[$key] ?? $default ?? (self::DEFAULTS[$key] ?? null);
    }

    /** @param  array<string, mixed>  $values */
    public function setMany(array $values, ?User $actor = null): void
    {
        foreach ($values as $key => $value) {
            PlatformSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
            );
        }

        Cache::forget(self::CACHE_KEY);
        $this->audit->log($actor, 'settings.updated', null, null, ['keys' => array_keys($values)]);
    }

    private function castValue(string $key, mixed $value): mixed
    {
        $default = self::DEFAULTS[$key] ?? null;
        if (is_bool($default)) {
            return in_array($value, [true, 1, '1', 'true', 'on'], true);
        }
        if (is_int($default)) {
            return (int) $value;
        }

        return $value;
    }
}
