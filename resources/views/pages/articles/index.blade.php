@extends('layouts.app')

{{-- Fix: dùng 'meta_title' đúng với @yield trong layout --}}
@section('meta_title', 'Bài Viết Công Nghệ, So Sánh Phần Cứng & Hướng Dẫn Kỹ Thuật | TechHub')
@section('meta_description', 'Khám phá các bài viết công nghệ chuyên sâu, so sánh CPU, GPU, điện thoại và laptop. Dữ liệu benchmark thực tế và tư vấn mua sắm từ đội ngũ TechHub.')
@section('canonical_url', url('/articles'))
@section('meta_keywords', 'bài viết công nghệ, so sánh phần cứng, đánh giá laptop, benchmark CPU GPU, hướng dẫn mua sắm, TechHub')
@section('og_type', 'website')

@push('head')
{{-- CollectionPage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Bài Viết Công Nghệ — TechHub",
  "description": "Kho bài viết công nghệ, so sánh phần cứng và hướng dẫn kỹ thuật chuyên sâu.",
  "url": "{{ url('/articles') }}",
  "publisher": {
    "@type": "Organization",
    "name": "TechHub",
    "url": "{{ url('/') }}"
  }
}
</script>
{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Trang Chủ", "item": "{{ url('/') }}" },
    { "@type": "ListItem", "position": 2, "name": "Bài Viết", "item": "{{ url('/articles') }}" }
  ]
}
</script>
@endpush

@section('content')

{{-- Hero Header --}}
<section style="padding: 3rem 0 2rem; text-align: center; position: relative;">
    <div class="container" style="max-width: 800px;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(79, 70, 229, 0.08); border: 1px solid rgba(79, 70, 229, 0.2); padding: 0.35rem 0.9rem; border-radius: var(--radius-full); font-size: 0.85rem; color: var(--accent-indigo); font-weight: 600; margin-bottom: 1.25rem;">
            <x-heroicon-o-scale style="width: 1.2em; height: 1.2em;" />
            <span>Trung Tâm So Sánh &amp; Tư Vấn Công Nghệ</span>
        </div>
        <h1 style="font-size: clamp(1.6rem, 5vw, 2.75rem); line-height: 1.2; margin-bottom: 1rem;">
            So Sánh <span class="gradient-text">Phần Cứng &amp; Thiết Bị</span> Thông Minh
        </h1>
        <p style="font-size: clamp(0.95rem, 2.5vw, 1.15rem); color: var(--text-sub); line-height: 1.6;">
            Tra cứu thông số kỹ thuật, so sánh điểm benchmark đối đầu và tìm ra sản phẩm phù hợp nhất với ngân sách của bạn.
        </p>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('articles.index') }}" style="max-width: 580px; margin: 1.75rem auto 0; position: relative; display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <div style="position: relative; flex: 1 1 220px;">
                <input type="text" name="search" class="form-control" style="padding-left: 2.8rem; height: 48px; font-size: 0.95rem; border-radius: var(--radius-full);" placeholder="{{ __('search_articles_placeholder') }}" value="{{ request('search') }}">
                <x-heroicon-s-magnifying-glass style="position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); width: 1.3em; height: 1.3em; color: var(--text-muted);" />
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-full); padding: 0 1.5rem; height: 48px;">{{ __('search') }}</button>
        </form>
    </div>
</section>

