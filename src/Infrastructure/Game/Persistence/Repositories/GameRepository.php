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

    public function getAllActive(?string $categorySlug = null, ?string $search = null, ?string $sort = null, ?string $difficulty = null): Collection
    {
        $query = Game::query()
            ->where('is_active', true)
            ->with('category');

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($difficulty && in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
            $query->where('difficulty', $difficulty);
        }

        if ($search) {
            $rawSearch = trim($search);
            $lowerSearch = mb_strtolower($rawSearch);

            // Semantic Vietnamese-to-English Game Dictionary
            $semanticAliases = [
                '2 người'     => ['2 player', 'two player', '2-player', '2 players', 'duel', 'multiplayer', 'versus', 'pvp', '2'],
                '2 nguoi'     => ['2 player', 'two player', '2-player', '2 players', 'duel', 'multiplayer', 'versus', 'pvp', '2'],
                'hai người'   => ['2 player', 'two player', '2-player', 'duel', 'multiplayer'],
                'hai nguoi'   => ['2 player', 'two player', '2-player', 'duel', 'multiplayer'],
                'đua xe'      => ['race', 'racing', 'car', 'drive', 'driving', 'moto', 'bike', 'drift', 'speed'],
                'dua xe'      => ['race', 'racing', 'car', 'drive', 'driving', 'moto', 'bike', 'drift', 'speed'],
                'lái xe'      => ['drive', 'driving', 'car', 'truck', 'simulator'],
                'lai xe'      => ['drive', 'driving', 'car', 'truck', 'simulator'],
                'bắn súng'    => ['shoot', 'shooter', 'gun', 'sniper', 'fps', 'strike', 'combat', 'war', 'army'],
                'ban sung'    => ['shoot', 'shooter', 'gun', 'sniper', 'fps', 'strike', 'combat', 'war', 'army'],
                'xạ thủ'      => ['sniper', 'shooter', 'target'],
                'xa thu'      => ['sniper', 'shooter', 'target'],
                'xếp hình'    => ['puzzle', 'block', 'tetris', 'match', 'tile', 'merge', 'connect', '2048'],
                'xep hinh'    => ['puzzle', 'block', 'tetris', 'match', 'tile', 'merge', 'connect', '2048'],
                'trí tuệ'     => ['brain', 'puzzle', 'logic', 'quiz', 'math', 'smart'],
                'tri tue'     => ['brain', 'puzzle', 'logic', 'quiz', 'math', 'smart'],
                'giải đố'     => ['puzzle', 'riddle', 'escape', 'solve', 'mystery'],
                'giai do'     => ['puzzle', 'riddle', 'escape', 'solve', 'mystery'],
                'thây ma'     => ['zombie', 'undead', 'dead', 'apocalypse', 'survival', 'monster'],
                'thay ma'     => ['zombie', 'undead', 'dead', 'apocalypse', 'survival', 'monster'],
                'xác sống'    => ['zombie', 'undead', 'dead', 'apocalypse'],
                'xac song'    => ['zombie', 'undead', 'dead', 'apocalypse'],
                'thể thao'    => ['sports', 'soccer', 'football', 'basketball', 'tennis', 'golf'],
                'the thao'    => ['sports', 'soccer', 'football', 'basketball', 'tennis', 'golf'],
                'bóng đá'     => ['soccer', 'football', 'penalty', 'fifa', 'goal'],
                'bong da'     => ['soccer', 'football', 'penalty', 'fifa', 'goal'],
                'nấu ăn'      => ['cook', 'cooking', 'kitchen', 'restaurant', 'food', 'cake', 'baking', 'chef'],
                'nau an'      => ['cook', 'cooking', 'kitchen', 'restaurant', 'food', 'cake', 'baking', 'chef'],
                'thời trang'  => ['dress', 'dressup', 'fashion', 'makeup', 'princess', 'beauty', 'salon', 'model'],
                'thoi trang'  => ['dress', 'dressup', 'fashion', 'makeup', 'princess', 'beauty', 'salon', 'model'],
                'công chúa'   => ['princess', 'queen', 'fairy', 'barbie'],
                'cong chua'   => ['princess', 'queen', 'fairy', 'barbie'],
                'chiến thuật' => ['tower', 'defense', 'strategy', 'kingdom', 'clash', 'empire', 'tactics'],
                'chien thuat' => ['tower', 'defense', 'strategy', 'kingdom', 'clash', 'empire', 'tactics'],
                'thủ thành'   => ['tower', 'defense', 'td', 'castle', 'protect'],
                'thu thanh'   => ['tower', 'defense', 'td', 'castle', 'protect'],
                'hành động'   => ['action', 'fight', 'fighter', 'battle', 'combat', 'ninja', 'sword', 'warrior'],
                'hanh dong'   => ['action', 'fight', 'fighter', 'battle', 'combat', 'ninja', 'sword', 'warrior'],
                'kinh dị'     => ['horror', 'scary', 'granny', 'creepy', 'escape', 'night', 'dark', 'ghost'],
                'kinh di'     => ['horror', 'scary', 'granny', 'creepy', 'escape', 'night', 'dark', 'ghost'],
                'chạy'        => ['runner', 'run', 'dash', 'rush', 'jump', 'subway'],
                'chay'        => ['runner', 'run', 'dash', 'rush', 'jump', 'subway'],
            ];

            $expandedKeywords = [$rawSearch];

            // Check if search phrase matches any semantic mapping
            foreach ($semanticAliases as $phrase => $synonyms) {
                if (str_contains($lowerSearch, $phrase)) {
                    $expandedKeywords = array_merge($expandedKeywords, $synonyms);
                }
            }

            // Split search terms into individual word tokens
            $tokens = preg_split('/\s+/', $lowerSearch, -1, PREG_SPLIT_NO_EMPTY);
            if ($tokens) {
                $expandedKeywords = array_merge($expandedKeywords, $tokens);
            }

            $uniqueKeywords = array_unique(array_filter($expandedKeywords, fn ($k) => mb_strlen(trim($k)) >= 2));

            $query->where(function ($q) use ($uniqueKeywords, $rawSearch) {
                $q->where('name', 'like', "%{$rawSearch}%")
                  ->orWhere('slug', 'like', "%{$rawSearch}%")
                  ->orWhere('summary', 'like', "%{$rawSearch}%");

                foreach ($uniqueKeywords as $kw) {
                    $q->orWhere('name', 'like', "%{$kw}%")
                      ->orWhere('slug', 'like', "%{$kw}%")
                      ->orWhere('summary', 'like', "%{$kw}%")
                      ->orWhere('description_markdown', 'like', "%{$kw}%")
                      ->orWhereHas('category', fn ($catQ) => $catQ->where('name', 'like', "%{$kw}%"));
                }
            });
        }

        // Dynamic Sorting
        match ($sort) {
            'latest'    => $query->orderByDesc('created_at'),
            'plays'     => $query->orderByDesc('play_count'),
            'title_asc' => $query->orderBy('name'),
            default     => $query->orderByDesc('is_featured')->orderByDesc('play_count'),
        };

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
