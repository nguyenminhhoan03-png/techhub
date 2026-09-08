@extends('layouts.app')

@section('meta_title', ($deal->meta_title ?? $deal->name . ' — Giá Rẻ Chính Chủ') . ' | TechHub Deals')
@section('meta_description', $deal->meta_description ?? ($deal->summary . ' Nâng cấp chính chủ riêng tư, bảo hành full 12 tháng 1-đổi-1.'))
@section('canonical_url', route('deals.show', $deal->slug))
@section('meta_keywords', $deal->name . ', ' . (is_array($deal->tags) ? implode(', ', $deal->tags) : '') . ', tài khoản ai giá rẻ, techhub deals')
@section('og_type', 'product')
@section('og_image', $deal->thumbnail_url ? asset($deal->thumbnail_url) : asset('images/deals/gemini-pro-5tb.png'))

@push('schemas')
{{-- Schema.org Product & Offer Rich Snippet for Google Search --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ $deal->name }}",
  "image": ["{{ $deal->thumbnail_url ? asset($deal->thumbnail_url) : asset('images/deals/gemini-pro-5tb.png') }}"],
  "description": "{{ $deal->summary }}",
  "sku": "DEAL-{{ $deal->id }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $deal->sub_badge ?: 'TechHub' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('deals.show', $deal->slug) }}",
    "priceCurrency": "VND",
    "price": "{{ $deal->price }}",
    "priceValidUntil": "2027-12-31",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "TechHub Deals"
    }
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $deal->rating }}",
    "reviewCount": "{{ $deal->rating_count ?: 346 }}"
  }
}
</script>
@endpush

