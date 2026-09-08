@extends('layouts.app')

@section('meta_title', ($game->meta_title ?? $game->name . ' — Chơi Online Miễn Phí Trên Trình Duyệt') . ' | TechHub Games')
@section('meta_description', $game->meta_description ?? ($game->summary . ' Chơi game ' . $game->name . ' online miễn phí không cần tải về, mượt mà trên máy tính và điện thoại.'))
@section('canonical_url', route('games.show', $game->slug))
@section('meta_keywords', $game->name . ', chơi ' . $game->name . ' online, ' . $game->category->name . ' online miễn phí, game trình duyệt, TechHub Games, HTML5 game')
@section('og_type', 'website')
@section('og_image', $game->thumbnail_url ?? asset('images/techhub-og.png'))

@push('head')
{{-- Game Engine Preconnect & DNS-Prefetch for low-latency loading --}}
<link rel="preconnect" href="https://play.gamepix.com" crossorigin>
<link rel="dns-prefetch" href="https://play.gamepix.com">
<link rel="preconnect" href="https://games.assets.gamepix.com" crossorigin>
<link rel="dns-prefetch" href="https://games.assets.gamepix.com">
<link rel="preconnect" href="https://api.gamepix.com" crossorigin>
<link rel="dns-prefetch" href="https://api.gamepix.com">
<link rel="preconnect" href="https://cs.iubenda.com" crossorigin>
<link rel="dns-prefetch" href="https://cs.iubenda.com">
<link rel="preconnect" href="https://html5.gamemonetize.com" crossorigin>
<link rel="dns-prefetch" href="https://html5.gamemonetize.com">
@endpush

