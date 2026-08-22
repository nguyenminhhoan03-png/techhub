<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Web;

use Domain\Game\Repositories\GameRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GameController
{
    public function __construct(
        private readonly GameRepositoryInterface $games,
    ) {}

    /**
     * Game listing portal with sidebar, search, and category sections.
     */
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search       = trim((string) $request->query('q', ''));

        $categories    = $this->games->getCategories();
        $featuredGames = $this->games->getFeaturedGames(4);
        $games         = $this->games->getAllActive($categorySlug ?: null, $search ?: null);

        $activeCategory = $categorySlug
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $totalGamesCount = $categories->sum('games_count');

        return view('pages.games.index', compact(
            'games',
            'categories',
            'featuredGames',
            'activeCategory',
            'categorySlug',
            'search',
            'totalGamesCount'
        ));
    }

    /**
     * Individual game page (metadata + iframe embed).
     */
    public function show(string $slug): View
    {
        $game = $this->games->findBySlug($slug);

        abort_if($game === null, 404, 'Game không tồn tại.');

        $related = $this->games->getAllActive($game->category->slug)
            ->where('slug', '!=', $slug)
            ->take(4);

        $categories = $this->games->getCategories();

        return view('pages.games.show', compact('game', 'related', 'categories'));
    }

    /**
     * Increment play count and redirect back to game page.
     */
    public function play(string $slug): RedirectResponse
    {
        $game = $this->games->findBySlug($slug);

        abort_if($game === null, 404);

        $this->games->incrementPlayCount($game);

        return redirect()->route('games.show', $slug);
    }
}
