@extends('admin.layouts.app')

@section('title', 'Quản Lý Tài Khoản AI & Deals')

@section('content')
<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.6rem;">
            <span>🛒</span> <span>Quản Lý Tài Khoản AI &amp; Deals</span>
        </h1>
        <p style="color: var(--text-sub); font-size: 0.9rem;">
            Đăng tải và quản lý các gói tài khoản bản quyền (Google AI Pro, Gemini, ChatGPT, Claude, Dev Tools...) bán cho người dùng.
        </p>
    </div>

    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('deals.index') }}" target="_blank" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
            <span>🌐</span> <span>Xem Cửa Hàng Ngoài Web ↗</span>
        </a>
        <a href="{{ route('admin.deals.create') }}" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
            <span>➕</span> <span>Đăng Sản Phẩm Mới</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <span>✓</span> <span>{{ session('success') }}</span>
    </div>
@endif

{{-- Filter & Search Toolbar --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <form action="{{ route('admin.deals.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; flex: 1;">
        <div style="min-width: 260px; flex: 1;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Tìm theo tên sản phẩm, slug, mô tả..."
                   style="width: 100%; padding: 0.55rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.88rem;">
        </div>

        <div style="min-width: 200px;">
            <select name="category" onchange="this.form.submit()"
                    style="width: 100%; padding: 0.55rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.88rem;">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-secondary btn-sm">Lọc</button>
        @if(request('search') || request('category'))
            <a href="{{ route('admin.deals.index') }}" class="btn btn-secondary btn-sm" style="color: var(--text-muted);">Xóa lọc</a>
        @endif
    </form>
</div>

{{-- Deals Data Table --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
            <thead>
                <tr style="background: var(--bg-surface-elevated); border-bottom: 1px solid var(--border-subtle); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 0.85rem 1rem;">Sản Phẩm</th>
                    <th style="padding: 0.85rem 1rem;">Danh Mục</th>
                    <th style="padding: 0.85rem 1rem;">Giá Bán</th>
                    <th style="padding: 0.85rem 1rem;">Phân Loại</th>
                    <th style="padding: 0.85rem 1rem;">Đã Bán</th>
                    <th style="padding: 0.85rem 1rem;">Trạng Thái</th>
                    <th style="padding: 0.85rem 1rem; text-align: right;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                    <tr style="border-bottom: 1px solid var(--border-subtle); transition: background 0.15s ease;" onmouseover="this.style.background='var(--bg-surface-elevated)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 0.95rem 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                @if($deal->thumbnail_url)
                                    <img src="{{ $deal->thumbnail_url }}" alt="{{ $deal->name }}" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-subtle); flex-shrink: 0;">
                                @else
                                    <div style="width: 50px; height: 50px; border-radius: 10px; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                                        ⚡
                                    </div>
                                @endif
                                <div style="min-width: 0;">
                                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.92rem; margin-bottom: 0.25rem;">
                                        {{ $deal->name }}
                                    </div>
                                    <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                        @if($deal->badge_text)
                                            <span style="font-size: 0.68rem; font-weight: 800; background: rgba(99,102,241,0.15); color: #6366f1; padding: 0.1rem 0.4rem; border-radius: 4px;">
                                                {{ $deal->badge_text }}
                                            </span>
                                        @endif
                                        @if($deal->sub_badge)
                                            <span style="font-size: 0.68rem; font-weight: 800; background: rgba(16,185,129,0.15); color: #10b981; padding: 0.1rem 0.4rem; border-radius: 4px;">
                                                {{ $deal->sub_badge }}
                                            </span>
                                        @endif
                                        <span style="font-size: 0.72rem; color: var(--text-muted);">slug: {{ $deal->slug }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 0.95rem 1rem;">
                            <span style="display: inline-block; font-size: 0.78rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 999px; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); color: var(--text-sub);">
                                {{ $deal->category }}
                            </span>
                        </td>

                        <td style="padding: 0.95rem 1rem;">
                            <div style="font-weight: 800; color: var(--accent-emerald); font-size: 0.95rem;">
                                {{ $deal->formatted_price }}
                            </div>
                            @if($deal->original_price)
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-decoration: line-through;">
                                    {{ $deal->formatted_original_price }}
                                </div>
                            @endif
                        </td>

                        <td style="padding: 0.95rem 1rem;">
                            @if(!empty($deal->variants))
                                <span style="font-size: 0.78rem; font-weight: 700; color: var(--accent-indigo); background: rgba(99,102,241,0.1); padding: 0.15rem 0.5rem; border-radius: 999px;">
                                    {{ count($deal->variants) }} gói tùy chọn
                                </span>
                            @else
                                <span style="font-size: 0.78rem; color: var(--text-muted);">Giá cố định</span>
                            @endif
                        </td>

                        <td style="padding: 0.95rem 1rem;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">
                                {{ number_format($deal->sold_count) }}
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">đơn</span>
                        </td>

                        <td style="padding: 0.95rem 1rem;">
                            <form action="{{ route('admin.deals.toggle', $deal->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="border: none; background: transparent; cursor: pointer; display: flex; align-items: center; gap: 0.35rem; font-size: 0.82rem; font-weight: 700; color: {{ $deal->is_active ? 'var(--accent-emerald)' : 'var(--text-muted)' }};">
                                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $deal->is_active ? 'var(--accent-emerald)' : '#94a3b8' }};"></span>
                                    <span>{{ $deal->is_active ? 'Hiển thị' : 'Đang ẩn' }}</span>
                                </button>
                            </form>
                        </td>

                        <td style="padding: 0.95rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.4rem; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('deals.show', $deal->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="Xem ngoài web" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">
                                    ↗
                                </a>
                                <a href="{{ route('admin.deals.edit', $deal->id) }}" class="btn btn-secondary btn-sm" title="Chỉnh sửa" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa sản phẩm" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2.5rem; text-align: center; color: var(--text-muted);">
                            Chưa có sản phẩm nào. Hãy bấm <strong>"Đăng Sản Phẩm Mới"</strong> để bắt đầu đăng tải gói tài khoản AI!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($deals->hasPages())
        <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--border-subtle);">
            {{ $deals->links() }}
        </div>
    @endif
</div>
@endsection
