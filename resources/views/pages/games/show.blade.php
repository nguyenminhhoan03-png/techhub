@extends('layouts.app')

@section('meta_title', ($game->meta_title ?? $game->name . ' — Chơi Online Miễn Phí Trên Trình Duyệt') . ' | TechHub Games')
@section('meta_description', $game->meta_description ?? ($game->summary . ' Chơi game ' . $game->name . ' online miễn phí không cần tải về, mượt mà trên máy tính và điện thoại.'))

@push('schemas')
{{-- Schema.org VideoGame Rich Snippet --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "VideoGame",
  "name": "{{ $game->name }}",
  "description": "{{ $game->meta_description ?? $game->summary }}",
  "url": "{{ route('games.show', $game->slug) }}",
  "genre": ["{{ $game->category->name }}", "Online Game", "Browser Game"],
  "gamePlatform": ["Web Browser", "HTML5", "PC", "Mobile"],
  "applicationCategory": "GameApplication",
  "operatingSystem": "Any",
  "inLanguage": "vi",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "VND",
    "availability": "https://schema.org/InStock"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "ratingCount": "{{ max(50, (int)($game->play_count / 10)) }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  "publisher": {
    "@type": "Organization",
    "name": "TechHub",
    "url": "{{ url('/') }}"
  }
}
</script>
{{-- Schema.org BreadcrumbList --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "{{ __('home') }}",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ __('games') }}",
      "item": "{{ route('games.index') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $game->category->display_name }}",
      "item": "{{ route('games.index', ['category' => $game->category->slug]) }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ $game->display_name }}",
      "item": "{{ route('games.show', $game->slug) }}"
    }
  ]
}
</script>
@endpush

