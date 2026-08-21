@extends('admin.layouts.app')

@section('title', 'Quản Lý Bài Viết & So Sánh')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Quản Lý <span class="gradient-text">Bài Viết &amp; So Sánh</span></h1>
        <p style="margin-top: 0.25rem;">Quản lý nội dung đánh giá công nghệ, so sánh linh kiện đối đầu và cẩm nang mua sắm.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.ai_studio.index') }}" class="btn btn-secondary btn-sm" style="background: linear-gradient(135deg, rgba(79,70,229,0.15) 0%, rgba(2,132,199,0.15) 100%); border-color: rgba(79,70,229,0.3); color: var(--accent-indigo); font-weight: 700;">
            🤖 AI Content Studio
        </a>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
            + Viết Bài Mới
        </a>
    </div>
</div>

{{-- Search & Filter --}}
<div class="tool-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;">
    <form method="GET" action="{{ route('admin.articles.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" style="max-width: 320px;" placeholder="Tìm theo tiêu đề bài viết..." value="{{ request('search') }}">
        
        <select name="type" class="form-control" style="max-width: 200px;">
            <option value="">Tất cả loại bài</option>
            <option value="comparison" {{ request('type') === 'comparison' ? 'selected' : '' }}>So Sánh Đối Đầu</option>
            <option value="review" {{ request('type') === 'review' ? 'selected' : '' }}>Đánh Giá Chi Tiết</option>
            <option value="buying_guide" {{ request('type') === 'buying_guide' ? 'selected' : '' }}>Tư Vấn Mua Sắm</option>
            <option value="news" {{ request('type') === 'news' ? 'selected' : '' }}>Tin Công Nghệ</option>
            <option value="article" {{ request('type') === 'article' ? 'selected' : '' }}>Bài Viết Chuẩn</option>
        </select>

        <select name="status" class="form-control" style="max-width: 180px;">
            <option value="">Tất cả trạng thái</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản (Published)</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
        </select>

        <button type="submit" class="btn btn-secondary btn-sm"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Lọc</button>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">Xóa lọc</a>
    </form>
</div>

{{-- Articles Table --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tiêu Đề / Slug</th>
                <th>Phân Loại</th>
                <th>Chuyên Mục</th>
                <th>Lượt Xem</th>
                <th>Trạng Thái</th>
                <th style="text-align: right;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $art)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); display: block;">{{ $art->title }}</strong>
                        <code style="font-size: 0.75rem; color: var(--accent-cyan);">/articles/{{ $art->slug }}</code>
                    </td>
                    <td>
                        <span class="badge {{ $art->type->badgeClass() }}">{{ $art->type->label() }}</span>
                    </td>
                    <td>
                        <span class="badge">{{ $art->category?->name ?? 'N/A' }}</span>
                    </td>
                    <td style="font-family: var(--font-mono); color: var(--text-main);">
                        {{ number_format($art->view_count) }}
                    </td>
                    <td>
                        <form action="{{ route('admin.articles.toggle', $art->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $art->status === 'published' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.25rem 0.65rem; font-size: 0.78rem;">
                                {{ $art->status === 'published' ? '● Đã Xuất Bản' : '○ Bản Nháp' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="Xem ngoài web">
                            <x-heroicon-o-eye style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                        </a>
                        <a href="{{ route('admin.articles.edit', $art->id) }}" class="btn btn-secondary btn-sm" title="Sửa bài">
                            <x-heroicon-o-pencil-square style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                        </a>
                        <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');" style="display: inline;">
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
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        Chưa có bài viết nào trong hệ thống. Hãy tạo bài mới hoặc dùng AI Studio để sinh bài!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $articles->links() }}
</div>
@endsection