@section('content')
<section class="deal-detail-section" style="padding: 2rem 0 4.5rem; background: var(--bg-main);">
    <div class="container">

        {{-- Back Link --}}
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('deals.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--text-sub); text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: color 0.2s;"
               onmouseover="this.style.color='var(--accent-indigo)'" onmouseout="this.style.color='var(--text-sub)'">
                <span>←</span> <span>Quay lại Cửa hàng</span>
            </a>
        </div>

        {{-- 2-Column Product Showcase (Dark Cinema Aesthetic) --}}
        <div class="deal-showcase-grid" style="display: grid; grid-template-columns: 480px 1fr; gap: 2.5rem; align-items: start; margin-bottom: 3.5rem;">

            {{-- ── LEFT: Product Image Showcase ── --}}
            <div style="position: relative;">
                {{-- Ambient Glow --}}
                <div style="position: absolute; inset: -15px; background: radial-gradient(circle, rgba(99,102,241,0.3) 0%, transparent 70%); filter: blur(35px); z-index: 0; pointer-events: none;"></div>

                <div style="position: relative; z-index: 1; aspect-ratio: 1 / 1; width: 100%; border-radius: var(--radius-xl); overflow: hidden; background: #07090e; border: 1px solid rgba(255,255,255,0.12); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);">
                    @if($deal->thumbnail_url)
                        <img id="main-deal-img" src="{{ $deal->thumbnail_url }}" alt="{{ $deal->name }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 5rem; background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                            🤖
                        </div>
                    @endif

                    {{-- Top Left Floating Badge --}}
                    @if($deal->badge_text)
                        <div style="position: absolute; top: 16px; left: 16px; z-index: 3;">
                            <span style="background: linear-gradient(135deg, #7c3aed, #6366f1); color: #ffffff; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 6px; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(124,58,237,0.4); text-transform: uppercase;">
                                {{ $deal->badge_text }}
                            </span>
                        </div>
                    @endif

                    {{-- Top Right Badges --}}
                    <div style="position: absolute; top: 16px; right: 16px; display: flex; flex-direction: column; align-items: flex-end; gap: 0.4rem; z-index: 3;">
                        @if($deal->sub_badge)
                            <span style="background: rgba(15,23,42,0.9); color: #ffffff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                {{ $deal->sub_badge }}
                            </span>
                        @endif

                        @if(!empty($deal->tags))
                            @foreach(array_slice($deal->tags, 0, 3) as $t)
                                <span style="background: rgba(16,185,129,0.85); color: #ffffff; font-size: 0.68rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                                    {{ $t }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    {{-- Bottom Brand Watermark --}}
                    <div style="position: absolute; bottom: 16px; left: 16px; z-index: 3;">
                        <span style="background: rgba(16,185,129,0.9); color: #000000; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em;">
                            TECHHUB VERIFIED
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Product Details & Purchase Controls ── --}}
            <div style="display: flex; flex-direction: column;">

                {{-- Category & Tags Row --}}
                <div style="display: flex; gap: 0.45rem; flex-wrap: wrap; margin-bottom: 0.85rem;">
                    @if(!empty($deal->tags))
                        @foreach($deal->tags as $t)
                            <span style="font-size: 0.78rem; font-weight: 600; color: #0284c7; background: rgba(2,132,199,0.12); border: 1px solid rgba(2,132,199,0.25); padding: 0.2rem 0.6rem; border-radius: 6px;">
                                {{ $t }}
                            </span>
                        @endforeach
                    @endif
                </div>

                {{-- Product Title --}}
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main); line-height: 1.35; margin-bottom: 0.85rem;">
                    {{ $deal->name }}
                </h1>

                {{-- Rating & Social Proof --}}
                <div style="display: flex; align-items: center; gap: 0.85rem; font-size: 0.88rem; color: var(--text-sub); margin-bottom: 1.25rem; flex-wrap: wrap;">
                    <span style="color: #f59e0b; font-weight: 700;">⭐ {{ number_format($deal->rating, 1) }}</span>
                    <span>({{ number_format($deal->rating_count ?: 346) }} đánh giá)</span>
                    <span>•</span>
                    <span style="font-weight: 600; color: var(--accent-emerald);">{{ number_format($deal->sold_count ?: 7700) }} Đã bán</span>
                    <span>•</span>
                    <span style="color: var(--text-muted);">
                        Số lượng: <strong style="color: var(--accent-emerald);">Có sẵn (Giao ngay)</strong>
                    </span>
                </div>

                {{-- Dynamic Price Display --}}
                <div style="margin-bottom: 1.75rem; display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: baseline; gap: 0.35rem;">
                        <span id="deal-current-price" style="font-size: 2.35rem; font-weight: 900; color: #10b981; letter-spacing: -0.03em;">
                            {{ $deal->formatted_price }}
                        </span>
                        <span style="font-size: 1.5rem; color: #10b981; font-weight: 900;">✦</span>
                    </div>

                    @if($deal->original_price)
                        <span id="deal-original-price" style="font-size: 1.15rem; color: var(--text-muted); text-decoration: line-through;">
                            {{ $deal->formatted_original_price }}
                        </span>
                        <span style="background: rgba(239,68,68,0.15); color: #ef4444; font-size: 0.82rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 999px;">
                            TIẾT KIỆM {{ $deal->discount_percentage }}%
                        </span>
                    @endif
                </div>

                {{-- Variant Selector Box (Chọn loại gói) --}}
                @if(!empty($deal->variants) && count($deal->variants) > 0)
                    <div style="margin-bottom: 1.75rem;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">
                            Chọn loại:
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem;">
                            @foreach($deal->variants as $index => $v)
                                <div class="deal-variant-card {{ $index === 0 ? 'active' : '' }}"
                                     onclick="selectVariant(this, {{ $v['price'] }}, '{{ addslashes($v['name']) }}')"
                                     style="background: {{ $index === 0 ? 'rgba(16,185,129,0.08)' : 'var(--bg-card)' }}; border: 2px solid {{ $index === 0 ? '#10b981' : 'var(--border-subtle)' }}; border-radius: var(--radius-md); padding: 0.95rem 1.15rem; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem;">
                                        {{ $v['name'] }}
                                    </div>
                                    <div style="font-size: 1rem; font-weight: 800; color: #10b981;">
                                        {{ number_format($v['price'], 0, ',', '.') }}₫
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 3 Trust Badges (Escrow, Giữ tiền 72h, Giao tức thì) --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 2rem;">
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem 0.6rem; text-align: center;">
                        <div style="font-size: 1.3rem; margin-bottom: 0.25rem;">🛡️</div>
                        <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-main);">Bảo vệ bởi Escrow</div>
                    </div>

                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem 0.6rem; text-align: center;">
                        <div style="font-size: 1.3rem; margin-bottom: 0.25rem;">⏱️</div>
                        <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-main);">Giữ tiền 72h</div>
                    </div>

                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem 0.6rem; text-align: center;">
                        <div style="font-size: 1.3rem; margin-bottom: 0.25rem;">⚡</div>
                        <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-main);">Giao hàng tức thì</div>
                    </div>
                </div>

                {{-- High-Converting CTA Button Row --}}
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a id="btn-zalo-cta" href="{{ $deal->zalo_url }}" target="_blank" rel="noopener"
                       style="flex: 1; min-width: 220px; display: inline-flex; align-items: center; justify-content: center; gap: 0.55rem; background: #0068ff; color: #ffffff; text-decoration: none; padding: 0.9rem 1.5rem; border-radius: var(--radius-md); font-size: 1rem; font-weight: 800; box-shadow: 0 4px 20px rgba(0,104,255,0.4); transition: transform 0.2s, box-shadow 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 25px rgba(0,104,255,0.5)'"
                       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,104,255,0.4)'">
                        <span>💬</span> <span>Mua Ngay Qua Zalo ({{ $deal->zalo_contact ?: '0866655803' }})</span>
                    </a>

                    <a id="btn-telegram-cta" href="{{ $deal->telegram_url }}" target="_blank" rel="noopener"
                       style="flex: 1; min-width: 200px; display: inline-flex; align-items: center; justify-content: center; gap: 0.55rem; background: #229ed9; color: #ffffff; text-decoration: none; padding: 0.9rem 1.5rem; border-radius: var(--radius-md); font-size: 1rem; font-weight: 800; box-shadow: 0 4px 20px rgba(34,158,217,0.4); transition: transform 0.2s, box-shadow 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 25px rgba(34,158,217,0.5)'"
                       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(34,158,217,0.4)'">
                        <span>🚀</span> <span>Chat Telegram</span>
                    </a>
                </div>

                <div style="margin-top: 0.85rem; font-size: 0.8rem; color: var(--text-muted); text-align: center;">
                    💡 Bấm nút sẽ tự động kết nối Zalo / Telegram để gửi thông tin kích hoạt nhanh trong 5 phút.
                </div>

            </div>
        </div>

        {{-- Product Description & Guides Section --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.25rem; margin-bottom: 3.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99,102,241,0.1); color: var(--accent-indigo); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    📖
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin: 0;">
                    Thông Tin Chi Tiết &amp; Hướng Dẫn Kích Hoạt
                </h2>
            </div>

            <div class="game-guide-body" style="font-size: 0.95rem; line-height: 1.8; color: var(--text-sub);">
                @if($deal->description_markdown)
                    {!! \Illuminate\Support\Str::markdown($deal->description_markdown) !!}
                @else
                    <p>{{ $deal->summary }}</p>
                @endif
            </div>
        </div>

        {{-- Related Deals --}}
        @if(isset($relatedDeals) && $relatedDeals->isNotEmpty())
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🔥</span> <span>Có Thể Bạn Cũng Quan Tâm</span>
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    @foreach($relatedDeals as $rel)
                        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; gap: 1rem; align-items: center; transition: all 0.2s;"
                             onmouseover="this.style.borderColor='var(--accent-indigo)';this.style.transform='translateY(-2px)'"
                             onmouseout="this.style.borderColor='var(--border-subtle)';this.style.transform='translateY(0)'">
                            <img src="{{ $rel->thumbnail_url ?: '/images/deals/gemini-pro-5tb.png' }}" alt="{{ $rel->name }}"
                                 style="width: 70px; height: 70px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border-subtle); flex-shrink: 0;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-main); line-height: 1.35; margin-bottom: 0.35rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <a href="{{ route('deals.show', $rel->slug) }}" style="color: inherit; text-decoration: none;">
                                        {{ $rel->name }}
                                    </a>
                                </div>
                                <div style="font-size: 0.95rem; font-weight: 800; color: #10b981;">
                                    {{ $rel->formatted_price }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

<script>
let selectedVariantName = '{{ $deal->variants[0]["name"] ?? $deal->name }}';
let selectedVariantPrice = {{ $deal->variants[0]["price"] ?? $deal->price }};

function selectVariant(element, price, name) {
    // Reset all variant cards
    document.querySelectorAll('.deal-variant-card').forEach(card => {
        card.style.background = 'var(--bg-card)';
        card.style.borderColor = 'var(--border-subtle)';
        card.classList.remove('active');
    });

    // Highlight selected card
    element.style.background = 'rgba(16,185,129,0.08)';
    element.style.borderColor = '#10b981';
    element.classList.add('active');

    // Update dynamic price display
    const priceFormatted = new Intl.NumberFormat('vi-VN').format(price) + '₫';
    document.getElementById('deal-current-price').textContent = priceFormatted;

    selectedVariantName = name;
    selectedVariantPrice = price;

    // Update Zalo & Telegram message
    updateCtaLinks();
}

function updateCtaLinks() {
    const productName = '{{ addslashes($deal->name) }}';
    const message = encodeURIComponent(`Chào shop, tôi muốn mua gói [${selectedVariantName}] (${new Intl.NumberFormat('vi-VN').format(selectedVariantPrice)}đ) trên TechHub. Tư vấn giúp tôi với!`);

    const phone = '{{ preg_replace("/[^0-9]/", "", (string)($deal->zalo_contact ?: "0866655803")) }}';
    const zaloBtn = document.getElementById('btn-zalo-cta');
    if (zaloBtn) {
        zaloBtn.href = `https://zalo.me/${phone}`;
    }

    const teleBtn = document.getElementById('btn-telegram-cta');
    if (teleBtn) {
        teleBtn.href = '{{ $deal->telegram_url }}';
    }
}
</script>
@endsection
