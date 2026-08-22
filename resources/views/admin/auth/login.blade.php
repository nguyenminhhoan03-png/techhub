<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị — TechHub Admin</title>
    
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
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; padding: 1.5rem;">

    <div style="width: 100%; max-width: 440px;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="logo-wrap" style="justify-content: center; margin-bottom: 0.75rem;">
                <div class="logo-icon" style="width: 44px; height: 44px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center;"><x-heroicon-s-bolt style="width: 28px; height: 28px;" /></div>
                <div class="logo-text" style="font-size: 1.6rem;">ADMIN<span style="color: var(--accent-cyan);">HUB</span></div>
            </div>
            <p style="font-size: 0.95rem; color: var(--text-sub);">Hệ thống quản trị &amp; phân quyền TechHub</p>
        </div>

        @if($errors->any())
            <div class="tool-card" style="background: rgba(244, 63, 94, 0.12); border-color: rgba(244, 63, 94, 0.4); padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #fca5a5;">
                @foreach($errors->all() as $error)
                    <div style="display: flex; align-items: center; gap: 0.5rem;"><x-heroicon-o-exclamation-triangle style="width: 1.2em; height: 1.2em;" /> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="tool-card" style="background: rgba(244, 63, 94, 0.12); border-color: rgba(244, 63, 94, 0.4); padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #fca5a5;">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><x-heroicon-o-exclamation-triangle style="width: 1.2em; height: 1.2em;" /> {{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="tool-card" style="background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.4); padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #34d399;">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><x-heroicon-o-check-circle style="width: 1.2em; height: 1.2em;" /> {{ session('success') }}</span>
            </div>
        @endif

        <div class="tool-panel" style="padding: 2.25rem;">
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Quản Trị Viên</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@techhub.local" value="{{ old('email', 'admin@techhub.local') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: var(--text-sub); cursor: pointer;">
                        <input type="checkbox" name="remember" value="1" checked>
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 1rem;">
                    <x-heroicon-s-lock-closed style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Đăng Nhập Quản Trị →
                </button>
            </form>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ url('/') }}" style="font-size: 0.88rem; color: var(--text-muted);">
                ← Quay lại Trang Chủ TechHub
            </a>
        </div>

    </div>

</body>
</html>
