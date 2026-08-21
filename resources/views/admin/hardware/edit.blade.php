@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Thiết Bị Phần Cứng')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>Chỉnh Sửa <span class="gradient-text">Thiết Bị / Linh Kiện</span></h1>
        <p style="margin-top: 0.25rem;">Cập nhật thông số và điểm số benchmark cho {{ $product->full_name }}.</p>
    </div>
    <a href="{{ route('admin.hardware.index') }}" class="btn btn-secondary btn-sm">← Quay Lại</a>
</div>

<div class="tool-panel" style="padding: 2rem;">
    <form action="{{ route('admin.hardware.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label class="form-label">Tên đầy đủ (Full Name)</label>
                <input type="text" name="full_name" class="form-control" value="{{ $product->full_name }}" required>
            </div>
            <div>
                <label class="form-label">Tên Model ngắn (Model Name)</label>
                <input type="text" name="model_name" class="form-control" value="{{ $product->model_name }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label class="form-label">Phân loại linh kiện</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Thương hiệu (Brand)</label>
                <select name="brand_id" class="form-control" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $product->brand_id === $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Giá khởi điểm (USD MSRP)</label>
                <input type="number" name="launch_msrp_usd" class="form-control" value="{{ $product->launch_msrp_usd }}" step="0.01">
            </div>
            <div>
                <label class="form-label">Ngày ra mắt (Release Date)</label>
                <input type="date" name="release_date" class="form-control" value="{{ $product->release_date ? $product->release_date->format('Y-m-d') : '' }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Đường dẫn ảnh Thumbnail (Image URL)</label>
            <input type="url" name="thumbnail_url" class="form-control" value="{{ $product->thumbnail_url }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem; background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
            <div>
                <label class="form-label">Điểm Tổng Thể (0 - 10)</label>
                <input type="number" name="overall_score" class="form-control" value="{{ $product->overall_score }}" step="0.1" min="0" max="10" required>
            </div>
            <div>
                <label class="form-label">Điểm Chơi Game (Gaming 0 - 10)</label>
                <input type="number" name="gaming_score" class="form-control" value="{{ $product->gaming_score }}" step="0.1" min="0" max="10" required>
            </div>
            <div>
                <label class="form-label">Điểm Năng Suất (Render 0 - 10)</label>
                <input type="number" name="productivity_score" class="form-control" value="{{ $product->productivity_score }}" step="0.1" min="0" max="10" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Thông số kỹ thuật chi tiết (Specs JSON format)</label>
            <textarea name="specs_raw" class="form-control" style="min-height: 160px; font-family: var(--font-mono); font-size: 0.85rem;">{{ json_encode($product->specs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <a href="{{ route('admin.hardware.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
        </div>
    </form>
</div>
@endsection
