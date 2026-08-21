<?php

declare(strict_types=1);

namespace Application\Setting\Services;

use Domain\Setting\Entities\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'techhub_system_settings';

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        /** @var array<string, mixed> $settings */
        $settings = Cache::remember(self::CACHE_KEY, 3600, fn(): array => Setting::query()->pluck('value', 'key')->toArray());

        return $settings[$key] ?? $default;
    }

    /**
     * Update or create a setting and clear cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $label = '', string $type = 'text'): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'group' => $group,
                'label' => $label ?: ucfirst(str_replace('_', ' ', $key)),
                'type' => $type,
            ],
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
