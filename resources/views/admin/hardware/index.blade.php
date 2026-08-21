@extends('admin.layouts.app')

@section('title', 'Quản Lý Dữ Liệu Phần Cứng & Specs')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Quản Lý <span class="gradient-text">CSDL Phần Cứng &amp; Specs</span></h1>
        <p style="margin-top: 0.25rem;">Danh mục linh kiện CPU, GPU, Smartphone, điểm Benchmark và thông số kỹ thuật.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.ai_studio.index') }}" class="btn btn-secondary btn-sm" style="color: var(--accent-indigo); font-weight: 700;">
            ⚡ So Sánh Bằng AI
        </a>
        <a href="{{ route('admin.hardware.create') }}" class="btn btn-primary btn-sm">
            + Thêm Thiết Bị Mới
        </a>
    </div>
</div>

{{-- Search & Filter --}}
<div class="tool-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;">
    <form method="GET" action="{{ route('admin.hardware.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" style="max-width: 320px;" placeholder="Tìm tên linh kiện, model..." value="{{ request('search') }}">
        
        <select name="category_id" class="form-control" style="max-width: 220px;">
            <option value="">Tất cả loại linh kiện</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary btn-sm"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Lọc</button>
        <a href="{{ route('admin.hardware.index') }}" class="btn btn-secondary btn-sm">Xóa lọc</a>
    </form>
</div>

{{-- Products Table --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tên Thiết Bị / Model</th>
                <th>Thương Hiệu</th>
                <th>Phân Loại</th>
                <th>Giá MSRP</th>
                <th>Điểm Tổng Thể</th>
                <th>Gaming</th>
                <th style="text-align: right;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $prod)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); display: block;">{{ $prod->full_name }}</strong>
                        <code style="font-size: 0.75rem; color: var(--accent-indigo);">{{ $prod->model_name }}</code>
                    </td>
                    <td>
                        <span class="badge">{{ $prod->brand?->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge" style="color: var(--accent-cyan);">{{ $prod->category?->name ?? 'N/A' }}</span>
                    </td>
                    <td style="font-family: var(--font-mono); font-weight: 600;">
                        {{ $prod->launch_msrp_usd ? '$' . number_format($prod->launch_msrp_usd) : 'N/A' }}
                    </td>
                    <td>
                        <span style="font-weight: 800; color: var(--accent-indigo); font-family: var(--font-mono);">{{ $prod->overall_score }}/10</span>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: var(--accent-emerald); font-family: var(--font-mono);">{{ $prod->gaming_score }}/10</span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.hardware.edit', $prod->id) }}" class="btn btn-secondary btn-sm" title="Sửa thông số">
                            <x-heroicon-o-pencil-square style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                        </a>
                        <form action="{{ route('admin.hardware.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa thiết bị này khỏi CSDL?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                <x-heroicon-o-trash style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        Chưa có sản phẩm phần cứng nào được thêm vào hệ thống.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $products->links() }}
</div>
@endsection
