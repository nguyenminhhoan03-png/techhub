@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' - TechHub')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('head_extra')
@if($article->schema_markup)
<script type="application/ld+json">
{!! json_encode($article->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ addslashes($article->title) }}",
  "description": "{{ addslashes($article->excerpt) }}",
  "image": "{{ $article->featured_image_url ?: url('/images/og-default.jpg') }}",
  "datePublished": "{{ $article->published_at ? $article->published_at->toIso8601String() : now()->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $article->author?->name ?? 'TechHub Editorial' }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "TechHub",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ url('/images/logo.png') }}"
    }
  }
}
</script>
@endsection

@section('content')

{{-- Breadcrumbs Navigation --}}
<div style="background: var(--bg-surface); border-bottom: 1px solid var(--border-subtle); padding: 0.85rem 0;">
    <div class="container">
        <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted); flex-wrap: wrap;">
            <a href="{{ url('/') }}" style="color: var(--text-sub); text-decoration: none;">{{ __('home') }}</a>
            <span>›</span>
            <a href="{{ route('articles.index') }}" style="color: var(--text-sub); text-decoration: none;">{{ __('articles') }}</a>
            <span>›</span>
            @if($article->category)
                <a href="{{ route('articles.index', ['category' => $article->category->slug]) }}" style="color: var(--text-sub); text-decoration: none;">{{ $article->category->display_name }}</a>
                <span>›</span>
            @endif
            <span style="color: var(--text-main); font-weight: 600;">{{ Str::limit($article->title, 45) }}</span>
        </nav>
    </div>
</div>

