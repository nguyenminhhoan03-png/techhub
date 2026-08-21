@extends('admin.layouts.app')

@section('title', 'Quản Lý Công Cụ & Danh Mục')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>Quản Lý <span class="gradient-text">Công Cụ &amp; Danh Mục</span></h1>
    <p style="margin-top: 0.25rem;">Bật/tắt công cụ, chỉnh sửa thông số cấu hình và quản lý cờ Premium.</p>
</div>

{{-- Filter --}}
<div class="tool-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;">
    <form method="GET" action="{{ route('admin.tools.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" style="max-width: 320px;" placeholder="Tìm tên hoặc slug..." value="{{ request('search') }}">
        
        <select name="category_id" class="form-control" style="max-width: 220px;">
            <option value="">Tất cả chuyên mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary btn-sm"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Lọc</button>
        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary btn-sm">Xóa lọc</a>
    </form>
</div>

{{-- Tools Table --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tên Công Cụ / Slug</th>
                <th>Chuyên Mục</th>
                <th>Động Cơ (Engine)</th>
                <th>Lượt Chạy</th>
                <th>Đánh Giá</th>
                <th>Trạng Thái</th>
                <th style="text-align: right;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tools as $tool)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); display: block;">{{ $tool->name }}</strong>
                        <code style="font-size: 0.75rem; color: var(--accent-cyan);">/tools/{{ $tool->slug }}</code>
                    </td>
                    <td>
                        <span class="badge">{{ $tool->category?->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge" style="font-family: var(--font-mono);">{{ $tool->engine_type->value }}</span>
                    </td>
                    <td style="font-family: var(--font-mono); color: var(--text-main);">
                        {{ number_format($tool->execution_count) }}
                    </td>
                    <td>
                        <span style="color: var(--accent-amber);">★ {{ number_format((float)$tool->rating_avg, 2) }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.tools.toggle', $tool->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $tool->is_active ? 'btn-primary' : 'btn-danger' }}" style="padding: 0.25rem 0.65rem; font-size: 0.78rem;">
                                {{ $tool->is_active ? '● Đang Bật' : '○ Tạm Dừng' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ url('/tools/' . $tool->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="Xem ngoài web">
                            <x-heroicon-o-eye style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
