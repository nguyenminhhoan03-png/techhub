@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Bài Viết')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>Chỉnh Sửa <span class="gradient-text">Bài Viết</span></h1>
        <p style="margin-top: 0.25rem;">Cập nhật nội dung, tối ưu thẻ SEO và trạng thái hiển thị.</p>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">← Quay Lại</a>
</div>

<div class="tool-panel" style="padding: 2rem;">
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Tiêu đề bài viết (Title)</label>
            <input type="text" name="title" class="form-control" value="{{ $article->title }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label class="form-label">Loại bài viết</label>
                <select name="type" class="form-control" required>
                    @foreach($types as $type)
                        <option value="{{ $type->value }}" {{ $article->type === $type ? 'selected' : '' }}>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Chuyên mục</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $article->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Trạng thái xuất bản</label>
                <select name="status" class="form-control" required>
                    <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Đã xuất bản (Published)</option>
                    <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Đường dẫn ảnh đại diện (Featured Image URL)</label>
            <input type="url" name="featured_image_url" class="form-control" value="{{ $article->featured_image_url }}">
        </div>

        <div class="form-group">
            <label class="form-label">Tóm tắt ngắn (Excerpt)</label>
            <textarea name="excerpt" class="form-control" style="min-height: 70px;" required>{{ $article->excerpt }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Nội dung bài viết (Markdown Body)</label>
            <textarea name="content_markdown" class="form-control" style="min-height: 380px; font-family: var(--font-mono); font-size: 0.9rem;" required>{{ $article->content_markdown }}</textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
        </div>
    </form>
</div>
@endsection