<article class="container" style="padding: 3rem 0 5rem;">
    
    {{-- Article Header --}}
    <header style="max-width: 860px; margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1rem; flex-wrap: wrap;">
            <span class="badge {{ $article->type->badgeClass() }}">
                {{ $article->type->label() }}
            </span>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
                {{ $article->published_at ? $article->published_at->format('d/m/Y') : date('d/m/Y') }}
            </span>
            <span style="color: var(--text-muted);">•</span>
            <span style="font-size: 0.85rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.25rem;">
                <x-heroicon-o-clock style="width: 1.1em; height: 1.1em;" />
                {{ __('read_time', ['count' => $article->read_time_minutes]) }}
            </span>
            <span style="color: var(--text-muted);">•</span>
            <span style="font-size: 0.85rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.25rem;">
                <x-heroicon-o-eye style="width: 1.1em; height: 1.1em;" />
                {{ number_format($article->view_count) }} lượt xem
            </span>
        </div>

        <h1 style="font-size: 2.35rem; line-height: 1.25; margin-bottom: 1.25rem; color: var(--text-main);">
            {{ $article->title }}
        </h1>

        <p style="font-size: 1.15rem; line-height: 1.6; color: var(--text-sub); background: var(--bg-surface-elevated); padding: 1.25rem 1.5rem; border-left: 4px solid var(--accent-indigo); border-radius: var(--radius-sm);">
            {{ $article->excerpt }}
        </p>
    </header>

    {{-- Main Reader Body Grid (Content + Sticky TOC) --}}
    <div style="display: grid; grid-template-columns: 1fr 280px; gap: 3rem; align-items: start;">
        
        {{-- Left: Article Content --}}
        <div style="min-width: 0;">

            {{-- Featured Image if available --}}
            @if($article->featured_image_url)
                <div style="margin-bottom: 2.5rem; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-card); max-height: 440px;">
                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endif

            {{-- Visual Hardware Benchmark Comparison Section (For Comparison Articles) --}}
            @if($article->type->value === 'comparison' && count($linkedProducts) >= 2)
                <div class="rich-output-card" style="margin-bottom: 2.5rem; border: 1px solid var(--border-medium); background: #ffffff; padding: 1.75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                        <h3 style="font-size: 1.2rem; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
                            <x-heroicon-o-scale style="width: 1.2em; height: 1.2em; color: var(--accent-indigo);" />
                            So Sánh Điểm Số Hiệu Năng (Benchmark Breakdown)
                        </h3>
                        <span class="badge badge-emerald">Dữ liệu chuẩn 2026</span>
                    </div>

                    {{-- Product Cards Head-to-Head --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        @foreach($linkedProducts as $prod)
                            <div style="background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); text-align: center;">
                                <strong style="font-size: 1.1rem; color: var(--text-main); display: block; margin-bottom: 0.35rem;">{{ $prod->full_name }}</strong>
                                <span style="font-size: 0.82rem; color: var(--text-muted); display: block; margin-bottom: 0.75rem;">{{ $prod->brand?->name }} • MSRP ${{ $prod->launch_msrp_usd }}</span>
                                <div style="font-size: 2rem; font-weight: 800; color: var(--accent-indigo); font-family: var(--font-mono);">
                                    {{ $prod->overall_score }}/10
                                </div>
                                <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Điểm Tổng Thể</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Comparison Metric Bars --}}
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; font-weight: 600; margin-bottom: 0.35rem;">
                                <span>🎮 Hiệu Năng Chơi Game (Gaming 1440p/4K)</span>
                                <span>{{ $linkedProducts[0]->gaming_score }} vs {{ $linkedProducts[1]->gaming_score }}</span>
                            </div>
                            <div style="height: 10px; background: #e2e8f0; border-radius: var(--radius-full); overflow: hidden; display: flex;">
                                <div style="width: {{ $linkedProducts[0]->gaming_score * 10 }}%; background: var(--accent-indigo);"></div>
                                <div style="width: {{ (10 - $linkedProducts[0]->gaming_score) * 10 }}%; background: transparent;"></div>
                            </div>
                        </div>

                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; font-weight: 600; margin-bottom: 0.35rem;">
                                <span>⚡ Đồ Họa &amp; Năng Suất (Render / AI)</span>
                                <span>{{ $linkedProducts[0]->productivity_score }} vs {{ $linkedProducts[1]->productivity_score }}</span>
                            </div>
                            <div style="height: 10px; background: #e2e8f0; border-radius: var(--radius-full); overflow: hidden; display: flex;">
                                <div style="width: {{ $linkedProducts[0]->productivity_score * 10 }}%; background: var(--accent-cyan);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Article Text Body --}}
            <div class="article-prose" style="font-size: 1.05rem; line-height: 1.8; color: var(--text-main);">
                {!! nl2br(e($article->content_markdown)) !!}
            </div>

            {{-- Affiliate Action Box --}}
            <div style="margin-top: 3rem; background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(2, 132, 199, 0.08) 100%); border: 1px solid rgba(79, 70, 229, 0.2); border-radius: var(--radius-lg); padding: 2rem; text-align: center;">
                <h3 style="font-size: 1.35rem; margin-bottom: 0.5rem; color: var(--text-main);">🛒 Tham Khảo Giá &amp; Khuyến Mãi Tốt Nhất</h3>
                <p style="color: var(--text-sub); font-size: 0.95rem; margin-bottom: 1.5rem; max-width: 550px; margin-left: auto; margin-right: auto;">
                    So sánh bảng giá chính hãng tại các đại lý uy tín để nhận được ưu đãi giảm giá và chính sách bảo hành tốt nhất.
                </p>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="https://shopee.vn" target="_blank" rel="nofollow noopener" class="btn btn-primary btn-sm" style="padding: 0.65rem 1.5rem;">
                        Xem Giá Trên Shopee ↗
                    </a>
                    <a href="https://lazada.vn" target="_blank" rel="nofollow noopener" class="btn btn-secondary btn-sm" style="padding: 0.65rem 1.5rem;">
                        Xem Giá Trên Lazada ↗
                    </a>
                </div>
            </div>

            {{-- FAQ Accordion if Schema Markup has FAQs --}}
            @if(!empty($article->schema_markup['mainEntity']))
                <div style="margin-top: 3.5rem;">
                    <h3 style="font-size: 1.4rem; margin-bottom: 1.25rem; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
                        <x-heroicon-o-question-mark-circle style="width: 1.2em; height: 1.2em; color: var(--accent-indigo);" />
                        Câu Hỏi Thường Gặp (FAQ)
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        @foreach($article->schema_markup['mainEntity'] as $faq)
                            <details style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1rem 1.25rem; cursor: pointer;">
                                <summary style="font-weight: 700; color: var(--text-main); font-size: 1rem; outline: none;">
                                    {{ $faq['name'] }}
                                </summary>
                                <p style="margin-top: 0.75rem; color: var(--text-sub); font-size: 0.92rem; line-height: 1.6; border-top: 1px solid var(--border-subtle); padding-top: 0.75rem;">
                                    {{ $faq['acceptedAnswer']['text'] }}
                                </p>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: Sticky Sidebar with Table of Contents (TOC) --}}
        <aside style="position: sticky; top: 90px;">
            
            {{-- TOC Widget --}}
            @if(count($toc) > 0)
                <div class="tool-card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <strong style="font-size: 0.88rem; text-transform: uppercase; color: var(--accent-indigo); display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.85rem; letter-spacing: 0.05em;">
                        <x-heroicon-o-list-bullet style="width: 1.2em; height: 1.2em;" />
                        Mục Lục Bài Viết
                    </strong>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem;">
                        @foreach($toc as $item)
                            <li style="padding-left: {{ ($item['level'] - 2) * 0.75 }}rem;">
                                <a href="#{{ $item['slug'] }}" style="color: var(--text-sub); text-decoration: none; display: block; line-height: 1.4; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-indigo)'" onmouseout="this.style.color='var(--text-sub)'">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Author Card --}}
            <div class="tool-card" style="padding: 1.25rem; text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--gradient-brand); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 0.75rem;">
                    {{ substr($article->author?->name ?? 'T', 0, 1) }}
                </div>
                <strong style="color: var(--text-main); display: block;">{{ $article->author?->name ?? 'TechHub Editorial' }}</strong>
                <span style="font-size: 0.78rem; color: var(--text-muted); display: block; margin-top: 0.2rem;">Chuyên Gia Đánh Giá Phần Cứng</span>
            </div>

        </aside>

    </div>

</article>

@endsection
