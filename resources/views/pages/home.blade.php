@php
    use Application\Setting\Services\SettingService;

    $isEn = app()->getLocale() === 'en';
    $heroTitle = $isEn
        ? SettingService::get('hero_title_en', __('hero_title_1'))
        : SettingService::get('hero_title', __('hero_title_1'));
    $heroSubtitle = $isEn
        ? SettingService::get('hero_subtitle_en', __('hero_subtitle'))
        : SettingService::get('hero_subtitle', __('hero_subtitle'));
@endphp
@extends('layouts.app')

@section('meta_title', $heroTitle . ' | TechHub')
@section('meta_description', $heroSubtitle)
@section('canonical_url', app()->getLocale() === 'en' ? url('/') . '?lang=en' : url('/'))
@section('meta_keywords', __('tools.common.home_meta_keywords'))
@section('og_image', asset('images/techhub-og.png'))

@push('schema')
{{-- Organization Schema (E-E-A-T signal) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "TechHub",
  "url": "{{ url('/') }}",
  "logo": {
    "@type": "ImageObject",
    "url": "{{ url('/images/logo.png') }}"
  },
  "description": "{{ $heroSubtitle }}",
  "sameAs": []
}
</script>
{{-- WebPage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "{{ $heroTitle }} — TechHub",
  "description": "{{ $heroSubtitle }}",
  "url": "{{ url('/') }}",
  "inLanguage": "{{ app()->getLocale() === 'en' ? 'en-US' : 'vi-VN' }}",
  "isPartOf": { "@type": "WebSite", "name": "TechHub", "url": "{{ url('/') }}" }
}
</script>
{{-- FAQPage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "url": "{{ url('/') }}",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "{{ __('faq_q1') }}",
      "acceptedAnswer": { "@type": "Answer", "text": "{{ __('faq_a1') }}" }
    },
    {
      "@type": "Question",
      "name": "{{ __('faq_q2') }}",
      "acceptedAnswer": { "@type": "Answer", "text": "{{ __('faq_a2') }}" }
    },
    {
      "@type": "Question",
      "name": "{{ __('faq_q3') }}",
      "acceptedAnswer": { "@type": "Answer", "text": "{{ __('faq_a3') }}" }
    }
  ]
}
</script>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="hero">
    {{-- Full High-Tech Background Mesh, Geometric Grid & Ambient Glow Orbs --}}
    <div class="hero-bg-mesh" aria-hidden="true">
        <div class="hero-grid-pattern"></div>
        <div class="hero-radial-glow"></div>
        <div class="hero-mesh-orb hero-mesh-orb-1"></div>
        <div class="hero-mesh-orb hero-mesh-orb-2"></div>
        <div class="hero-mesh-orb hero-mesh-orb-3"></div>
    </div>

    <div class="container" style="position: relative; z-index: 2;">
        
        {{-- Animated Badge --}}
        <div class="hero-pill">
            <span>{{ __('hero_badge') }}</span>
        </div>

        {{-- Main Headline --}}
        <h1 class="hero-title">
            {{ $heroTitle }} <br>
            <span class="gradient-text">{{ __('hero_title_2') }}</span>
        </h1>

        {{-- Subtitle --}}
        <p class="hero-subtitle">
            {{ $heroSubtitle }}
        </p>

        {{-- Search Box with Live Filter --}}
        <div class="hero-search-wrap">
            <span class="hero-search-icon" style="display: inline-flex; align-items: center; justify-content: center;"><x-heroicon-o-magnifying-glass style="width: 1.2em; height: 1.2em;" /></span>
            <input type="text" 
                   id="global-search-input" 
                   class="hero-search-input" 
                   placeholder="{{ __('search_placeholder') }}" 
                   autocomplete="off">
            <span class="hero-search-badge">Ctrl + K</span>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-number gradient-text">{{ $tools->count() }}+</span>
                <span class="stat-label">{{ __('all_tools') }}</span>
            </div>
            <div class="stat-box">
                <span class="stat-number" style="color: var(--accent-emerald);">&lt; 5ms</span>
                <span class="stat-label">{{ __('execution_time') }}</span>
            </div>
            <div class="stat-box">
                <span class="stat-number" style="color: var(--accent-cyan);">100%</span>
                <span class="stat-label">Zero Retention</span>
            </div>
            <div class="stat-box">
                <span class="stat-number" style="color: var(--accent-violet);">REST API</span>
                <span class="stat-label">Ready</span>
            </div>
        </div>

    </div>
</section>

{{-- Category Segment Filter Bar with Swiper Carousel --}}
<section class="categories-bar">
    <div class="container" style="position: relative; display: flex; align-items: center; gap: 0.65rem;">
        
        {{-- Swiper Prev Button --}}
        <button type="button" class="swiper-cat-btn swiper-cat-prev" aria-label="Previous categories">
            ‹
        </button>

        {{-- Swiper Container --}}
        <div class="swiper swiper-categories">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a href="{{ url('/tools') }}" data-filter-category="" class="cat-tab {{ !request('category') ? 'active' : '' }}">
                        <span style="display: flex; align-items: center;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em;" /></span> <span>{{ __('all_tools') }} ({{ $tools->count() }})</span>
                    </a>
                </div>
                @foreach($categories as $category)
                    <div class="swiper-slide">
                        <a href="{{ url('/tools?category=' . $category->slug) }}" 
                           data-filter-category="{{ $category->slug }}"
                           class="cat-tab {{ request('category') === $category->slug ? 'active' : '' }}">
                            <span style="display: flex; align-items: center;">
                                @if($category->slug === 'developer') <x-heroicon-o-code-bracket style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'calculators') <x-heroicon-o-calculator style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'image') <x-heroicon-o-photo style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'pdf') <x-heroicon-o-document-text style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'text') <x-heroicon-o-pencil-square style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'color') <x-heroicon-o-swatch style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'ai') <x-heroicon-o-cpu-chip style="width: 1.2em; height: 1.2em;" />
                                @elseif($category->slug === 'seo') <x-heroicon-o-globe-alt style="width: 1.2em; height: 1.2em;" />
                                @else <x-heroicon-s-bolt style="width: 1.2em; height: 1.2em;" />
                                @endif
                            </span>
                            <span>{{ $category->display_name }} ({{ $category->tools_count ?? $category->tools->count() }})</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Swiper Next Button --}}
        <button type="button" class="swiper-cat-btn swiper-cat-next" aria-label="Next categories">
            ›
        </button>

    </div>
