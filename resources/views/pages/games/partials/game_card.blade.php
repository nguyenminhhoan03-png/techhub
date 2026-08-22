<article class="game-portal-card {{ !empty($isFeaturedHero) ? 'is-hero-card' : '' }}">
    <a href="{{ route('games.show', $game->slug) }}" class="game-card-link" title="Chơi game {{ $game->name }} ngay">
        
        {{-- Cover Poster Artwork Container (16:10 Aspect Ratio) --}}
        <div class="game-card-cover">
            @include('pages.games.partials.game_poster', ['game' => $game])

            {{-- Floating Badges over Cover --}}
            <div class="game-cover-badges">
                <span class="game-badge-cat" style="background: rgba(15, 17, 32, 0.75); backdrop-filter: blur(8px); color: {{ $game->category->color }}; border: 1px solid {{ $game->category->color }}44;">
                    {{ $game->category->icon }} {{ $game->category->name }}
                </span>

                @if($game->is_featured)
                    <span class="game-badge-hot">
                        HOT 🔥
                    </span>
                @endif
            </div>
        </div>

        {{-- Card Info Details --}}
        <div class="game-card-body">
            <div class="game-card-meta">
                <span class="game-diff-tag" style="color: {{ $game->difficulty_color }}; background: {{ $game->difficulty_color }}18; border: 1px solid {{ $game->difficulty_color }}33;">
                    ● {{ $game->difficulty_label }}
                </span>
                <span class="game-plays-count">
                    🕹️ {{ number_format($game->play_count) }}
                </span>
            </div>

            <h3 class="game-card-title">
                {{ $game->name }}
            </h3>

            <p class="game-card-desc">
                {{ Str::limit($game->summary, 72) }}
            </p>
        </div>

    </a>
</article>
