<?php

declare(strict_types=1);

namespace Application\Ad\Services;

use Domain\Ad\Entities\Advertisement;
use Illuminate\Support\Facades\Cache;

class AdService
{
    /**
     * Get active advertisement for a given slot.
     */
    public static function getAdForSlot(string $slot): ?Advertisement
    {
        $cacheKey = "techhub_active_ad_{$slot}";

        return Cache::remember($cacheKey, 300, function () use ($slot): ?Advertisement {
            $now = now();

            return Advertisement::query()
                ->where('slot', $slot)
                ->where('is_active', true)
                ->where(function ($q) use ($now): void {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now): void {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                })
                ->inRandomOrder()
                ->first();
        });
    }
}
