@extends('admin.layouts.app')

@section('title', 'Quản Lý Quảng Cáo & Banner')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Quản Lý <span class="gradient-text">Quảng Cáo &amp; Banner Động</span></h1>
        <p style="margin-top: 0.25rem;">Cài đặt banner tài trợ, mã Google AdSense, Affiliate link hiển thị linh hoạt tại các vị trí trên website.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('modal-create-ad').style.display='flex'">
        + Thêm Banner / Quảng Cáo Mới
    </button>
</div>

{{-- Slot Previews Info --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="tool-card" style="padding: 1.2rem;">
        <span class="badge" style="margin-bottom: 0.5rem;">Vị Trí #1</span>
        <strong style="color: var(--text-main); display: block;">header_top</strong>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Đầu trang trên thanh Header (728x90)</span>
    </div>
    <div class="tool-card" style="padding: 1.2rem;">
        <span class="badge" style="margin-bottom: 0.5rem;">Vị Trí #2</span>
        <strong style="color: var(--text-main); display: block;">tool_workspace_bottom</strong>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Dưới khung thực thi công cụ (728x90)</span>
    </div>
    <div class="tool-card" style="padding: 1.2rem;">
        <span class="badge" style="margin-bottom: 0.5rem;">Vị Trí #3</span>
        <strong style="color: var(--text-main); display: block;">sidebar_right</strong>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Cột bên phải (300x250)</span>
    </div>
    <div class="tool-card" style="padding: 1.2rem;">
        <span class="badge" style="margin-bottom: 0.5rem;">Vị Trí #4</span>
        <strong style="color: var(--text-main); display: block;">footer_banner</strong>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Trước chân trang Footer</span>
    </div>
</div>

{{-- Ads Table --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tên Quảng Cáo</th>
                <th>Vị Trí (Slot)</th>
                <th>Loại</th>
                <th>Lượt Hiển Thị / Click</th>
                <th>Trạng Thái</th>
                <th style="text-align: right;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ads as $ad)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); display: block;">{{ $ad->name }}</strong>
                        @if($ad->target_url)
                            <a href="{{ $ad->target_url }}" target="_blank" style="font-size: 0.75rem; color: var(--accent-cyan);">
                                {{ Str::limit($ad->target_url, 40) }} ↗
                            </a>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="font-family: var(--font-mono); color: var(--accent-indigo);">
                            {{ $ad->slot }}
                        </span>
                    </td>
                    <td>
                        <span class="badge">{{ $ad->type }}</span>
                    </td>
                    <td style="font-family: var(--font-mono); font-size: 0.85rem;">
                        <x-heroicon-o-eye style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ number_format($ad->impressions_count) }} &bull; <x-heroicon-o-cursor-arrow-rays style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ number_format($ad->clicks_count) }}
                    </td>
                    <td>
                        <form action="{{ route('admin.ads.toggle', $ad->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $ad->is_active ? 'btn-primary' : 'btn-danger' }}" style="padding: 0.25rem 0.65rem; font-size: 0.78rem;">
                                {{ $ad->is_active ? '● Đang Chạy' : '○ Tạm Dừng' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa quảng cáo này?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><x-heroicon-o-trash style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        Chưa có banner quảng cáo nào được thiết lập.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal: Create Ad --}}
<div id="modal-create-ad" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 200; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="tool-panel" style="width: 100%; max-width: 580px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="color: var(--text-main);">Thêm Quảng Cáo / Banner Mới</h3>
            <button type="button" onclick="document.getElementById('modal-create-ad').style.display='none'" class="btn btn-secondary btn-sm">✕</button>
        </div>
        <form action="{{ route('admin.ads.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên Quảng Cáo / Chiến dịch</label>
                <input type="text" name="name" class="form-control" placeholder="Ví dụ: Banner Tài Trợ Header" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">Vị trí hiển thị (Slot)</label>
                    <select name="slot" class="form-control" required>
                        <option value="header_top">header_top (Đầu trang)</option>
                        <option value="tool_workspace_bottom">tool_workspace_bottom (Dưới tool)</option>
                        <option value="sidebar_right">sidebar_right (Cột phải)</option>
                        <option value="footer_banner">footer_banner (Chân trang)</option>
                        <option value="in_content">in_content (Giữa bài viết)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Loại quảng cáo</label>
                    <select name="type" class="form-control" required>
                        <option value="custom_banner">Banner Tự Chọn (Image + Link)</option>
                        <option value="adsense_html">Mã Google AdSense / HTML</option>
                        <option value="affiliate">Tiếp Thị Liên Kết (Affiliate)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Đường dẫn ảnh Banner (Image URL)</label>
                <input type="url" name="image_url" class="form-control" placeholder="https://example.com/banner-728x90.jpg">
            </div>

            <div class="form-group">
                <label class="form-label">Đường dẫn đích khi nhấp (Target URL)</label>
                <input type="url" name="target_url" class="form-control" placeholder="https://affiliate.example.com/click?id=123">
            </div>

            <div class="form-group">
                <label class="form-label">Mã HTML / Script Google AdSense (Nếu dùng)</label>
                <textarea name="raw_html" class="form-control" style="min-height: 90px;" placeholder="<script async src='https://pagead2.googlesyndication.com...'></script>"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('modal-create-ad').style.display='none'" class="btn btn-secondary btn-sm">Hủy</button>
                <button type="submit" class="btn btn-primary btn-sm">Lưu Quảng Cáo</button>
            </div>
        </form>
    </div>
</div>

@endsection