</section>

{{-- Featured & Trending Tools Grid --}}
<section style="padding: 4.5rem 0;">
    <div class="container">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2>{{ __('popular_tools') }}</h2>
                <p style="margin-top: 0.35rem;">{{ __('popular_tools_subtitle') }}</p>
            </div>
            <a href="{{ url('/tools') }}" class="btn btn-secondary btn-sm">
                {{ __('view_all_tools', ['count' => $tools->count()]) }}
            </a>
        </div>

        <div class="grid-cards">
            @foreach($tools as $tool)
                <div class="tool-card" 
                     data-tool-name="{{ $tool->display_name }}" 
                     data-tool-summary="{{ $tool->display_summary }}" 
                     data-tool-category="{{ $tool->category?->slug ?? '' }}">
                    
                    <div class="card-top">
                        <div class="tool-icon-wrap 
                            @if($tool->category?->slug === 'developer') tool-icon-dev
                            @elseif($tool->category?->slug === 'calculators') tool-icon-calc
                            @elseif($tool->category?->slug === 'image') tool-icon-img
                            @elseif($tool->category?->slug === 'seo') tool-icon-seo
                            @else tool-icon-dev
                            @endif">
                            @if($tool->category?->slug === 'developer') <x-heroicon-o-code-bracket style="width: 1.2em; height: 1.2em;" />
                            @elseif($tool->category?->slug === 'calculators') <x-heroicon-o-calculator style="width: 1.2em; height: 1.2em;" />
                            @elseif($tool->category?->slug === 'image') <x-heroicon-o-photo style="width: 1.2em; height: 1.2em;" />
                            @elseif($tool->category?->slug === 'seo') <x-heroicon-o-globe-alt style="width: 1.2em; height: 1.2em;" />
                            @else <x-heroicon-s-bolt style="width: 1.2em; height: 1.2em;" />
                            @endif
                        </div>
                        <span class="badge">{{ $tool->category?->display_name ?? 'Utility' }}</span>
                    </div>

                    <h3 class="card-title">{{ $tool->display_name }}</h3>
                    <p class="card-desc">{{ $tool->display_summary }}</p>

                    <div class="card-footer">
                        <span style="display: flex; align-items: center; gap: 0.35rem;">
                            <span style="color: var(--accent-amber);">★</span>
                            <strong style="color: var(--text-main);">{{ number_format((float)$tool->rating_avg, 2) }}</strong>
                            <span style="color: var(--text-muted);">({{ $tool->rating_count }})</span>
                        </span>
                        <a href="{{ url('/tools/' . $tool->slug) }}" class="btn btn-primary btn-sm">
                            {{ __('open_tool') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- Why Choose Us Section --}}
