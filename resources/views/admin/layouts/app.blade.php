<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — TechHub Quản Trị</title>
    
    {{-- Favicon & App Icons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/techhub.css') }}">
</head>
<body>

    {{-- Admin Mobile Top Bar --}}
    <div class="admin-mobile-topbar" style="display: none; background: #ffffff; border-bottom: 1px solid var(--border-subtle); padding: 0.75rem 1rem; align-items: center; justify-content: space-between;">
        <a href="{{ route('admin.dashboard') }}" class="logo-wrap">
            <div class="logo-icon" style="width: 28px; height: 28px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 18px; height: 18px;" /></div>
            <div class="logo-text" style="font-size: 1.1rem;">ADMIN<span style="color: var(--accent-cyan);">HUB</span></div>
        </a>
        <button type="button" class="btn btn-secondary btn-sm" onclick="document.querySelector('.admin-sidebar').classList.toggle('open')">
            <x-heroicon-o-bars-3 style="width: 20px; height: 20px;" />
        </button>
    </div>

    <div class="admin-layout">
        
        {{-- Left Sidebar --}}
        <aside class="admin-sidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding: 0 0.5rem;">
                <a href="{{ route('admin.dashboard') }}" class="logo-wrap">
                    <div class="logo-icon" style="width: 32px; height: 32px; font-size: 1rem; display: flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 20px; height: 20px;" /></div>
                    <div class="logo-text" style="font-size: 1.2rem;">ADMIN<span style="color: var(--accent-cyan);">HUB</span></div>
                </a>
            </div>

            <div style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); padding: 0 0.75rem 0.5rem; font-weight: 700;">
                Quản Trị Hệ Thống
            </div>

            <nav style="flex: 1;">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-chart-bar style="width: 1.2em; height: 1.2em;" /></span> <span>Tổng Quan</span>
                </a>
                <a href="{{ route('admin.articles.index') }}" class="admin-nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-newspaper style="width: 1.2em; height: 1.2em;" /></span> <span>Bài Viết &amp; So Sánh</span>
                </a>
                <a href="{{ route('admin.hardware.index') }}" class="admin-nav-item {{ request()->routeIs('admin.hardware.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-cpu-chip style="width: 1.2em; height: 1.2em;" /></span> <span>CSDL Phần Cứng</span>
                </a>
                <a href="{{ route('admin.ai_studio.index') }}" class="admin-nav-item {{ request()->routeIs('admin.ai_studio.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-sparkles style="width: 1.2em; height: 1.2em; color: var(--accent-indigo);" /></span> <span style="font-weight: 700; color: var(--accent-indigo);">AI Content Studio</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-users style="width: 1.2em; height: 1.2em;" /></span> <span>Người Dùng &amp; Quyền</span>
                </a>
                <a href="{{ route('admin.tools.index') }}" class="admin-nav-item {{ request()->routeIs('admin.tools.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-wrench-screwdriver style="width: 1.2em; height: 1.2em;" /></span> <span>Công Cụ &amp; Danh Mục</span>
                </a>
                <a href="{{ route('admin.ads.index') }}" class="admin-nav-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-megaphone style="width: 1.2em; height: 1.2em;" /></span> <span>Quảng Cáo &amp; Banner</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-cog-6-tooth style="width: 1.2em; height: 1.2em;" /></span> <span>Cấu Hình &amp; Text Động</span>
                </a>
            </nav>

            {{-- Footer User Info --}}
            <div style="border-top: 1px solid var(--border-subtle); padding-top: 1rem; margin-top: auto;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; padding: 0 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gradient-brand); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                            {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ auth()->user()?->name ?? 'Admin' }}</div>
                            <div style="font-size: 0.72rem; color: var(--accent-emerald); font-weight: 600;">● Trực tuyến</div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-secondary btn-sm" style="flex: 1; font-size: 0.75rem;">
                        Trang Chủ ↗
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; font-size: 0.75rem;">
                            Đăng Xuất
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Admin Content --}}
        <main class="admin-main">
            
            {{-- Alert Notifications --}}
            @if(session('success'))
                <div class="tool-card" style="background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.4); padding: 1rem 1.5rem; margin-bottom: 1.5rem; color: #34d399; display: flex; align-items: center; gap: 0.75rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-check-circle style="width: 1.5em; height: 1.5em;" /></span> <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="tool-card" style="background: rgba(244, 63, 94, 0.12); border-color: rgba(244, 63, 94, 0.4); padding: 1rem 1.5rem; margin-bottom: 1.5rem; color: #fca5a5; display: flex; align-items: center; gap: 0.75rem;">
                    <span style="display: flex; align-items: center;"><x-heroicon-o-exclamation-triangle style="width: 1.5em; height: 1.5em;" /></span> <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <script src="{{ asset('js/techhub.js') }}"></script>
    @stack('scripts')
</body>
</html>
