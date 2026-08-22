@php
    $slug = $game->slug;
@endphp

<div class="game-poster-wrapper game-poster-{{ $slug }}" style="position: relative; width: 100%; height: 100%; min-height: 140px; border-radius: inherit; overflow: hidden; display: flex; align-items: center; justify-content: center;">

    {{-- 1. If Game has an external HD CDN Thumbnail (from Game Feed API) --}}
    @if(!empty($game->thumbnail_url))
        <img
            src="{{ $game->thumbnail_url }}"
            alt="{{ $game->name }}"
            loading="lazy"
            class="poster-bg"
            style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; transition: transform 0.4s ease;"
        >

    {{-- 2. Custom Handcrafted Vector Themes for Classic Games --}}
    @elseif($slug === '2048')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #d97706 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(245,158,11,0.2) 1px, transparent 0); background-size: 16px 16px;"></div>
        <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 8px;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #edc22e; color: #fff; font-weight: 900; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(237,194,46,0.6); transform: rotate(-6deg);">2048</div>
            <div style="width: 36px; height: 36px; border-radius: 8px; background: #f59563; color: #fff; font-weight: 800; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(245,149,99,0.5); transform: rotate(8deg);">1024</div>
        </div>

    @elseif($slug === 'flappy-bird')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #0284c7 100%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 24px; background: #166534; border-top: 3px solid #22c55e;"></div>
        <div style="position: absolute; right: 24px; bottom: 24px; width: 22px; height: 50px; background: #15803d; border-radius: 4px 4px 0 0; border: 2px solid #22c55e;"></div>
        <div style="position: absolute; right: 24px; top: 0; width: 22px; height: 40px; background: #15803d; border-radius: 0 0 4px 4px; border: 2px solid #22c55e;"></div>
        <div style="position: relative; z-index: 2; font-size: 3.2rem; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.5)); transform: translateY(-4px) rotate(-8deg);">🐦</div>

    @elseif($slug === 'tetris')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #09090b 0%, #2e1065 50%, #701a75 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: linear-gradient(to right, rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 18px 18px;"></div>
        <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 4px;">
            <div style="display: flex; gap: 3px;">
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #06b6d4; box-shadow: 0 0 10px #06b6d4;"></div>
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #06b6d4; box-shadow: 0 0 10px #06b6d4;"></div>
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #06b6d4; box-shadow: 0 0 10px #06b6d4;"></div>
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #06b6d4; box-shadow: 0 0 10px #06b6d4;"></div>
            </div>
            <div style="display: flex; gap: 3px; margin-left: 20px;">
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #e11d48; box-shadow: 0 0 10px #e11d48;"></div>
                <div style="width: 18px; height: 18px; border-radius: 3px; background: #e11d48; box-shadow: 0 0 10px #e11d48;"></div>
            </div>
        </div>

    @elseif($slug === 'snake')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #022c22 0%, #064e3b 60%, #14532d 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(34,197,94,0.15) 1.5px, transparent 0); background-size: 14px 14px;"></div>
        <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 6px;">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: #22c55e; box-shadow: 0 0 14px #22c55e;"></div>
            <div style="width: 20px; height: 20px; border-radius: 5px; background: #4ade80;"></div>
            <div style="width: 18px; height: 18px; border-radius: 4px; background: #86efac;"></div>
            <div style="width: 16px; height: 16px; border-radius: 50%; background: #ef4444; margin-left: 14px; box-shadow: 0 0 12px #ef4444;"></div>
        </div>

    @elseif($slug === 'asteroid-shooter')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #1e1b4b 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.4) 1px, transparent 0); background-size: 24px 24px;"></div>
        <div style="position: relative; z-index: 2; font-size: 3rem; filter: drop-shadow(0 0 18px rgba(56,189,248,0.7)); transform: rotate(-20deg);">🚀</div>

    @elseif($slug === 'breakout')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #18002e 0%, #3b0764 50%, #831843 100%);"></div>
        <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 8px;">
            <div style="display: flex; gap: 4px;">
                <div style="width: 24px; height: 8px; border-radius: 2px; background: #f43f5e; box-shadow: 0 0 8px #f43f5e;"></div>
                <div style="width: 24px; height: 8px; border-radius: 2px; background: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></div>
                <div style="width: 24px; height: 8px; border-radius: 2px; background: #10b981; box-shadow: 0 0 8px #10b981;"></div>
                <div style="width: 24px; height: 8px; border-radius: 2px; background: #3b82f6; box-shadow: 0 0 8px #3b82f6;"></div>
            </div>
            <div style="width: 10px; height: 10px; border-radius: 50%; background: #fff; box-shadow: 0 0 12px #fff; transform: translateY(4px);"></div>
            <div style="width: 50px; height: 6px; border-radius: 3px; background: #38bdf8; box-shadow: 0 0 10px #38bdf8; margin-top: 6px;"></div>
        </div>

    @elseif($slug === 'pong')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #020617 0%, #0f172a 100%);"></div>
        <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; border-left: 2px dashed rgba(255,255,255,0.2);"></div>
        <div style="position: absolute; left: 16px; width: 6px; height: 36px; border-radius: 2px; background: #22c55e; box-shadow: 0 0 10px #22c55e;"></div>
        <div style="position: absolute; right: 16px; width: 6px; height: 36px; border-radius: 2px; background: #ef4444; box-shadow: 0 0 10px #ef4444;"></div>
        <div style="position: relative; z-index: 2; width: 12px; height: 12px; border-radius: 50%; background: #fff; box-shadow: 0 0 14px #fff;"></div>

    @elseif($slug === 'dino-runner')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #1c1917 0%, #292524 60%, #44403c 100%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 20px; border-top: 2px solid rgba(255,255,255,0.2);"></div>
        <div style="position: relative; z-index: 2; display: flex; align-items: flex-end; gap: 14px;">
            <div style="font-size: 2.8rem; transform: translateY(-4px);">🦕</div>
            <div style="font-size: 1.8rem; color: #22c55e; margin-bottom: 2px;">🌵</div>
        </div>

    @elseif($slug === 'platformer-jump')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #4f46e5 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
        <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 6px;">
            <div style="font-size: 1.8rem; filter: drop-shadow(0 0 10px #f59e0b);">🪙</div>
            <div style="width: 44px; height: 6px; border-radius: 3px; background: #22c55e; box-shadow: 0 0 10px #22c55e;"></div>
            <div style="font-size: 2rem; transform: translateY(-2px);">🏃</div>
        </div>

    @elseif($slug === 'memory-match')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #3b0764 0%, #581c87 50%, #7e22ce 100%);"></div>
        <div style="position: relative; z-index: 2; display: flex; gap: 10px;">
            <div style="width: 42px; height: 56px; border-radius: 8px; background: linear-gradient(135deg,#9333ea,#c084fc); border: 2px solid #e9d5ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 18px rgba(0,0,0,0.4); transform: rotate(-8deg);">💎</div>
            <div style="width: 42px; height: 56px; border-radius: 8px; background: linear-gradient(135deg,#9333ea,#c084fc); border: 2px solid #e9d5ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 18px rgba(0,0,0,0.4); transform: rotate(8deg);">💎</div>
        </div>

    @elseif($slug === 'simon-says')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #09090b 0%, #18181b 100%);"></div>
        <div style="position: relative; z-index: 2; width: 64px; height: 64px; border-radius: 50%; display: grid; grid-template-columns: 1fr 1fr; gap: 4px; padding: 4px; background: #000; box-shadow: 0 0 24px rgba(255,255,255,0.15);">
            <div style="border-radius: 20px 4px 4px 4px; background: #22c55e; box-shadow: 0 0 10px #22c55e;"></div>
            <div style="border-radius: 4px 20px 4px 4px; background: #ef4444; box-shadow: 0 0 10px #ef4444;"></div>
            <div style="border-radius: 4px 4px 4px 20px; background: #f59e0b; box-shadow: 0 0 10px #f59e0b;"></div>
            <div style="border-radius: 4px 4px 20px 4px; background: #3b82f6; box-shadow: 0 0 10px #3b82f6;"></div>
        </div>

    @elseif($slug === 'whack-a-mole')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #1c1917 0%, #451a03 50%, #78350f 100%);"></div>
        <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 8px;">
            <div style="font-size: 2.8rem; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.5));">🦔</div>
            <div style="font-size: 2.2rem; transform: rotate(25deg); filter: drop-shadow(0 0 10px #f59e0b);">🔨</div>
        </div>

    @elseif($slug === 'math-sprint')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #042f2e 0%, #115e59 50%, #0f766e 100%);"></div>
        <div style="position: relative; z-index: 2; font-family: 'Courier New', monospace; font-size: 1.8rem; font-weight: 900; color: #2dd4bf; text-shadow: 0 0 14px rgba(45,212,191,0.8); letter-spacing: 2px;">
            42 × 7 = ?
        </div>

    @elseif($slug === 'color-flood')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #f43f5e 0%, #a855f7 33%, #3b82f6 66%, #10b981 100%);"></div>
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35);"></div>
        <div style="position: relative; z-index: 2; display: grid; grid-template-columns: repeat(3, 16px); gap: 4px;">
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #f43f5e;"></div>
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #a855f7;"></div>
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #3b82f6;"></div>
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #10b981;"></div>
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #f59e0b;"></div>
            <div style="width: 16px; height: 16px; border-radius: 3px; background: #ec4899;"></div>
        </div>

    @elseif($slug === 'dev-typing-speed')
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, #09090b 0%, #1e1e2e 50%, #181825 100%);"></div>
        <div style="position: absolute; inset: 0; background-image: linear-gradient(rgba(137,180,250,0.06) 1px, transparent 1px); background-size: 100% 12px;"></div>
        <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 4px;">
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; font-weight: 800; color: #a6e3a1; text-shadow: 0 0 10px rgba(166,227,161,0.6);">
                const speed = 120;
            </div>
            <div style="font-size: 0.75rem; color: #89b4fa; font-weight: 700; background: rgba(137,180,250,0.15); padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(137,180,250,0.3);">
                WPM MASTER ⌨️
            </div>
        </div>

    @else
        <div class="poster-bg" style="position: absolute; inset: 0; background: linear-gradient(135deg, {{ $game->category->color }}33, {{ $game->category->color }}88);"></div>
        <div style="position: relative; z-index: 2; font-size: 3rem;">🎮</div>
    @endif

    {{-- Dark Vignette Overlay for Crisp Contrast --}}
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%); pointer-events: none; z-index: 1;"></div>

    {{-- Hover Play Button Overlay (CrazyGames style) --}}
    <div class="poster-play-btn" style="position: absolute; z-index: 3; width: 48px; height: 48px; border-radius: 50%; background: var(--gradient-brand, linear-gradient(135deg,#6366f1,#8b5cf6)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; box-shadow: 0 8px 24px rgba(99,102,241,0.6); opacity: 0; transform: scale(0.7); transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); pointer-events: none;">
        ▶
    </div>

</div>
