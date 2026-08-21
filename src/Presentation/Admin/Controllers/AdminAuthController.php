<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\User\Enums\UserStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Presentation\Controller;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && 'admin' === Auth::user()?->role) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'admin_login_' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Quá nhiều lần đăng nhập không thành công. Vui lòng thử lại sau {$seconds} giây.",
            ]);
        }

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user && 'admin' === $user->role && (UserStatus::Active === $user->status || 'active' === $user->status)) {
                RateLimiter::clear($throttleKey);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Tài khoản không có quyền truy cập trang quản trị.',
            ]);
        }

        RateLimiter::hit($throttleKey, 300);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    /**
     * Log out from admin panel.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Đã đăng xuất thành công.');
    }
}