{{-- Type Filter Tabs --}}
<section style="padding: 1rem 0 2.5rem;">
    <div class="container">
        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem;">
            <a href="{{ route('articles.index') }}" class="cat-tab {{ empty($currentType) ? 'active' : '' }}">
                <x-heroicon-o-squares-2x2 style="width: 1.2em; height: 1.2em;" />
                <span>{{ __('all_articles') }}</span>
            </a>
            <a href="{{ route('articles.index', ['type' => 'comparison']) }}" class="cat-tab {{ $currentType === 'comparison' ? 'active' : '' }}">
                <x-heroicon-o-scale style="width: 1.2em; height: 1.2em;" />
                <span>{{ __('type_comparison') }}</span>
            </a>
            <a href="{{ route('articles.index', ['type' => 'review']) }}" class="cat-tab {{ $currentType === 'review' ? 'active' : '' }}">
                <x-heroicon-o-star style="width: 1.2em; height: 1.2em;" />
                <span>{{ __('type_review') }}</span>
            </a>
            <a href="{{ route('articles.index', ['type' => 'buying_guide']) }}" class="cat-tab {{ $currentType === 'buying_guide' ? 'active' : '' }}">
                <x-heroicon-o-shopping-bag style="width: 1.2em; height: 1.2em;" />
                <span>{{ __('type_buying_guide') }}</span>
            </a>
            <a href="{{ route('articles.index', ['type' => 'news']) }}" class="cat-tab {{ $currentType === 'news' ? 'active' : '' }}">
                <x-heroicon-o-newspaper style="width: 1.2em; height: 1.2em;" />
                <span>{{ __('type_news') }}</span>
            </a>
        </div>

        {{-- Articles Grid --}}
        @if($articles->isEmpty())
            <div class="tool-card" style="text-align: center; padding: 4rem 2rem; max-width: 600px; margin: 0 auto;">
                <h3>{{ __('no_articles_found') }}</h3>
                <p style="margin-top: 0.5rem;">{{ __('no_articles_found_desc') }}</p>
                <a href="{{ route('articles.index') }}" class="btn btn-primary btn-sm" style="margin-top: 1.5rem;">{{ __('all_articles') }}</a>
            </div>
        @else
            <div class="grid-cards">
                @foreach($articles as $art)
                    <article class="tool-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                        @if($art->featured_image_url)
                            <a href="{{ route('articles.show', $art->slug) }}" style="display: block; height: 200px; overflow: hidden; position: relative;">
                                <img src="{{ $art->featured_image_url }}" alt="{{ $art->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                <span class="badge {{ $art->type->badgeClass() }}" style="position: absolute; top: 1rem; left: 1rem; backdrop-filter: blur(8px);">
                                    {{ $art->type->label() }}
                                </span>
                            </a>
                        @else
                            <div style="height: 140px; background: var(--gradient-brand); display: flex; align-items: center; justify-content: center; position: relative;">
                                <x-heroicon-o-scale style="width: 3.5em; height: 3.5em; color: rgba(255,255,255,0.4);" />
                                <span class="badge {{ $art->type->badgeClass() }}" style="position: absolute; top: 1rem; left: 1rem;">
                                    {{ $art->type->label() }}
                                </span>
                            </div>
                        @endif

                        <div style="padding: 1.5rem; display: flex; flex-direction: column; flex: 1;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.65rem;">
                                <span>{{ $art->category?->display_name ?? 'Tech' }}</span>
                                <span>•</span>
                                <span>{{ __('read_time', ['count' => $art->read_time_minutes]) }}</span>
                                <span>•</span>
                                <span>{{ $art->published_at ? $art->published_at->format('d/m/Y') : '' }}</span>
                            </div>

                            <h3 style="font-size: 1.15rem; line-height: 1.4; margin-bottom: 0.75rem;">
                                <a href="{{ route('articles.show', $art->slug) }}" style="color: var(--text-main); text-decoration: none;">
                                    {{ $art->title }}
                                </a>
                            </h3>

                            @if($art->excerpt)
                                <p style="font-size: 0.9rem; color: var(--text-sub); line-height: 1.6; margin-bottom: 1.25rem; flex: 1;">
                                    {{ Str::limit($art->excerpt, 120) }}
                                </p>
                            @endif

                            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.82rem; color: var(--text-muted);">{{ number_format($art->view_count) }} views</span>
                                <a href="{{ route('articles.show', $art->slug) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem; font-size: 0.82rem;">
                                    {{ __('read_article') }}
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $articles->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