@push('schemas')
{{-- Schema.org VideoGame Rich Snippet --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "VideoGame",
  "name": "{{ $game->name }}",
  "description": "{{ $game->meta_description ?? $game->summary }}",
  "url": "{{ route('games.show', $game->slug) }}",
  "image": "{{ $game->thumbnail_url ?? asset('images/techhub-og.png') }}",
  "genre": ["{{ $game->category->name }}", "Online Game", "Browser Game"],
  "gamePlatform": ["Web Browser", "HTML5", "PC", "Mobile"],
  "applicationCategory": "GameApplication",
  "operatingSystem": "Any",
  "inLanguage": "vi",
  "isAccessibleForFree": true,
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "VND",
    "availability": "https://schema.org/InStock"
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
        <div class="game-show-grid">

            {{-- ── LEFT: Game Player & Instructions ── --}}
            <div class="gs-left-col">

                {{-- Header Title & Controls --}}
                <div class="gs-header">
                    <div>
                        <h1 class="gs-title">{{ $game->display_name }}</h1>
                        <div class="gs-meta-row">
                            <a href="{{ route('games.index', ['category' => $game->category->slug]) }}"
                               class="game-badge-cat"
                               style="background: {{ $game->category->color }}22; color: {{ $game->category->color }}; border: 1px solid {{ $game->category->color }}33;">
                                {{ $game->category->icon }} {{ $game->category->display_name }}
                            </a>
                            <span class="game-diff-tag"
                                  style="background: {{ $game->difficulty_color }}22; color: {{ $game->difficulty_color }}; border: 1px solid {{ $game->difficulty_color }}33;">
                                {{ app()->getLocale() === 'en' ? 'Difficulty' : 'Độ khó' }}: {{ $game->difficulty_label }}
                            </span>
                            <span class="gs-meta-plays">
                                🕹️ {{ number_format($game->play_count) }} {{ app()->getLocale() === 'en' ? 'plays' : 'lượt chơi' }}
                            </span>
                            <span class="gs-meta-rating">
                                ⭐ 4.9 / 5.0
                            </span>
                        </div>
                    </div>

                    <div class="gs-btn-row">
                        <button type="button" onclick="reloadGameFrame()" class="btn btn-secondary btn-sm btn-icon" title="Tải lại game nếu bị lỗi">
                            <span>🔄</span> <span>Tải Lại</span>
                        </button>
                        <a href="{{ $game->engine_path }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm btn-icon" title="Mở chơi trực tiếp nếu bị chặn quảng cáo/iframe">
                            <span>🚀</span> <span>Mở Tab Riêng</span>
                        </a>
                        <button type="button" onclick="toggleFullscreen()" class="btn btn-secondary btn-sm btn-icon" title="Chơi toàn màn hình">
                            <span>⛶</span> <span>Toàn Màn Hình</span>
                        </button>
                        <a href="{{ route('games.index') }}" class="btn btn-secondary btn-sm">
                            ← Cổng Games
                        </a>
                    </div>
                </div>

                {{-- Iframe Game Container with Cinema Ambient Lighting --}}
                <div class="gs-iframe-wrap">
                    <div class="cinema-ambient-glow" style="--ambient-color: {{ $game->category->color }}66;"></div>
                    <div id="game-container" onclick="focusGameFrame()" style="width: 100%; aspect-ratio: 16 / 10; min-height: 540px; position: relative;">

                        {{-- Loading Spinner --}}
                        <div id="game-loader" class="gs-game-loader">
                            <div class="gs-loader-spinner"></div>
                            <div class="gs-loader-text">{{ app()->getLocale() === 'en' ? 'Launching ' . $game->name . '...' : 'Đang khởi chạy ' . $game->name . '...' }}</div>
                            <div class="gs-loader-hint">{{ app()->getLocale() === 'en' ? 'Connecting to game server...' : 'Đang kết nối máy chủ trò chơi...' }}</div>
                        </div>

                        {{-- Fallback Banner if slow --}}
                        <div id="game-fallback-banner" class="gs-fallback-banner">
                            <span class="gs-fallback-text">⚡ {{ app()->getLocale() === 'en' ? 'Game loading slowly? ' : 'Trò chơi tải chậm do đường truyền? ' }}</span>
                            <button type="button" onclick="reloadGameFrame()" class="gs-fallback-btn">🔄 {{ app()->getLocale() === 'en' ? 'Reload Game' : 'Tải lại ngay' }}</button>
                            <a href="{{ $game->engine_path }}" target="_blank" rel="noopener noreferrer" class="gs-fallback-btn" style="background: var(--accent-indigo, #6366f1); color: #fff; text-decoration: none; display: inline-flex; align-items: center; margin-left: 0.5rem;">
                                🚀 {{ app()->getLocale() === 'en' ? 'Open in New Tab' : 'Mở tab riêng' }}
                            </a>
                            <button type="button" onclick="document.getElementById('game-fallback-banner').style.display='none'" class="gs-fallback-close">✕ {{ app()->getLocale() === 'en' ? 'Close' : 'Đóng' }}</button>
                        </div>

                        <iframe
                            id="game-frame"
                            src="{{ $game->engine_path }}"
                            data-src="{{ $game->engine_path }}"
                            loading="eager"
                            frameborder="0"
                            scrolling="no"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen; monetization; camera; gamepad; keyboard-map; pointer-lock"
                            allowfullscreen="true"
                            webkitallowfullscreen="true"
                            mozallowfullscreen="true"
                            class="gs-game-frame"
                            title="{{ $game->name }}"
                            onload="handleGameLoaded()"
                        ></iframe>
                    </div>
                </div>

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
                <div style="margin-top: 2rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 2rem; position: relative; overflow: hidden;">

                    {{-- Decorative top-left gradient blob --}}
                    <div style="position: absolute; top: -40px; left: -40px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%); pointer-events: none;"></div>

                    {{-- Section Header --}}
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle);">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(99,102,241,0.35);">📖</div>
                        <div>
                            <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main); margin: 0; line-height: 1.3;">Hướng Dẫn Cách Chơi {{ $game->name }}</h2>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem; font-weight: 500;">Hướng dẫn chi tiết · Mẹo & Thủ thuật · Tính năng nổi bật</div>
                        </div>
                    </div>

                    <div class="game-guide-body">
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
    // --- Game State Flags & Watchdog ---
    let _gameLoaded = false;
    let _fallbackTimer = null;

    /**
     * Bắt đầu watchdog timer để hiện gợi ý tải lại / mở tab riêng nếu CDN máy chủ game quốc tế bị chậm.
     */
    function _startFallbackTimer(delayMs = 7500) {
        if (_fallbackTimer) clearTimeout(_fallbackTimer);
        _fallbackTimer = setTimeout(() => {
            if (!_gameLoaded) {
                const banner = document.getElementById('game-fallback-banner');
                if (banner) banner.style.display = 'block';
            }
        }, delayMs);
    }

    /**
     * Gọi bởi onload của iframe khi game hoàn tất tải mã nguồn.
     * Chặn triệt để sự kiện onload giả từ 'about:blank'.
     */
    function handleGameLoaded() {
        const frame = document.getElementById('game-frame');
        // Bỏ qua nếu iframe chưa có src hoặc đang ở about:blank
        if (!frame || !frame.src || frame.src === 'about:blank' || frame.src.endsWith('about:blank')) {
            return;
        }

        // Hủy watchdog timer vì game đã phản hồi thành công
        if (_fallbackTimer) {
            clearTimeout(_fallbackTimer);
            _fallbackTimer = null;
        }

        _gameLoaded = true;

        // Delay 500ms để Engine HTML5 Canvas bên trong iframe vẽ frame đầu tiên
        setTimeout(() => {
            const loader = document.getElementById('game-loader');
            const banner = document.getElementById('game-fallback-banner');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => { loader.style.display = 'none'; }, 400);
            }
            if (banner) banner.style.display = 'none';

            // Kích hoạt resize event để engine game điều chỉnh canvas theo tỉ lệ chính xác
            try {
                window.dispatchEvent(new Event('resize'));
            } catch (e) {}
        }, 500);
    }

    /**
     * Tải lại game an toàn mà không hủy đứt kết nối mạng hay gây race condition.
     */
    function reloadGameFrame() {
        const frame = document.getElementById('game-frame');
        const loader = document.getElementById('game-loader');
        const banner = document.getElementById('game-fallback-banner');
        if (!frame) return;

        _gameLoaded = false;
        if (banner) banner.style.display = 'none';

        if (loader) {
            const text = loader.querySelector('.gs-loader-text');
            if (text) text.textContent = '{{ app()->getLocale() === "en" ? "Reloading " . $game->name . "..." : "Đang tải lại " . $game->name . "..." }}';
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        }

        // Nạp lại src kèm timestamp để bypass cache lỗi hoặc load lại sạch
        const rawSrc = frame.getAttribute('data-src') || frame.src;
        const cleanUrl = rawSrc.replace(/([?&])_t=\d+/, '');
        const separator = cleanUrl.includes('?') ? '&' : '?';
        frame.src = cleanUrl + separator + '_t=' + Date.now();

        _startFallbackTimer(8500);
    }

    function focusGameFrame() {
        const frame = document.getElementById('game-frame');
        if (frame) {
            frame.focus();
            try { frame.contentWindow?.focus(); } catch (e) {}
        }
    }

    function toggleFullscreen() {
        const container = document.getElementById('game-container') || document.getElementById('game-frame');
        if (!container) return;

        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen().catch(() => {});
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.mozRequestFullScreen) {
                container.mozRequestFullScreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen().catch(() => {});
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
        }
    }

    // Khởi động watchdog timer khi DOM sẵn sàng
    document.addEventListener('DOMContentLoaded', () => {
        _startFallbackTimer(7500);
    });
</script>
@endsection
