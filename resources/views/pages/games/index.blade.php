@extends('layouts.app')

@section('meta_title', $activeCategory ? ($activeCategory->name . ' — Chơi Game ' . $activeCategory->name . ' Online Miễn Phí | TechHub Games') : (request('q') ? ('Tìm kiếm game: ' . e(request('q')) . ' | TechHub Games') : 'Web Games Online Miễn Phí — Chơi Game Trình Duyệt Không Cần Cài Đặt | TechHub'))
@section('meta_description', $activeCategory ? ($activeCategory->description . '. Chơi ngay trên trình duyệt máy tính và điện thoại, không cần tải về.') : 'Cổng game Web HTML5 miễn phí hàng đầu: 2048, Flappy Bird, Tetris, Snake Classic, Dino Runner, Brick Breaker, Dev Typing Speed. Chơi ngay trên trình duyệt mượt mà 100%.')

@push('schemas')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "{{ $activeCategory ? $activeCategory->name . ' — Game Online Miễn Phí' : 'Cổng Web Games Online Miễn Phí TechHub' }}",
  "description": "{{ $activeCategory ? $activeCategory->description : 'Kho game mini HTML5 chất lượng cao, chơi trực tiếp trên trình duyệt mọi thiết bị.' }}",
  "url": "{{ url()->current() }}",
  "mainEntity": {
    "@type": "ItemList",
    "name": "Danh Sách Game Online",
    "numberOfItems": {{ $games->count() }},
    "itemListElement": [
      @foreach($games as $index => $g)
      {
        "@type": "ListItem",
        "position": {{ $index + 1 }},
        "name": "{{ $g->name }}",
        "url": "{{ route('games.show', $g->slug) }}"
      }@if(!$loop->last),@endif
      @endforeach
    ]
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Trang Chủ",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Web Games",
      "item": "{{ route('games.index') }}"
    }
    @if($activeCategory)
    ,{
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $activeCategory->name }}",
      "item": "{{ route('games.index', ['category' => $activeCategory->slug]) }}"
    }
    @endif
  ]
}
</script>
@endpush

