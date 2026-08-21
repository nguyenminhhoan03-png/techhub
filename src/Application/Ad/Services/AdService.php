<?php

declare(strict_types=1);

namespace Application\Ad\Services;

use Domain\Ad\Entities\Advertisement;

class AdService
{
    /**
     * Get active advertisement for a given slot.
     */
    public static function getAdForSlot(string $slot): ?Advertisement
    {
        $now = now();

        /** @var Advertisement|null $ad */
        $ad = Advertisement::query()
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

        if ($ad) {
            // Increment impression count asynchronously / silently
            $ad->increment('impressions_count');
        }

        return $ad;
    }
}