@section('content')
<div style="padding: 2rem 0 4rem;">
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom: 1.5rem;">
            <a href="{{ url('/') }}">{{ __('home') }}</a>
            <span>/</span>
            <a href="{{ route('games.index') }}">{{ __('games') }}</a>
            <span>/</span>
            <a href="{{ route('games.index', ['category' => $game->category->slug]) }}">{{ $game->category->display_name }}</a>
            <span>/</span>
            <span style="color: var(--text-main); font-weight: 600;">{{ $game->display_name }}</span>
        </div>

        {{-- 2-Column Game Player Layout --}}
        <div class="game-show-grid" style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;">

            {{-- ── LEFT: Game Player & Instructions ── --}}
            <div style="min-width: 0;">

                {{-- Header Title & Controls --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h1 style="font-size: 1.85rem; margin-bottom: 0.4rem; color: var(--text-main);">
                            {{ $game->display_name }}
                        </h1>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="{{ route('games.index', ['category' => $game->category->slug]) }}"
                               style="background: {{ $game->category->color }}22; color: {{ $game->category->color }}; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px; border: 1px solid {{ $game->category->color }}33; text-decoration: none;">
                                {{ $game->category->icon }} {{ $game->category->display_name }}
                            </a>
                            <span style="font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px; background: {{ $game->difficulty_color }}22; color: {{ $game->difficulty_color }}; border: 1px solid {{ $game->difficulty_color }}33;">
                                {{ app()->getLocale() === 'en' ? 'Difficulty' : 'Độ khó' }}: {{ $game->difficulty_label }}
                            </span>
                            <span style="font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.25rem;">
                                🕹️ {{ number_format($game->play_count) }} {{ app()->getLocale() === 'en' ? 'plays' : 'lượt chơi' }}
                            </span>
                            <span style="font-size: 0.78rem; color: #f59e0b; font-weight: 700;">
                                ⭐ 4.9 / 5.0
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap;">
                        <button type="button" onclick="reloadGameFrame()" class="btn btn-secondary btn-sm" title="Tải lại game nếu bị lỗi" style="display: flex; align-items: center; gap: 0.35rem;">
                            <span>🔄</span> <span>Tải Lại</span>
                        </button>
                        <a href="{{ $game->engine_path }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" title="Mở chơi trực tiếp nếu bị chặn quảng cáo/iframe" style="display: flex; align-items: center; gap: 0.35rem;">
                            <span>🚀</span> <span>Mở Tab Riêng</span>
                        </a>
                        <button type="button" onclick="toggleFullscreen()" class="btn btn-secondary btn-sm" title="Chơi toàn màn hình" style="display: flex; align-items: center; gap: 0.35rem;">
                            <span>⛶</span> <span>Toàn Màn Hình</span>
                        </button>
                        <a href="{{ route('games.index') }}" class="btn btn-secondary btn-sm">
                            ← Cổng Games
                        </a>
                    </div>
                </div>

                {{-- Iframe Game Container with Cinema Ambient Lighting & Responsive Aspect Ratio --}}
                <div style="position: relative; margin-bottom: 1.25rem;">
                    <div class="cinema-ambient-glow" style="--ambient-color: {{ $game->category->color }}66;"></div>
                    <div id="game-container" onclick="focusGameFrame()" style="position: relative; z-index: 1; width: 100%; aspect-ratio: 16 / 10; min-height: 500px; max-height: calc(100vh - 120px); background: #0d1117; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.12); box-shadow: 0 20px 60px rgba(0,0,0,0.6); cursor: pointer;">
                        
                        {{-- Loading Skeleton & Smooth Placeholder --}}
                        <div id="game-loader" style="position: absolute; inset: 0; z-index: 2; background: #0d1117; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; transition: opacity 0.4s ease; pointer-events: none;">
                            <div style="width: 48px; height: 48px; border: 4px solid rgba(255,255,255,0.1); border-top-color: var(--accent-cyan); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                            <div style="color: #94a3b8; font-size: 0.92rem; font-weight: 600;">Đang khởi chạy {{ $game->name }}...</div>
                        </div>

                        <iframe
                            id="game-frame"
                            src="{{ $game->engine_path }}"
                            loading="eager"
                            frameborder="0"
                            scrolling="auto"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen; monetization; camera; gamepad; keyboard-map; pointer-lock"
                            allowfullscreen="true"
                            webkitallowfullscreen="true"
                            mozallowfullscreen="true"
                            style="position: absolute; inset: 0; width: 100%; height: 100%; border: none; display: block;"
                            title="{{ $game->name }}"
                            onload="handleGameLoaded()"
                        ></iframe>
                    </div>
                </div>

                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>

                {{-- Controls & Cookie Hint Bar --}}
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; margin-top: 0.85rem; padding: 0.75rem 1.15rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 0.86rem; color: var(--text-sub); flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.1rem;">⌨️</span>
                        <span><strong>Phím điều khiển:</strong> {{ $game->controls_hint ?: 'Chuột / Cảm Ứng / Phím Mũi Tên' }}</span>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        💡 Mẹo: Nhấn <strong>⛶ Toàn Màn Hình</strong> để phóng to và bấm <em>"Accept / Đồng ý"</em> Cookie nếu có.
                    </div>
                </div>

                {{-- Description & Guide Section (SEO Onpage Rich Content) --}}
                <div style="margin-top: 2rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 2rem;">
                    <h2 style="font-size: 1.35rem; margin-bottom: 1rem; color: var(--text-main);">
                        📖 Hướng Dẫn Cách Chơi {{ $game->name }}
                    </h2>
                    
                    <div style="color: var(--text-sub); line-height: 1.8; font-size: 0.95rem;">
                        @if($game->description_markdown)
                            {!! Str::markdown($game->description_markdown) !!}
                        @else
                            <p>{{ $game->summary }}</p>
                        @endif
                    </div>

                    {{-- Features & Tech Notes --}}
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.25rem;">⚡ Nền tảng</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">HTML5 Canvas / Vanilla JS</div>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.25rem;">📱 Tương thích</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Chrome, Firefox, Safari, Edge, Mobile</div>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.25rem;">💾 Lưu trữ</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">Tự động lưu High Score tại trình duyệt</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Sidebar Stats & Related Games ── --}}
            <aside class="game-show-sidebar" style="display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 85px;">

                {{-- Stats Card --}}
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
                    <h3 style="font-size: 1.05rem; margin-bottom: 1.25rem; color: var(--text-main);">
                        📊 Thông Tin Trò Chơi
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                            <span style="color: var(--text-muted);">🕹️ Lượt chơi</span>
                            <strong>{{ number_format($game->play_count) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                            <span style="color: var(--text-muted);">⚡ Độ khó</span>
                            <strong style="color: {{ $game->difficulty_color }}">{{ $game->difficulty_label }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                            <span style="color: var(--text-muted);">🗂️ Danh mục</span>
                            <strong>{{ $game->category->name }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                            <span style="color: var(--text-muted);">⭐ Đánh giá</span>
                            <strong style="color: #f59e0b;">4.9 / 5.0</strong>
                        </div>
                    </div>

                    {{-- Play record button --}}
                    <form action="{{ route('games.play', $game->slug) }}" method="POST" style="margin-top: 1.25rem;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            🎮 Ghi Lại Lượt Chơi
                        </button>
                    </form>
                </div>

                {{-- Related Games in Same Category --}}
                @if($related->isNotEmpty())
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
                        <h3 style="font-size: 1.05rem; margin-bottom: 1.15rem; color: var(--text-main);">
                            🎲 {{ app()->getLocale() === 'en' ? 'Similar Games' : 'Game Cùng Thể Loại' }}
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($related as $r)
                                <a href="{{ route('games.show', $r->slug) }}"
                                   style="display: flex; align-items: center; gap: 0.85rem; padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); text-decoration: none; transition: var(--transition-fast);"
                                   onmouseover="this.style.borderColor='var(--accent-indigo)';this.style.transform='translateX(4px)'"
                                   onmouseout="this.style.borderColor='var(--border-subtle)';this.style.transform='translateX(0)'">
                                    <div style="width: 56px; height: 42px; border-radius: 8px; overflow: hidden; flex-shrink: 0; position: relative;">
                                        @include('pages.games.partials.game_poster', ['game' => $r])
                                    </div>
                                    <div style="min-width: 0; flex-grow: 1;">
                                        <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $r->display_name }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            🕹️ {{ number_format($r->play_count) }} {{ app()->getLocale() === 'en' ? 'plays' : 'lượt' }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- All Categories Quick Nav --}}
                @if(isset($categories) && $categories->isNotEmpty())
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; margin-bottom: 0.85rem; color: var(--text-main);">
                            🗂️ {{ app()->getLocale() === 'en' ? 'Explore Categories' : 'Khám Phá Thể Loại Khác' }}
                        </h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                            @foreach($categories as $cat)
                                <a href="{{ route('games.index', ['category' => $cat->slug]) }}"
                                   style="font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 999px; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); color: var(--text-sub); text-decoration: none; transition: var(--transition-fast);"
                                   onmouseover="this.style.borderColor='var(--accent-indigo)';this.style.color='var(--text-main)'"
                                   onmouseout="this.style.borderColor='var(--border-subtle)';this.style.color='var(--text-sub)'">
                                    {{ $cat->icon }} {{ $cat->display_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>
        </div>

    </div>
</div>

<script>
    function handleGameLoaded() {
        const loader = document.getElementById('game-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }
        // Dispatch instant canvas resize pulse
        window.dispatchEvent(new Event('resize'));
    }

    function reloadGameFrame() {
        const frame = document.getElementById('game-frame');
        const loader = document.getElementById('game-loader');
        if (loader) {
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        }
        if (frame) {
            const currentSrc = frame.src;
            frame.src = 'about:blank';
            setTimeout(() => {
                frame.src = currentSrc;
            }, 100);
        }
    }

    function focusGameFrame() {
        const frame = document.getElementById('game-frame');
        if (frame) {
            frame.focus();
            try {
                frame.contentWindow?.focus();
            } catch (e) {}
        }
    }

    function toggleFullscreen() {
        const container = document.getElementById('game-container') || document.getElementById('game-frame');
        if (container.requestFullscreen) {
            container.requestFullscreen();
        } else if (container.webkitRequestFullscreen) {
            container.webkitRequestFullscreen();
        } else if (container.mozRequestFullScreen) {
            container.mozRequestFullScreen();
        }
    }

    // Auto Layout Hydration Watchdog for HTML5 Canvas engines
    document.addEventListener('DOMContentLoaded', () => {
        [150, 500, 1200, 2500].forEach(delay => {
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, delay);
        });
    });
</script>
@endsection