@section('content')
<section class="games-portal-section" style="padding: 2rem 0 4rem;">
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom: 1.5rem;">
            <a href="{{ url('/') }}">{{ __('home') }}</a>
            <span>/</span>
            @if($activeCategory || $search)
                <a href="{{ route('games.index') }}">{{ __('games') }}</a>
                <span>/</span>
                <span style="color: var(--text-main); font-weight: 600;">
                    {{ $activeCategory ? $activeCategory->display_name : (app()->getLocale() === 'en' ? 'Search: "' . e($search) . '"' : 'Tìm kiếm: "' . e($search) . '"') }}
                </span>
            @else
                <span style="color: var(--text-main); font-weight: 600;">🎮 {{ __('games') }} Portal</span>
            @endif
        </div>

        {{-- Portal Main Grid (Sidebar + Content) --}}
        <div class="games-layout" style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">

            {{-- ── LEFT SIDEBAR (CrazyGames Style) ── --}}
            <aside class="games-sidebar" style="position: sticky; top: 85px; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem; box-shadow: var(--shadow-card);">
                
                {{-- Quick Search Box --}}
                <div>
                    <label for="game-search-input" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">
                        🔍 {{ __('search') }} Game
                    </label>
                    <form action="{{ route('games.index') }}" method="GET" style="position: relative;">
                        <input
                            type="text"
                            name="q"
                            id="game-search-input"
                            value="{{ request('q') }}"
                            placeholder="{{ __('search_games_placeholder') }}"
                            style="width: 100%; padding: 0.6rem 2.2rem 0.6rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.85rem; outline: none; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='var(--accent-indigo)'"
                            onblur="this.style.borderColor='var(--border-subtle)'"
                        >
                        @if(request('q'))
                            <a href="{{ route('games.index') }}" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); text-decoration: none; font-size: 0.85rem;" title="Clear">✕</a>
                        @endif
                        @if($categorySlug)
                            <input type="hidden" name="category" value="{{ $categorySlug }}">
                        @endif
                    </form>

                    {{-- Popular Keyword Tags --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.65rem;">
                        <a href="{{ route('games.index', ['q' => '2048']) }}" style="font-size: 0.72rem; padding: 0.15rem 0.45rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-muted); text-decoration: none;">2048</a>
                        <a href="{{ route('games.index', ['q' => 'snake']) }}" style="font-size: 0.72rem; padding: 0.15rem 0.45rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-muted); text-decoration: none;">snake</a>
                        <a href="{{ route('games.index', ['q' => 'runner']) }}" style="font-size: 0.72rem; padding: 0.15rem 0.45rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-muted); text-decoration: none;">runner</a>
                        <a href="{{ route('games.index', ['q' => 'puzzle']) }}" style="font-size: 0.72rem; padding: 0.15rem 0.45rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-muted); text-decoration: none;">puzzle</a>
                    </div>
                </div>

                <div style="height: 1px; background: var(--border-subtle);"></div>

                {{-- Category Nav Links --}}
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.65rem;">
                        🗂️ {{ __('games') }}
                    </div>
                    <nav style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <a href="{{ route('games.index') }}"
                           class="sidebar-game-cat {{ !$categorySlug && !$search ? 'active' : '' }}"
                           style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); text-decoration: none; font-size: 0.9rem; font-weight: 600; color: {{ !$categorySlug && !$search ? 'var(--text-main)' : 'var(--text-sub)' }}; background: {{ !$categorySlug && !$search ? 'var(--bg-surface-elevated)' : 'transparent' }}; border: 1px solid {{ !$categorySlug && !$search ? 'var(--accent-indigo)' : 'transparent' }};">
                            <span style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>🎮</span> <span>{{ __('filter_all') }}</span>
                            </span>
                            <span style="font-size: 0.75rem; font-weight: 700; background: var(--border-subtle); color: var(--text-muted); padding: 0.15rem 0.5rem; border-radius: 999px;">
                                {{ $totalGamesCount }}
                            </span>
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('games.index', ['category' => $cat->slug]) }}"
                               class="sidebar-game-cat {{ $categorySlug === $cat->slug ? 'active' : '' }}"
                               style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); text-decoration: none; font-size: 0.9rem; font-weight: 600; color: {{ $categorySlug === $cat->slug ? 'var(--text-main)' : 'var(--text-sub)' }}; background: {{ $categorySlug === $cat->slug ? 'var(--bg-surface-elevated)' : 'transparent' }}; border: 1px solid {{ $categorySlug === $cat->slug ? $cat->color : 'transparent' }};">
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span>{{ $cat->icon }}</span> <span>{{ $cat->display_name }}</span>
                                </span>
                                <span style="font-size: 0.75rem; font-weight: 700; background: {{ $cat->color }}22; color: {{ $cat->color }}; padding: 0.15rem 0.5rem; border-radius: 999px;">
                                    {{ $cat->games_count }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- Quick Perks Badge --}}
                <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem; font-size: 0.8rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 0.4rem;">
                    <div>⚡ <strong>100% Miễn phí</strong></div>
                    <div>🚀 <strong>Chạy ngay trên trình duyệt</strong></div>
                    <div>📱 <strong>Hỗ trợ PC &amp; Mobile</strong></div>
                </div>

            </aside>

            {{-- ── RIGHT MAIN CONTENT ── --}}
            <main class="games-main-content" style="min-width: 0;">

                {{-- Quick Topic Filter Chips Carousel Bar --}}
                <div class="game-quick-chips" style="display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 0.75rem; margin-bottom: 1.5rem; scrollbar-width: thin;">
                    <a href="{{ route('games.index') }}"
                       style="white-space: nowrap; padding: 0.45rem 0.9rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.35rem; transition: all 0.2s; {{ !$categorySlug && !$search ? 'background: linear-gradient(135deg, var(--accent-indigo), var(--accent-purple)); color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.35);' : 'background: var(--bg-card); color: var(--text-sub); border: 1px solid var(--border-subtle);' }}">
                        <span>🎮</span> <span>Tất Cả</span>
                    </a>
                    @php
                        $quickTags = [
                            ['icon' => '👥', 'label' => 'Game 2 Người', 'q' => '2 người'],
                            ['icon' => '🏎️', 'label' => 'Đua Xe', 'q' => 'đua xe'],
                            ['icon' => '🔫', 'label' => 'Bắn Súng', 'q' => 'bắn súng'],
                            ['icon' => '🧩', 'label' => 'Trí Tuệ & Puzzle', 'q' => 'trí tuệ'],
                            ['icon' => '🧟', 'label' => 'Zombie & Sinh Tồn', 'q' => 'zombie'],
                            ['icon' => '⚽', 'label' => 'Bóng Đá & Thể Thao', 'q' => 'bóng đá'],
                            ['icon' => '🍳', 'label' => 'Nấu Ăn', 'q' => 'nấu ăn'],
                            ['icon' => '👑', 'label' => 'Thời Trang', 'q' => 'thời trang'],
                            ['icon' => '🏰', 'label' => 'Chiến Thuật', 'q' => 'chiến thuật'],
                            ['icon' => '🥋', 'label' => 'Hành Động', 'q' => 'hành động'],
                        ];
                    @endphp
                    @foreach($quickTags as $tag)
                        @php $isActive = mb_strtolower((string)$search) === mb_strtolower($tag['q']); @endphp
                        <a href="{{ route('games.index', ['q' => $tag['q']]) }}"
                           style="white-space: nowrap; padding: 0.45rem 0.9rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.35rem; transition: all 0.2s; {{ $isActive ? 'background: linear-gradient(135deg, var(--accent-indigo), var(--accent-purple)); color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.35);' : 'background: var(--bg-card); color: var(--text-sub); border: 1px solid var(--border-subtle);' }}">
                            <span>{{ $tag['icon'] }}</span> <span>{{ $tag['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                {{-- SEARCH OR CATEGORY FILTER ACTIVE VIEW --}}
                @if($activeCategory || $search)
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;">
                        <div>
                            <h1 style="font-size: 1.65rem; margin-bottom: 0.25rem;">
                                @if($activeCategory)
                                    {{ $activeCategory->icon }} {{ $activeCategory->name }}
                                @else
                                    🔍 Kết quả tìm kiếm: <span class="gradient-text">"{{ e($search) }}"</span>
                                @endif
                            </h1>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">
                                @if($activeCategory)
                                    {{ $activeCategory->description }} ({{ $games->count() }} games)
                                @else
                                    Tìm thấy <strong>{{ $games->count() }}</strong> trò chơi phù hợp.
                                @endif
                            </p>
                        </div>

                        {{-- Sort & Filter Controls --}}
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <form action="{{ route('games.index') }}" method="GET" style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($search)
                                    <input type="hidden" name="q" value="{{ $search }}">
                                @endif
                                @if($categorySlug)
                                    <input type="hidden" name="category" value="{{ $categorySlug }}">
                                @endif

                                <select name="sort" onchange="this.form.submit()" style="padding: 0.45rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.82rem; font-weight: 600; outline: none; cursor: pointer;">
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>🔥 Phổ biến nhất</option>
                                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>⚡ Mới nhất</option>
                                    <option value="plays" {{ request('sort') === 'plays' ? 'selected' : '' }}>🕹️ Chơi nhiều nhất</option>
                                    <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>🔤 Tên A - Z</option>
                                </select>

                                <select name="difficulty" onchange="this.form.submit()" style="padding: 0.45rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.82rem; font-weight: 600; outline: none; cursor: pointer;">
                                    <option value="">🎯 Mọi độ khó</option>
                                    <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>🟢 Dễ</option>
                                    <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>🟡 Vừa</option>
                                    <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>🔴 Khó</option>
                                </select>
                            </form>

                            <a href="{{ route('games.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 0.45rem 0.85rem;">
                                ✕ Bỏ Lọc
                            </a>
                        </div>
                    </div>

                    @if($games->isEmpty())
                        <div style="text-align: center; padding: 4rem 2rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); margin-bottom: 2rem;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">🎯</div>
                            <h3 style="margin-bottom: 0.5rem;">Không tìm thấy game nào phù hợp!</h3>
                            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Hãy thử tìm kiếm với các từ khóa gợi ý như: <em>"2 người", "đua xe", "bắn súng", "2048", "zombie"</em></p>
                            <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('games.index', ['q' => '2 người']) }}" class="btn btn-secondary btn-sm">👥 Game 2 Người</a>
                                <a href="{{ route('games.index', ['q' => 'đua xe']) }}" class="btn btn-secondary btn-sm">🏎️ Đua Xe</a>
                                <a href="{{ route('games.index', ['q' => 'bắn súng']) }}" class="btn btn-secondary btn-sm">🔫 Bắn Súng</a>
                                <a href="{{ route('games.index') }}" class="btn btn-primary btn-sm">Xem Toàn Bộ Games</a>
                            </div>
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
                            @foreach($games as $game)
                                @include('pages.games.partials.game_card', ['game' => $game])
                            @endforeach
                        </div>
                    @endif

                {{-- DEFAULT PORTAL HOME VIEW (CrazyGames Bento Hero + Categorized Rows) --}}
                @else

                    {{-- Hero Spotlight Bento Grid --}}
                    @php
                        $spotlightGame = $featuredGames->first();
                        $sideFeatured = $featuredGames->skip(1)->take(3);
                    @endphp

                    @if($spotlightGame)
                        <section style="margin-bottom: 2.75rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <div>
                                    <h1 style="font-size: 1.85rem; margin-bottom: 0.25rem; font-weight: 900;">
                                        🔥 Game <span class="gradient-text">Nổi Bật &amp; Phổ Biến Nhất</span>
                                    </h1>
                                    <p style="font-size: 0.95rem; color: var(--text-muted);">Top game HTML5 được chơi nhiều nhất hôm nay — click chơi ngay!</p>
                                </div>
                            </div>

                            <div class="gaming-bento-hero">
                                {{-- Big Spotlight Card --}}
                                <a href="{{ route('games.show', $spotlightGame->slug) }}" class="spotlight-main-card" style="position: relative;">
                                    <div style="position: absolute; inset: 0; z-index: 0;">
                                        @include('pages.games.partials.game_poster', ['game' => $spotlightGame])
                                    </div>
                                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(11,12,22,0.2) 0%, rgba(11,12,22,0.92) 80%); z-index: 1;"></div>
                                    
                                    <div style="position: relative; z-index: 2;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.65rem;">
                                            <span style="background: linear-gradient(135deg, #f43f5e, #fb923c); color: #fff; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 999px; box-shadow: 0 4px 14px rgba(244,63,94,0.5);">
                                                SPOTLIGHT 🌟
                                            </span>
                                            <span style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 999px;">
                                                {{ $spotlightGame->category->icon }} {{ $spotlightGame->category->name }}
                                            </span>
                                        </div>

                                        <h2 style="font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 0.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">
                                            {{ $spotlightGame->name }}
                                        </h2>

                                        <p style="color: rgba(255,255,255,0.85); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.25rem; max-width: 520px;">
                                            {{ $spotlightGame->summary }}
                                        </p>

                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            <span class="btn btn-primary" style="padding: 0.65rem 1.6rem; font-weight: 800; font-size: 0.95rem; box-shadow: 0 8px 24px rgba(99,102,241,0.5);">
                                                Chơi Ngay ▶
                                            </span>
                                            <span style="color: rgba(255,255,255,0.75); font-size: 0.85rem;">
                                                🕹️ {{ number_format($spotlightGame->play_count) }} lượt chơi
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                {{-- Side 3 Cards Grid --}}
                                <div style="display: grid; grid-template-columns: 1fr; gap: 0.85rem;">
                                    @foreach($sideFeatured as $sGame)
                                        <a href="{{ route('games.show', $sGame->slug) }}"
                                           style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 16px; text-decoration: none; transition: all 0.25s ease;"
                                           onmouseover="this.style.borderColor='var(--accent-indigo)';this.style.transform='translateX(6px)'"
                                           onmouseout="this.style.borderColor='var(--border-subtle)';this.style.transform='translateX(0)'">
                                            
                                            <div style="width: 80px; height: 60px; border-radius: 10px; overflow: hidden; flex-shrink: 0; position: relative;">
                                                @include('pages.games.partials.game_poster', ['game' => $sGame])
                                            </div>

                                            <div style="min-width: 0; flex-grow: 1;">
                                                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.2rem;">
                                                    <span style="font-size: 0.68rem; font-weight: 700; color: {{ $sGame->category->color }};">
                                                        {{ $sGame->category->name }}
                                                    </span>
                                                    <span style="font-size: 0.68rem; color: var(--text-muted);">•</span>
                                                    <span style="font-size: 0.68rem; color: {{ $sGame->difficulty_color }};">
                                                        {{ $sGame->difficulty_label }}
                                                    </span>
                                                </div>
                                                <div style="font-weight: 800; font-size: 1rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.2rem;">
                                                    {{ $sGame->name }}
                                                </div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                    🕹️ {{ number_format($sGame->play_count) }} lượt chơi
                                                </div>
                                            </div>

                                            <span style="font-size: 1.2rem; color: var(--accent-indigo); margin-left: auto;">
                                                ▶
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                    {{-- Sections by Category (CrazyGames Horizontal Category Grids) --}}
                    @foreach($categories as $category)
                        @if($category->activeGames->isNotEmpty())
                            <section style="margin-bottom: 3rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.15rem; padding-bottom: 0.65rem; border-bottom: 1px solid var(--border-subtle);">
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $category->color }}1a; border: 1px solid {{ $category->color }}44; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                            {{ $category->icon }}
                                        </div>
                                        <div>
                                            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0; color: var(--text-main);">
                                                {{ $category->name }}
                                            </h2>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">
                                                {{ $category->description }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('games.index', ['category' => $category->slug]) }}"
                                       style="font-size: 0.85rem; font-weight: 700; color: var(--accent-indigo); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; white-space: nowrap;">
                                        Xem tất cả ({{ $category->activeGames->count() }}) →
                                    </a>
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
                                    @foreach($category->activeGames as $game)
                                        @include('pages.games.partials.game_card', ['game' => $game])
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    @endforeach

                @endif

                {{-- ── SEO ARTICLE & FAQ BLOCK (Chuẩn SEO Onpage) ── --}}
                <article class="seo-rich-article" style="margin-top: 3.5rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.25rem; box-shadow: var(--shadow-card);">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-main);">
                        🎮 Giới Thiệu Cổng Web Games HTML5 Miễn Phí — TechHub Games
                    </h2>
                    <p style="line-height: 1.8; color: var(--text-sub); margin-bottom: 1.25rem; font-size: 0.95rem;">
                        <strong>TechHub Games</strong> là cổng trò chơi điện tử trực tuyến trên nền tảng <strong>HTML5 / WebGL</strong> hoàn toàn miễn phí. Người chơi có thể trải nghiệm ngay lập tức các tựa game nổi tiếng như <em>2048</em>, <em>Flappy Bird</em>, <em>Tetris</em>, <em>Snake Classic</em>, <em>Dino Runner</em>, <em>Brick Breaker</em>, và bài kiểm tra tốc độ gõ phím <em>Dev Typing Speed Test</em> mà không cần cài đặt bất kỳ phần mềm hay plugin nào.
                    </p>

                    <h3 style="font-size: 1.2rem; margin: 1.5rem 0 0.75rem; color: var(--text-main);">🌟 Điểm Nổi Bật Của Game Trên TechHub</h3>
                    <ul style="line-height: 1.8; color: var(--text-sub); margin-left: 1.25rem; margin-bottom: 1.5rem; font-size: 0.95rem;">
                        <li><strong>Chơi Ngay Tức Thì:</strong> Không cần đăng ký, không cần tải file .exe hay app cồng kềnh.</li>
                        <li><strong>Tối Ưu Đa Nền Tảng:</strong> Chạy mượt mà trên cả máy tính để bàn (PC/Mac) và điện thoại thông minh (iOS/Android).</li>
                        <li><strong>Bảo Mật &amp; Riêng Tư:</strong> Không quảng cáo gây phiền nhiễu, mã nguồn game nhẹ, tiết kiệm tài nguyên CPU/RAM.</li>
                        <li><strong>Đa Dạng Thể Loại:</strong> Từ game trí tuệ giải đố (Puzzle), hành động (Action), arcade cổ điển đến các game rèn luyện kỹ năng lập trình viên.</li>
                    </ul>

                    <h3 style="font-size: 1.2rem; margin: 1.5rem 0 0.75rem; color: var(--text-main);">❓ Câu Hỏi Thường Gặp (FAQs)</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.75rem;">
                        <details style="background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 0.85rem 1.15rem; cursor: pointer;">
                            <summary style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;">Chơi game trên TechHub có mất phí không?</summary>
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-sub); line-height: 1.6;">
                                Hoàn toàn 100% miễn phí. Bạn có thể chơi không giới hạn tất cả các trò chơi có trên cổng game.
                            </p>
                        </details>

                        <details style="background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 0.85rem 1.15rem; cursor: pointer;">
                            <summary style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;">Game có lưu lại điểm cao nhất (High Score) không?</summary>
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-sub); line-height: 1.6;">
                                Có, toàn bộ kỷ lục điểm số cao nhất của bạn đều được tự động lưu vào LocalStorage trên trình duyệt của bạn.
                            </p>
                        </details>
                    </div>
                </article>

            </main>

        </div>

    </div>
</section>
@endsection
