<?php

declare(strict_types=1);

namespace Presentation\Admin\Middleware;

use Closure;
use Domain\User\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request and check admin privileges.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        $user = Auth::user();

        // Verify admin role and active status
        $role = $user->role ?? null;
        $status = $user->status ?? null;
        $statusValue = $status instanceof UserStatus ? $status->value : (string) $status;

        $isAdmin = 'admin' === $role;
        $isActive = 'active' === $statusValue;

        if ( ! $isAdmin || ! $isActive) {
            return redirect()->route('admin.login')->with('error', 'Tài khoản không có quyền truy cập quản trị hoặc đã bị khóa.');
        }

        return $next($request);
    }
}
