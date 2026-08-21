@extends('layouts.app')

@section('meta_title', __('all_tools') . ' — TechHub')
@section('meta_description', __('hero_subtitle'))

@section('content')
<section style="padding: 3.5rem 0;">
    <div class="container">
        
        {{-- Breadcrumb --}}
        @php
            $currentCategoryModel = request('category') ? $categories->firstWhere('slug', request('category')) : null;
        @endphp
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Trang Chủ</a>
            <span>/</span>
            <a href="{{ url('/tools') }}" style="color: inherit; text-decoration: none;">{{ __('tools_hub') }}</a>
            @if($currentCategoryModel)
                <span>/</span>
                <span style="color: var(--text-main); font-weight: 600;">{{ $currentCategoryModel->name }}</span>
            @elseif(request('category'))
                <span>/</span>
                <span style="color: var(--text-main); font-weight: 600;">{{ request('category') }}</span>
            @endif
        </div>

        <div style="margin-bottom: 2.5rem;">
            <h1>{{ __('all_tools') }} <span class="gradient-text">&amp; Tiện Ích Trực Tuyến</span></h1>
            <p style="margin-top: 0.5rem; font-size: 1.05rem;">
                {{ __('popular_tools_subtitle') }}
            </p>
        </div>

        {{-- Categories Filter Tabs with Swiper Carousel --}}
        <div style="position: relative; display: flex; align-items: center; gap: 0.65rem; margin-bottom: 2.5rem;">
            <button type="button" class="swiper-cat-btn swiper-cat-prev" aria-label="Previous categories">‹</button>
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
                                <span>{{ $category->name }} ({{ $category->tools_count ?? $category->tools->count() }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="swiper-cat-btn swiper-cat-next" aria-label="Next categories">›</button>
        </div>

        {{-- Tools Grid --}}
        @if($tools->isEmpty())
            <div class="tool-card" style="text-align: center; padding: 4rem 2rem;">
                <h3>Không tìm thấy công cụ phù hợp</h3>
                <p style="margin-top: 0.5rem;">Vui lòng thử lại với từ khóa khác hoặc xóa bộ lọc.</p>
                <a href="{{ url('/tools') }}" class="btn btn-primary btn-sm" style="margin-top: 1.5rem;">
                    {{ __('all_tools') }}
                </a>
            </div>
        @else
            <div class="grid-cards">
                @foreach($tools as $tool)
                    <div class="tool-card"
                         data-tool-name="{{ $tool->name }}" 
                         data-tool-summary="{{ $tool->summary }}" 
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
                            <span class="badge">{{ $tool->category?->name ?? 'Utility' }}</span>
                        </div>

                        <h3 class="card-title">{{ $tool->name }}</h3>
                        <p class="card-desc">{{ $tool->summary }}</p>

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
        @endif

    </div>
</section>
@endsection