<section style="padding: 4.5rem 0; background: var(--bg-surface-elevated); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
    <div class="container">
        
        <div style="text-align: center; max-width: 720px; margin: 0 auto 3.5rem;">
            <h2>{{ __('why_choose_us') }}</h2>
            <p style="margin-top: 0.5rem;">{{ __('why_choose_subtitle') }}</p>
        </div>

        <div class="grid-cards" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="tool-card">
                <div class="tool-icon-wrap tool-icon-dev" style="margin-bottom: 1rem; display: inline-flex; align-items: center; justify-content: center;"><x-heroicon-o-shield-check style="width: 1.5em; height: 1.5em;" /></div>
                <h3 class="card-title">{{ __('zero_retention_title') }}</h3>
                <p class="card-desc">{{ __('zero_retention_desc') }}</p>
            </div>

            <div class="tool-card">
                <div class="tool-icon-wrap tool-icon-calc" style="margin-bottom: 1rem; display: inline-flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 1.5em; height: 1.5em;" /></div>
                <h3 class="card-title">{{ __('speed_title') }}</h3>
                <p class="card-desc">{{ __('speed_desc') }}</p>
            </div>

            <div class="tool-card">
                <div class="tool-icon-wrap tool-icon-img" style="margin-bottom: 1rem; display: inline-flex; align-items: center; justify-content: center;"><x-heroicon-o-globe-alt style="width: 1.5em; height: 1.5em;" /></div>
                <h3 class="card-title">{{ __('api_ready_title') }}</h3>
                <p class="card-desc">{{ __('api_ready_desc') }}</p>
            </div>
        </div>

    </div>
</section>

{{-- FAQ Section --}}
<section style="padding: 4.5rem 0;">
    <div class="container" style="max-width: 880px;">
        
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>{{ __('faq_title') }}</h2>
            <p style="margin-top: 0.5rem;">{{ __('faq_subtitle') }}</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div class="tool-card" style="padding: 1.6rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 0.6rem; color: var(--text-main);">{{ __('faq_q1') }}</h3>
                <p style="font-size: 0.95rem; line-height: 1.6;">{{ __('faq_a1') }}</p>
            </div>

            <div class="tool-card" style="padding: 1.6rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 0.6rem; color: var(--text-main);">{{ __('faq_q2') }}</h3>
                <p style="font-size: 0.95rem; line-height: 1.6;">{{ __('faq_a2') }}</p>
            </div>

            <div class="tool-card" style="padding: 1.6rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 0.6rem; color: var(--text-main);">{{ __('faq_q3') }}</h3>
                <p style="font-size: 0.95rem; line-height: 1.6;">{{ __('faq_a3') }}</p>
            </div>
        </div>

    </div>
</section>

@endsection
