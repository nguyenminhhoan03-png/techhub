@extends('layouts.app')

@section('meta_title', app()->getLocale() === 'en' ? 'Log in to Your TechHub Account | TechHub PRO' : 'Đăng Nhập Tài Khoản TechHub | TechHub PRO')
@section('meta_description', app()->getLocale() === 'en' ? 'Log in to your TechHub account to manage your websites, developer tools, and projects.' : 'Đăng nhập vào tài khoản TechHub của bạn để quản lý website, công cụ lập trình và dự án cá nhân.')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 3rem 1.25rem 5rem;">
    <div style="width: 100%; max-width: 460px;">
        
        {{-- Card Container --}}
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); padding: 2.5rem 2.25rem; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -40px; top: -40px; width: 160px; height: 160px; background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%); pointer-events: none;"></div>

            {{-- Header --}}
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: linear-gradient(135deg, #2563eb, #0284c7); color: #ffffff; border-radius: 16px; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);">
                    <x-heroicon-s-bolt style="width: 30px; height: 30px;" />
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.4rem; letter-spacing: -0.02em;">
                    {{ app()->getLocale() === 'en' ? 'Welcome Back' : 'Đăng Nhập TechHub' }}
                </h1>
                <p style="color: #64748b; font-size: 0.92rem; margin: 0;">
                    {{ app()->getLocale() === 'en' ? 'Access your websites, visual editor, and tools.' : 'Quản lý website kéo thả, công cụ và dự án của bạn.' }}
                </p>
            </div>

            {{-- Errors & Alerts --}}
            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; color: #dc2626; font-size: 0.88rem;">
                    @foreach($errors->all() as $error)
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                            <span>⚠️</span> <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; color: #dc2626; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>⚠️</span> <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; color: #16a34a; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>✅</span> <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label for="email" style="display: block; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        {{ app()->getLocale() === 'en' ? 'Email Address' : 'Địa Chỉ Email' }}
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="you@example.com" 
                        required 
                        autofocus
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#2563eb'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        {{ app()->getLocale() === 'en' ? 'Password' : 'Mật Khẩu' }}
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required 
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#2563eb'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: #64748b; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1" checked style="accent-color: #2563eb; width: 16px; height: 16px; cursor: pointer;">
                        <span>{{ app()->getLocale() === 'en' ? 'Remember me' : 'Ghi nhớ đăng nhập' }}</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    style="width: 100%; background: linear-gradient(135deg, #2563eb, #0284c7); color: #ffffff; border: none; padding: 0.85rem; border-radius: 12px; font-size: 1rem; font-weight: 800; cursor: pointer; box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.15s;"
                    onmouseover="this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                >
                    <span>{{ app()->getLocale() === 'en' ? 'Log In' : 'Đăng Nhập Ngay' }}</span>
                    <span>→</span>
                </button>
            </form>

            {{-- Footer Switch --}}
            <div style="text-align: center; margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; font-size: 0.9rem; color: #64748b;">
                {{ app()->getLocale() === 'en' ? "Don't have an account?" : 'Chưa có tài khoản TechHub?' }}
                <a href="{{ route('register') }}" style="color: #2563eb; font-weight: 700; text-decoration: none; margin-left: 0.35rem;">
                    {{ app()->getLocale() === 'en' ? 'Sign up free' : 'Đăng ký miễn phí' }}
                </a>
            </div>

        </div>

        {{-- Help note --}}
        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.84rem; color: #94a3b8;">
            TechHub &copy; {{ date('Y') }} — {{ app()->getLocale() === 'en' ? 'Secure Member Portal' : 'Cổng bảo mật thành viên' }}
        </div>

    </div>
</div>
@endsection
