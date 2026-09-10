<?php

declare(strict_types=1);

namespace Presentation\UserManagement\Controllers;

use Domain\User\Entities\User;
use Domain\User\Enums\UserStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Presentation\Controller;

class UserAuthController extends Controller
{
    /**
     * Hiển thị giao diện Đăng nhập thành viên.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('builder.index');
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập thành viên.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'user_login_' . Str::lower((string) $request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => app()->getLocale() === 'en'
                    ? "Too many login attempts. Please try again in {$seconds} seconds."
                    : "Bạn đã đăng nhập sai quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
            ]);
        }

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $statusValue = $user?->status instanceof UserStatus ? $user->status->value : (string) ($user?->status ?? 'active');

            if ('active' === $statusValue) {
                RateLimiter::clear($throttleKey);
                $request->session()->regenerate();

                return redirect()->intended(route('builder.index'))->with('success', app()->getLocale() === 'en'
                    ? 'Welcome back, ' . $user->name . '!'
                    : 'Chào mừng bạn quay trở lại, ' . $user->name . '!');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => app()->getLocale() === 'en'
                    ? 'Your account has been deactivated or suspended.'
                    : 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.',
            ]);
        }

        RateLimiter::hit($throttleKey, 300);

        return back()->withInput($request->only('email'))->withErrors([
            'email' => app()->getLocale() === 'en'
                ? 'Invalid email or password.'
                : 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    /**
     * Hiển thị giao diện Đăng ký thành viên mới.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('builder.index');
        }

        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản thành viên mới.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => app()->getLocale() === 'en' ? 'Full name is required.' : 'Vui lòng nhập họ và tên.',
            'email.required' => app()->getLocale() === 'en' ? 'Email is required.' : 'Vui lòng nhập địa chỉ email.',
            'email.unique' => app()->getLocale() === 'en' ? 'This email is already registered.' : 'Địa chỉ email này đã được sử dụng.',
            'password.required' => app()->getLocale() === 'en' ? 'Password is required.' : 'Vui lòng nhập mật khẩu.',
            'password.min' => app()->getLocale() === 'en' ? 'Password must be at least 6 characters.' : 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => app()->getLocale() === 'en' ? 'Password confirmation does not match.' : 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'ulid' => (string) Str::ulid(),
            'name' => (string) $validated['name'],
            'email' => (string) $validated['email'],
            'password' => (string) $validated['password'], // cast to hashed in model
            'status' => UserStatus::Active,
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('builder.index')->with('success', app()->getLocale() === 'en'
            ? 'Account registered successfully! Welcome to TechHub.'
            : 'Đăng ký tài khoản thành công! Chào mừng bạn đến với TechHub.');
    }

    /**
     * Xử lý đăng xuất tài khoản.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', app()->getLocale() === 'en'
            ? 'You have been logged out successfully.'
            : 'Đã đăng xuất tài khoản thành công.');
    }
}
