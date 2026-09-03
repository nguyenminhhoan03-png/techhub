@php
    use Application\Setting\Services\SettingService;
    use Application\Ad\Services\AdService;

    $footerAd = AdService::getAdForSlot('footer_banner');
    $gaMeasurementId = SettingService::get('google_analytics_id', config('services.google.analytics_id', env('GOOGLE_ANALYTICS_ID', 'G-7TJK356QR4')));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(!empty($gaMeasurementId))
        {{-- Google tag (gtag.js) - Realtime Traffic Analytics --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaMeasurementId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaMeasurementId }}');
        </script>
    @endif

    {{-- Favicon & App Icons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#2563eb">

    {{-- Dynamic SEO Meta Tags --}}
    <title>@yield('meta_title', __('hero_title_1') . ' — TechHub')</title>
    <meta name="description" content="@yield('meta_description', __('hero_subtitle'))">
    <meta name="keywords" content="@yield('meta_keywords', 'công cụ lập trình online, json formatter online, jwt debugger, regex tester, tính lãi suất vay, tính bmi online, base64 encode decode, hash generator, tạo sitemap xml, schema markup generator, developer utilities, online tools free, TechHub')">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    
    {{-- Hreflang Multi-Language Alternates --}}
    <link rel="alternate" hreflang="vi" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    
    {{-- OpenGraph & Social Cards --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="TechHub">
    <meta property="og:locale" content="{{ app()->getLocale() === 'vi' ? 'vi_VN' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() === 'vi' ? 'en_US' : 'vi_VN' }}">
    <meta property="og:title" content="@yield('meta_title', __('hero_title_1') . ' — TechHub')">
    <meta property="og:description" content="@yield('meta_description', __('hero_subtitle'))">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/techhub-og.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('meta_title', 'TechHub')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@TechHub">
    <meta name="twitter:title" content="@yield('meta_title', __('hero_title_1') . ' — TechHub')">
    <meta name="twitter:description" content="@yield('meta_description', __('hero_subtitle'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/techhub-og.png'))">
    
    {{-- Typography & Google Fonts (Non-blocking) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Outfit:wght@500;600;700;800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    </noscript>
    
    {{-- Swiper.js Carousel CSS (Non-blocking) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    </noscript>

    {{-- Custom Design System Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/techhub.css') }}?v={{ @filemtime(public_path('css/techhub.css')) ?: '2.0.1' }}">

    {{-- JSON-LD Structured Data Schema --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "TechHub",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/tools') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    {{-- Client-Side Multi-Language Bridge --}}
    @php
        $i18nData = [
            'processing' => __('processing'),
            'copied' => __('copied'),
            'copy_result' => __('copy_result'),
            'copy_failed' => app()->getLocale() === 'en' ? 'Copy failed' : 'Sao chép thất bại',
            'copy_empty' => app()->getLocale() === 'en' ? 'No content to copy!' : 'Không có nội dung để sao chép!',
            'copied_clipboard' => app()->getLocale() === 'en' ? 'Copied to clipboard!' : 'Đã sao chép vào bộ nhớ tạm (Clipboard)!',
            'image_invalid' => app()->getLocale() === 'en' ? 'Please select a valid image file (PNG, JPG, WEBP, GIF)' : 'Vui lòng chọn tệp hình ảnh hợp lệ (PNG, JPG, WEBP, GIF)',
            'image_loaded' => app()->getLocale() === 'en' ? 'Loaded image: ' : 'Đã tải ảnh: ',
            'sample_loaded' => app()->getLocale() === 'en' ? 'Sample data loaded!' : 'Đã nạp dữ liệu mẫu!',
            'exec_success' => app()->getLocale() === 'en' ? 'Executed successfully in :ms ms!' : 'Thực thi thành công trong :ms ms!',
            'network_error' => app()->getLocale() === 'en' ? 'Network error: ' : 'Lỗi kết nối mạng: ',
            'error_generic' => app()->getLocale() === 'en' ? 'An error occurred during processing.' : 'Lỗi trong quá trình xử lý.',
            'copy' => __('copy'),
            'copy_all' => __('copy_all'),
            'copy_code' => __('copy_code'),
            'copy_script' => __('copy_script'),
            'download' => __('download'),
            'downloaded' => app()->getLocale() === 'en' ? 'File downloaded: ' : 'Đã tải xuống tệp ',
        ];
    @endphp
    <script>
        window.__locale = "{{ app()->getLocale() }}";
        window.__i18n = {!! json_encode($i18nData, JSON_UNESCAPED_UNICODE) !!};
    </script>
    @stack('head')
    @stack('schema')
    @stack('schemas')
    @yield('head_extra')
</head>
<body>

    {{-- Global Top Navigation --}}
    <header class="header">
        <div class="container header-inner">
            <a href="{{ url('/') }}" class="logo-wrap">
                <div class="logo-icon" style="display: flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 20px; height: 20px;" /></div>
                <div class="logo-text">TECH<span style="color: var(--accent-cyan);">HUB</span></div>
                <span class="logo-badge">PRO</span>
            </a>

            {{-- Nav Menu (Desktop) --}}
            <nav class="nav-desktop">
                <ul class="nav-menu">
                    <li><a href="{{ url('/tools') }}" class="nav-item-link {{ request()->is('tools*') && !request('category') ? 'active' : '' }}"><x-heroicon-s-bolt style="width: 14px; height: 14px; flex-shrink: 0;" /> {{ __('tools_hub') }}</a></li>
                    <li><a href="{{ route('articles.index') }}" class="nav-item-link {{ request()->is('articles*') ? 'active' : '' }}"><x-heroicon-o-newspaper style="width: 14px; height: 14px; flex-shrink: 0;" /> {{ __('articles') }}</a></li>
                    <li><a href="{{ route('games.index') }}" class="nav-item-link {{ request()->is('games*') ? 'active' : '' }}"><x-heroicon-o-puzzle-piece style="width: 14px; height: 14px; flex-shrink: 0;" /> {{ __('games') }}</a></li>
                    <li><a href="{{ url('/tools?category=seo') }}" class="nav-item-link {{ request('category') === 'seo' ? 'active' : '' }}"><x-heroicon-o-globe-alt style="width: 14px; height: 14px; flex-shrink: 0;" /> {{ __('seo') }}</a></li>
                </ul>
            </nav>

            {{-- Right Controls: Language Switcher, CTA & Mobile Toggle --}}
            <div class="header-right" style="display: flex; align-items: center; gap: 0.75rem;">
                
                {{-- Language Switcher (VI / EN) --}}
                <div class="lang-switcher">
                    <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ app()->getLocale() === 'vi' ? 'active' : '' }}" title="Tiếng Việt">
                        <span>🇻🇳</span> <span>VI</span>
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">
                        <span>🇬🇧</span> <span>EN</span>
                    </a>
                </div>

                <a href="{{ url('/tools') }}" class="btn btn-primary btn-sm header-cta-btn">
                    {{ __('explore_tools') }} →
                </a>

                {{-- Mobile Menu Hamburger Button --}}
                <button type="button" id="btn-mobile-menu" class="btn-mobile-menu" aria-label="Toggle Menu" onclick="toggleMobileMenu()">
                    <x-heroicon-o-bars-3 id="icon-menu-open" style="width: 22px; height: 22px;" />
                    <x-heroicon-o-x-mark id="icon-menu-close" style="width: 22px; height: 22px; display: none;" />
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Drawer --}}
        <div id="mobile-menu-drawer" class="mobile-menu-drawer">
            <div class="container" style="padding: 1rem 1.25rem;">
                <ul class="mobile-nav-list">
                    <li>
                        <a href="{{ url('/tools') }}" class="mobile-nav-link {{ request()->is('tools*') && !request('category') ? 'active' : '' }}">
                            <x-heroicon-s-bolt style="width: 1.2em; height: 1.2em;" />
                            <span>{{ __('tools_hub') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('articles.index') }}" class="mobile-nav-link {{ request()->is('articles*') ? 'active' : '' }}">
                            <x-heroicon-o-newspaper style="width: 1.2em; height: 1.2em;" />
                            <span>{{ __('articles') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('games.index') }}" class="mobile-nav-link {{ request()->is('games*') ? 'active' : '' }}">
                            <x-heroicon-o-puzzle-piece style="width: 1.2em; height: 1.2em;" />
                            <span>🎮 {{ __('games') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tools?category=developer') }}" class="mobile-nav-link {{ request('category') === 'developer' ? 'active' : '' }}">
                            <x-heroicon-o-code-bracket style="width: 1.2em; height: 1.2em;" />
                            <span>{{ __('developer') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tools?category=seo') }}" class="mobile-nav-link {{ request('category') === 'seo' ? 'active' : '' }}">
                            <x-heroicon-o-globe-alt style="width: 1.2em; height: 1.2em;" />
                            <span>{{ __('seo') }}</span>
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()?->role === 'admin')
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link" style="color: var(--accent-indigo);">
                                <x-heroicon-s-shield-check style="width: 1.2em; height: 1.2em;" />
                                <span>{{ __('admin_portal') }}</span>
                            </a>
                        </li>
                    @endif
                    <li style="padding-top: 0.75rem; border-top: 1px solid var(--border-subtle); margin-top: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">{{ app()->getLocale() === 'vi' ? 'Ngôn ngữ' : 'Language' }}</span>
                        <div class="lang-switcher">
                            <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ app()->getLocale() === 'vi' ? 'active' : '' }}" title="Tiếng Việt">
                                <span>🇻🇳</span> <span>VI</span>
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">
                                <span>🇬🇧</span> <span>EN</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Main View Body --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer Banner Ad Slot --}}
    @if($footerAd && (($footerAd->type === 'custom_banner' && $footerAd->image_url) || $footerAd->raw_html))
        <div class="container" style="padding-bottom: 2rem;">
            <div style="border-radius: var(--radius-md); overflow: hidden; text-align: center;">
                @if($footerAd->type === 'custom_banner' && $footerAd->image_url)
                    <a href="{{ $footerAd->target_url ?: '#' }}" target="_blank" rel="nofollow sponsored">
                        <img src="{{ $footerAd->image_url }}" alt="{{ $footerAd->name }}" style="max-width: 100%; height: auto; border-radius: var(--radius-sm); display: block; margin: 0 auto;">
                    </a>
                @elseif($footerAd->raw_html)
                    {!! $footerAd->raw_html !!}
                @endif
            </div>
        </div>
    @endif

    {{-- Global Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="logo-wrap" style="margin-bottom: 1rem;">
                        <div class="logo-icon" style="width: 32px; height: 32px; font-size: 1rem; display: flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 18px; height: 18px;" /></div>
                        <div class="logo-text" style="font-size: 1.2rem;">TECH<span style="color: var(--accent-cyan);">HUB</span></div>
                    </div>
                    <p style="font-size: 0.9rem; line-height: 1.6; max-width: 320px;">
                        {{ __('footer_desc') }}
                    </p>
                </div>

                <div class="footer-col">
                    <h4>{{ __('developer') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/tools/json-formatter') }}">JSON Formatter</a></li>
                        <li><a href="{{ url('/tools/jwt-debugger') }}">JWT Debugger</a></li>
                        <li><a href="{{ url('/tools/regex-tester') }}">Regex Tester</a></li>
                        <li><a href="{{ url('/tools/hash-generator') }}">Hash Generator</a></li>
                        <li><a href="{{ url('/tools/base64-encode-decode') }}">Base64 Tool</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>{{ __('calculators') }} &amp; {{ __('image') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/tools/loan-calculator') }}">Loan Calculator (EMI)</a></li>
                        <li><a href="{{ url('/tools/percentage-calculator') }}">Percentage Calculator</a></li>
                        <li><a href="{{ url('/tools/bmi-calculator') }}">BMI &amp; Body Weight</a></li>
                        <li><a href="{{ url('/tools/image-color-extractor') }}">Color Palette Extractor</a></li>
                        <li><a href="{{ url('/tools/image-metadata-inspector') }}">EXIF Metadata</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>{{ __('specs_title') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/api/tools') }}">{{ __('api_access') }}</a></li>
                        <li><a href="{{ url('/up') }}">{{ __('system_status') }}</a></li>
                        <li><a href="{{ url('/admin/login') }}">{{ __('admin_portal') }}</a></li>
                        <li><a href="{{ url('/tools') }}">{{ __('all_tools') }} (18+)</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ __('copyright') }}</p>
            </div>
        </div>
    </footer>

    {{-- Swiper.js Bundle --}}
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Core Javascript --}}
    <script defer src="{{ asset('js/techhub.js') }}?v={{ @filemtime(public_path('js/techhub.js')) ?: '2.0.1' }}"></script>
    @stack('scripts')
</body>
</html>
