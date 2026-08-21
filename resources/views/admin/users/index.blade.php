@extends('admin.layouts.app')

@section('title', 'Quản Lý Người Dùng & Phân Quyền')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Quản Lý <span class="gradient-text">Người Dùng &amp; Phân Quyền</span></h1>
        <p style="margin-top: 0.25rem;">Quản lý toàn bộ danh sách tài khoản, vai trò phân quyền (admin/member/pro) và trạng thái.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('modal-create-user').style.display='flex'">
        + Thêm Thành Viên Mới
    </button>
</div>

{{-- Filters --}}
<div class="tool-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" style="max-width: 320px;" placeholder="Tìm tên hoặc email..." value="{{ request('search') }}">
        
        <select name="role" class="form-control" style="max-width: 160px;">
            <option value="">Tất cả vai trò</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị (admin)</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Thành viên (user)</option>
            <option value="pro" {{ request('role') === 'pro' ? 'selected' : '' }}>Thành viên VIP (pro)</option>
        </select>

        <select name="status" class="form-control" style="max-width: 160px;">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Bị khóa (Banned)</option>
        </select>

        <button type="submit" class="btn btn-secondary btn-sm"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Lọc</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Xóa lọc</a>
    </form>
</div>

{{-- Users Table --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID / Tên</th>
                <th>Email</th>
                <th>Vai Trò (Role)</th>
                <th>Trạng Thái</th>
                <th>Ngày Tạo</th>
                <th style="text-align: right;">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: var(--accent-cyan);">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <strong style="color: var(--text-main); display: block;">{{ $user->name }}</strong>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">ID: #{{ $user->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge" style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc; border-color: rgba(99, 102, 241, 0.4);">
                                <x-heroicon-o-shield-check style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Admin
                            </span>
                        @elseif($user->role === 'pro')
                            <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fcd34d; border-color: rgba(245, 158, 11, 0.4);">
                                ⭐ PRO VIP
                            </span>
                        @else
                            <span class="badge">Member</span>
                        @endif
                    </td>
                    <td>
                        @if($user->status->value === 'active' || $user->status === 'active')
                            <span class="badge badge-emerald">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Bị khóa</span>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-muted);">
                        {{ $user->created_at?->format('d/m/Y H:i') }}
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn btn-secondary btn-sm" 
                                onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->role }}', '{{ is_string($user->status) ? $user->status : $user->status->value }}')">
                            ✏️ Sửa
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        Không có người dùng nào phù hợp.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.5rem;">
    {{ $users->links() }}
</div>

{{-- Modal: Create User --}}
<div id="modal-create-user" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 200; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="tool-panel" style="width: 100%; max-width: 500px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="color: var(--text-main);">Thêm Thành Viên Mới</h3>
            <button type="button" onclick="document.getElementById('modal-create-user').style.display='none'" class="btn btn-secondary btn-sm">✕</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Tối thiểu 8 ký tự" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Vai trò (Role)</label>
                    <select name="role" class="form-control">
                        <option value="user">User (Thành viên)</option>
                        <option value="pro">Pro (VIP)</option>
                        <option value="admin">Admin (Quản trị)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active">Hoạt động</option>
                        <option value="banned">Bị khóa</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('modal-create-user').style.display='none'" class="btn btn-secondary btn-sm">Hủy</button>
                <button type="submit" class="btn btn-primary btn-sm">Tạo Thành Viên</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit User --}}
<div id="modal-edit-user" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 200; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="tool-panel" style="width: 100%; max-width: 500px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="color: var(--text-main);" id="edit-user-title">Cập Nhật Người Dùng</h3>
            <button type="button" onclick="document.getElementById('modal-edit-user').style.display='none'" class="btn btn-secondary btn-sm">✕</button>
        </div>
        <form id="edit-user-form" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Họ và Tên</label>
                <input type="text" id="edit-user-name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Đổi mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu mới...">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Vai trò (Role)</label>
                    <select id="edit-user-role" name="role" class="form-control">
                        <option value="user">User (Thành viên)</option>
                        <option value="pro">Pro (VIP)</option>
                        <option value="admin">Admin (Quản trị)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Trạng thái</label>
                    <select id="edit-user-status" name="status" class="form-control">
                        <option value="active">Hoạt động</option>
                        <option value="banned">Khóa tài khoản (Banned)</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('modal-edit-user').style.display='none'" class="btn btn-secondary btn-sm">Hủy</button>
                <button type="submit" class="btn btn-primary btn-sm">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditUserModal(id, name, role, status) {
    document.getElementById('edit-user-title').innerText = 'Cập Nhật Người Dùng #' + id;
    document.getElementById('edit-user-form').action = '/admin/users/' + id;
    document.getElementById('edit-user-name').value = name;
    document.getElementById('edit-user-role').value = role;
    document.getElementById('edit-user-status').value = status;
    document.getElementById('modal-edit-user').style.display = 'flex';
}
</script>
@endpush
