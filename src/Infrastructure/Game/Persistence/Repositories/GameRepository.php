<?php

declare(strict_types=1);

namespace Infrastructure\Game\Persistence\Repositories;

use Domain\Game\Entities\Game;
use Domain\Game\Entities\GameCategory;
use Domain\Game\Repositories\GameRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class GameRepository implements GameRepositoryInterface
{
    public function getCategories(): Collection
    {
        return GameCategory::query()
            ->where('is_active', true)
            ->whereHas('activeGames')
            ->orderBy('sort_order')
            ->with(['activeGames' => fn ($q) => $q->with('category')->orderByDesc('is_featured')->orderByDesc('play_count')])
            ->withCount(['activeGames as games_count'])
            ->get();
    }

    public function getFeaturedGames(int $limit = 4): Collection
    {
        return Game::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->orderByDesc('play_count')
            ->limit($limit)
            ->get();
    }

    public function getAllActive(?string $categorySlug = null, ?string $search = null): Collection
    {
        $query = Game::query()
            ->where('is_active', true)
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderByDesc('play_count');

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function findBySlug(string $slug): ?Game
    {
        return Game::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->first();
    }

    public function incrementPlayCount(Game $game): void
    {
        $game->incrementPlayCount();
    }
}
