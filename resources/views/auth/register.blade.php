@extends('layouts.app')

@section('meta_title', app()->getLocale() === 'en' ? 'Create a Free Account | TechHub PRO' : 'Đăng Ký Tài Khoản Miễn Phí | TechHub PRO')
@section('meta_description', app()->getLocale() === 'en' ? 'Create a free TechHub account to build websites with visual drag-and-drop, manage digital tools, and publish online.' : 'Đăng ký tài khoản TechHub miễn phí để tạo website kéo thả, quản lý công cụ và xuất bản trang trực tuyến.')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 3rem 1.25rem 5rem;">
    <div style="width: 100%; max-width: 480px;">
        
        {{-- Card Container --}}
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); padding: 2.5rem 2.25rem; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -40px; top: -40px; width: 160px; height: 160px; background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); pointer-events: none;"></div>

            {{-- Header --}}
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: linear-gradient(135deg, #0284c7, #06b6d4); color: #ffffff; border-radius: 16px; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(2, 132, 199, 0.3);">
                    <x-heroicon-s-sparkles style="width: 30px; height: 30px;" />
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.4rem; letter-spacing: -0.02em;">
                    {{ app()->getLocale() === 'en' ? 'Create Your Account' : 'Tạo Tài Khoản Mới' }}
                </h1>
                <p style="color: #64748b; font-size: 0.92rem; margin: 0;">
                    {{ app()->getLocale() === 'en' ? 'Free forever. Start building beautiful websites today.' : 'Hoàn toàn miễn phí. Bắt đầu thiết kế website ngay hôm nay.' }}
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

            {{-- Form --}}
            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" style="display: block; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        {{ app()->getLocale() === 'en' ? 'Full Name' : 'Họ và Tên' }}
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="{{ app()->getLocale() === 'en' ? 'John Doe' : 'Nguyễn Văn A' }}" 
                        required 
                        autofocus
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#0284c7'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

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
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#0284c7'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="password" style="display: block; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        {{ app()->getLocale() === 'en' ? 'Password' : 'Mật Khẩu' }}
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="{{ app()->getLocale() === 'en' ? 'At least 6 characters' : 'Tối thiểu 6 ký tự' }}" 
                        required 
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#0284c7'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

                <div style="margin-bottom: 1.75rem;">
                    <label for="password_confirmation" style="display: block; font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        {{ app()->getLocale() === 'en' ? 'Confirm Password' : 'Xác Nhận Mật Khẩu' }}
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="{{ app()->getLocale() === 'en' ? 'Re-enter your password' : 'Nhập lại mật khẩu' }}" 
                        required 
                        style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.95rem; color: #0f172a; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='#0284c7'; this.style.background='#ffffff'"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'"
                    >
                </div>

                <button 
                    type="submit" 
                    style="width: 100%; background: linear-gradient(135deg, #0284c7, #2563eb); color: #ffffff; border: none; padding: 0.85rem; border-radius: 12px; font-size: 1rem; font-weight: 800; cursor: pointer; box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.15s;"
                    onmouseover="this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                >
                    <span>{{ app()->getLocale() === 'en' ? 'Create Account' : 'Tạo Tài Khoản Ngay' }}</span>
                    <span>→</span>
                </button>
            </form>

            {{-- Footer Switch --}}
            <div style="text-align: center; margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; font-size: 0.9rem; color: #64748b;">
                {{ app()->getLocale() === 'en' ? 'Already have an account?' : 'Đã có tài khoản TechHub?' }}
                <a href="{{ route('login') }}" style="color: #0284c7; font-weight: 700; text-decoration: none; margin-left: 0.35rem;">
                    {{ app()->getLocale() === 'en' ? 'Log in' : 'Đăng nhập' }}
                </a>
            </div>

        </div>

        {{-- Help note --}}
        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.84rem; color: #94a3b8;">
            TechHub &copy; {{ date('Y') }} — {{ app()->getLocale() === 'en' ? 'Join thousands of creators' : 'Gia nhập cộng đồng sáng tạo' }}
        </div>

    </div>
</div>
@endsection
