<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\User\Entities\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Presentation\Controller;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::query()->orderByDesc('id');

        if ($search && is_string($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role && is_string($role)) {
            $query->where('role', $role);
        }

        if ($status && is_string($status)) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,user,pro'],
            'status' => ['required', 'string', 'in:active,inactive,banned'],
        ]);

        User::query()->create([
            'ulid' => (string) Str::ulid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã tạo người dùng mới thành công.');
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,user,pro'],
            'status' => ['required', 'string', 'in:active,inactive,banned'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if ( ! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Đã cập nhật thông tin thành viên #{$user->id}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản đang đăng nhập.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công.');
    }
}
