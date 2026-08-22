<?php

declare(strict_types=1);

namespace Domain\Game\Repositories;

use Domain\Game\Entities\Game;
use Domain\Game\Entities\GameCategory;
use Illuminate\Database\Eloquent\Collection;

interface GameRepositoryInterface
{
    /** @return Collection<GameCategory> */
    public function getCategories(): Collection;

    /** @return Collection<Game> */
    public function getFeaturedGames(int $limit = 4): Collection;

    /** @return Collection<Game> */
    public function getAllActive(?string $categorySlug = null, ?string $search = null): Collection;

    public function findBySlug(string $slug): ?Game;

    public function incrementPlayCount(Game $game): void;
}
