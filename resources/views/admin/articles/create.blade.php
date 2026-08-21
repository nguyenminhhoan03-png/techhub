@extends('admin.layouts.app')

@section('title', 'Tạo Bài Viết Mới')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>Tạo <span class="gradient-text">Bài Viết Mới</span></h1>
        <p style="margin-top: 0.25rem;">Soạn thảo nội dung đánh giá công nghệ hoặc hướng dẫn chuẩn SEO.</p>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">← Quay Lại</a>
</div>

<div class="tool-panel" style="padding: 2rem;">
    <form action="{{ route('admin.articles.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Tiêu đề bài viết (Title)</label>
            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Đánh Giá Chi Tiết RTX 5070: Quái Vật 1440p Mới..." required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label class="form-label">Loại bài viết</label>
                <select name="type" class="form-control" required>
                    @foreach($types as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Chuyên mục</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Trạng thái xuất bản</label>
                <select name="status" class="form-control" required>
                    <option value="published">Xuất bản ngay (Published)</option>
                    <option value="draft">Lưu bản nháp (Draft)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Đường dẫn ảnh đại diện (Featured Image URL)</label>
            <input type="url" name="featured_image_url" class="form-control" placeholder="https://images.unsplash.com/photo-...">
        </div>

        <div class="form-group">
            <label class="form-label">Tóm tắt ngắn (Excerpt - Hiển thị trên thẻ xem trước)</label>
            <textarea name="excerpt" class="form-control" style="min-height: 70px;" placeholder="Đoạn tóm tắt thu hút người đọc..." required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Nội dung bài viết (Markdown Body)</label>
            <textarea name="content_markdown" class="form-control" style="min-height: 320px; font-family: var(--font-mono); font-size: 0.9rem;" placeholder="## 1. Giới Thiệu...&#10;&#10;Nội dung bài viết dạng Markdown..." required></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu &amp; Xuất Bản Bài Viết</button>
        </div>
    </form>
</div>
@endsection
