@extends('layouts.app')

@section('meta_title', 'Cửa Hàng Tài Khoản AI & Bản Quyền Phần Mềm Giá Rẻ | TechHub Deals')
@section('meta_description', 'Mua tài khoản Google AI Pro (Gemini Advanced 5TB + Antigravity) chính chủ giá rẻ chỉ từ 50k. Kích hoạt trực tiếp trên email khách, bảo mật tuyệt đối, bảo hành full 12 tháng 1-đổi-1.')
@section('canonical_url', route('deals.index'))
@section('meta_keywords', 'tài khoản google ai pro giá rẻ, mua gemini pro 5tb, antigravity pro, gemini advanced, tài khoản ai chính chủ, techhub deals')
@section('og_type', 'website')
@section('og_image', asset('images/deals/gemini-pro-5tb.png'))

@section('content')
<section class="deals-portal-section" style="padding: 2.5rem 0 4.5rem;">
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom: 1.5rem;">
            <a href="{{ url('/') }}">{{ __('home') }}</a>
            <span>/</span>
            <span style="color: var(--text-main); font-weight: 600;">🔥 Cửa Hàng Tài Khoản AI &amp; Deals</span>
        </div>

        {{-- Hero Store Header Banner --}}
        <div style="background: linear-gradient(135deg, #0d1117 0%, #161b22 50%, #1e1b4b 100%); border: 1px solid rgba(99,102,241,0.3); border-radius: var(--radius-xl); padding: 2.5rem 2rem; margin-bottom: 2.5rem; position: relative; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.4);">
            {{-- Background ambient glow --}}
            <div style="position: absolute; right: -80px; top: -80px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(99,102,241,0.35) 0%, transparent 70%); pointer-events: none;"></div>
            <div style="position: absolute; left: 30%; bottom: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16,185,129,0.2) 0%, transparent 70%); pointer-events: none;"></div>

            <div style="position: relative; z-index: 2; max-width: 780px;">
                <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 0.35rem 0.85rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; color: #a5b4fc; margin-bottom: 1rem;">
                    <span>✨</span> <span>TIẾT KIỆM ĐẾN 90% — BẢN QUYỀN CHÍNH CHỦ CHO DEVELOPER</span>
                </div>
                <h1 style="font-size: 2.2rem; font-weight: 900; color: #ffffff; line-height: 1.25; margin-bottom: 0.85rem; letter-spacing: -0.02em;">
                    Cửa Hàng Tài Khoản Google AI Pro &amp; Cloud 5TB
                </h1>
                <p style="color: #cbd5e1; font-size: 1rem; line-height: 1.7; margin-bottom: 1.5rem;">
                    Nâng cấp tài khoản <strong>Google AI Pro (Gemini Advanced + 5TB Cloud + Antigravity)</strong> trực tiếp trên email chính chủ của bạn. Bảo mật riêng tư 100%, bảo hành suốt thời hạn 12 tháng.
                </p>

                {{-- Fast Contact Badges --}}
                <div style="display: flex; gap: 0.85rem; flex-wrap: wrap; align-items: center;">
                    <a href="https://zalo.me/0866655803" target="_blank" rel="noopener"
                       style="display: inline-flex; align-items: center; gap: 0.45rem; background: #0068ff; color: #ffffff; text-decoration: none; padding: 0.55rem 1.15rem; border-radius: 999px; font-size: 0.88rem; font-weight: 700; box-shadow: 0 4px 14px rgba(0,104,255,0.4);">
                        <span>💬</span> <span>Zalo: 0866655803</span>
                    </a>
                    <a href="https://t.me/hoannm" target="_blank" rel="noopener"
                       style="display: inline-flex; align-items: center; gap: 0.45rem; background: #229ed9; color: #ffffff; text-decoration: none; padding: 0.55rem 1.15rem; border-radius: 999px; font-size: 0.88rem; font-weight: 700; box-shadow: 0 4px 14px rgba(34,158,217,0.4);">
                        <span>🚀</span> <span>Telegram: @hoannm</span>
                    </a>
                    <span style="color: #94a3b8; font-size: 0.82rem; font-weight: 600;">⚡ Kích hoạt trong 5 - 15 phút</span>
                </div>
            </div>
        </div>

        {{-- Quick Category Filter Chips Bar --}}
        <div class="quick-chips-container" style="margin-bottom: 2rem;">
            <div id="deals-quick-chips" class="game-quick-chips">
                <a href="{{ route('deals.index') }}"
                   class="chip-pill {{ !$activeCategory && !$search ? 'active' : '' }}">
                    <span class="chip-icon">⚡</span> <span class="chip-text">Tất Cả Sản Phẩm</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('deals.index', ['category' => $cat]) }}"
                       class="chip-pill {{ $activeCategory === $cat ? 'active' : '' }}">
                        <span class="chip-icon">
                            @if(str_contains(strtolower($cat), 'google')) 🤖
                            @elseif(str_contains(strtolower($cat), 'chatgpt')) 💬
                            @elseif(str_contains(strtolower($cat), 'claude')) 🧠
                            @else 🛠️
                            @endif
                        </span>
                        <span class="chip-text">{{ $cat }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Products Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.75rem; margin-bottom: 3.5rem;">
            @forelse($deals as $deal)
                <div class="deal-store-card" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 2px 10px rgba(0,0,0,0.03); position: relative;"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--accent-indigo)';this.style.boxShadow='0 12px 30px rgba(99,102,241,0.15)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.borderColor='var(--border-subtle)';this.style.boxShadow='0 2px 10px rgba(0,0,0,0.03)'">

                    {{-- Product Image Wrap --}}
                    <div style="position: relative; aspect-ratio: 1 / 1; width: 100%; background: #0a0e17; overflow: hidden;">
                        @if($deal->thumbnail_url)
                            <img src="{{ $deal->thumbnail_url }}" alt="{{ $deal->name }}" loading="lazy"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                                🤖
                            </div>
                        @endif

                        {{-- Floating Badges (Top Left & Top Right) --}}
                        <div style="position: absolute; top: 12px; left: 12px; display: flex; gap: 0.35rem; z-index: 2;">
                            @if($deal->badge_text)
                                <span style="background: #7c3aed; color: #ffffff; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 4px; letter-spacing: 0.04em; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                    {{ $deal->badge_text }}
                                </span>
                            @endif
                        </div>

                        <div style="position: absolute; top: 12px; right: 12px; display: flex; gap: 0.35rem; z-index: 2;">
                            @if($deal->sub_badge)
                                <span style="background: rgba(15,23,42,0.85); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(6px); font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 4px;">
                                    {{ $deal->sub_badge }}
                                </span>
                            @endif
                        </div>

                        {{-- Discount Chip (Bottom Left) --}}
                        @if($deal->discount_percentage > 0)
                            <div style="position: absolute; bottom: 12px; left: 12px; background: #ef4444; color: #ffffff; font-size: 0.75rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 999px; box-shadow: 0 2px 6px rgba(239,68,68,0.4);">
                                -{{ $deal->discount_percentage }}%
                            </div>
                        @endif
                    </div>

                    {{-- Product Body --}}
                    <div style="padding: 1.25rem 1.35rem 1.35rem; display: flex; flex-direction: column; flex: 1;">
                        {{-- Tags Row --}}
                        @if(!empty($deal->tags))
                            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap; margin-bottom: 0.6rem;">
                                @foreach(array_slice($deal->tags, 0, 3) as $t)
                                    <span style="font-size: 0.72rem; color: #0284c7; background: rgba(2,132,199,0.1); border: 1px solid rgba(2,132,199,0.2); padding: 0.1rem 0.45rem; border-radius: 4px; font-weight: 600;">
                                        #{{ $t }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Product Title --}}
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); line-height: 1.4; margin-bottom: 0.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em;">
                            <a href="{{ route('deals.show', $deal->slug) }}" style="color: inherit; text-decoration: none;">
                                {{ $deal->name }}
                            </a>
                        </h3>

                        {{-- Social Proof (Rating & Sold) --}}
                        <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1rem;">
                            <span style="color: #f59e0b; font-weight: 700;">⭐ {{ number_format($deal->rating, 1) }}</span>
                            <span>•</span>
                            <span>{{ number_format($deal->rating_count ?: 346) }} đánh giá</span>
                            <span>•</span>
                            <span style="color: var(--accent-emerald); font-weight: 600;">Đã bán {{ number_format($deal->sold_count ?: 7700) }}</span>
                        </div>

                        {{-- Price Row --}}
                        <div style="margin-top: auto; padding-top: 0.85rem; border-top: 1px solid var(--border-subtle); display: flex; align-items: baseline; justify-content: space-between;">
                            <div>
                                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Chỉ từ</span>
                                <div style="display: flex; align-items: baseline; gap: 0.4rem;">
                                    <span style="font-size: 1.35rem; font-weight: 900; color: #10b981; letter-spacing: -0.02em;">
                                        {{ $deal->formatted_price }}
                                    </span>
                                    <span style="color: #10b981; font-size: 0.85rem;">✦</span>
                                    @if($deal->original_price)
                                        <span style="font-size: 0.82rem; color: var(--text-muted); text-decoration: line-through;">
                                            {{ $deal->formatted_original_price }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-primary btn-sm"
                               style="font-size: 0.82rem; font-weight: 700; padding: 0.45rem 0.95rem; border-radius: var(--radius-sm);">
                                Xem Gói →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 3.5rem; text-align: center; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg);">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📦</div>
                    <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem;">Chưa tìm thấy sản phẩm phù hợp</h3>
                    <p style="color: var(--text-sub); font-size: 0.9rem; margin-bottom: 1.25rem;">Hãy thử chọn danh mục khác hoặc liên hệ trực tiếp với chúng tôi qua Zalo.</p>
                    <a href="{{ route('deals.index') }}" class="btn btn-secondary btn-sm">Xem Tất Cả Sản Phẩm</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($deals->hasPages())
            <div style="display: flex; justify-content: center; margin-bottom: 3.5rem;">
                {{ $deals->links() }}
            </div>
        @endif

        {{-- Trust & Support Block --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.75rem; box-shadow: var(--shadow-card);">
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    🛡️
                </div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Bảo Vệ Bởi Escrow</h4>
                    <p style="font-size: 0.82rem; color: var(--text-sub); line-height: 1.6;">Giao dịch minh bạch, bảo lưu thanh toán 72h cho tới khi bạn kích hoạt thành công.</p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    ⚡
                </div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Giao Hàng Tức Thì</h4>
                    <p style="font-size: 0.82rem; color: var(--text-sub); line-height: 1.6;">Xử lý và kích hoạt tài khoản trong vòng 5 - 15 phút sau khi nhận thông tin email.</p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    🔒
                </div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Chính Chủ &amp; Bảo Hành 1 Năm</h4>
                    <p style="font-size: 0.82rem; color: var(--text-sub); line-height: 1.6;">Kích hoạt trên tài khoản cá nhân, bảo hành trọn vẹn 12 tháng 1-đổi-1.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
