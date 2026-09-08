@extends('admin.layouts.app')

@section('title', 'Đăng Sản Phẩm / Gói Tài Khoản Mới')

@section('content')
<div style="max-width: 980px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('admin.deals.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                ← Quay lại danh sách
            </a>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">
                ➕ Đăng Sản Phẩm / Gói Tài Khoản Mới
            </h1>
        </div>
    </div>

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 1rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.deals.store') }}" method="POST">
        @csrf

        {{-- ── 1. Thông Tin Cơ Bản ── --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>📦</span> <span>1. Thông Tin Cơ Bản</span>
            </h3>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Tên sản phẩm / Gói dịch vụ <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="name" id="deal-name" value="{{ old('name') }}" required
                           placeholder="Ví dụ: Gemini Pro + 5TB + Antigravity - Hạn 1 năm | Nâng chính chủ riêng tư"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Danh mục <span style="color: red;">*</span>
                    </label>
                    <select name="category" required style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Đường dẫn tĩnh (Slug) - <em>Để trống sẽ tự tạo</em>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="gemini-pro-5tb-antigravity-1-nam"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Link Ảnh Đại Diện (Thumbnail URL)
                    </label>
                    <input type="text" name="thumbnail_url" value="{{ old('thumbnail_url', '/images/deals/gemini-pro-5tb.png') }}"
                           placeholder="https://... hoặc /images/deals/..."
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Nhãn Badge chính
                    </label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', 'KHÔNG TRÙNG') }}" placeholder="KHÔNG TRÙNG / HOT"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Nhãn phụ (Hãng/Loại)
                    </label>
                    <input type="text" name="sub_badge" value="{{ old('sub_badge', 'GOOGLE') }}" placeholder="GOOGLE / OPENAI"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Tags từ khóa (cách nhau bởi dấu phẩy)
                    </label>
                    <input type="text" name="tags_raw" value="{{ old('tags_raw', 'google one pro, antigravity pro, gemini pro') }}"
                           placeholder="google one pro, antigravity pro, gemini pro"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>
            </div>
        </div>

        {{-- ── 2. Giá Tiền & Các Gói Phân Loại (Variants) ── --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>💰</span> <span>2. Giá Tiền &amp; Các Gói Phân Loại (Variants)</span>
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Giá hiển thị từ (VNĐ) <span style="color: red;">*</span>
                    </label>
                    <input type="number" name="price" id="main-price" value="{{ old('price', 50000) }}" required min="0" step="1000"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--accent-emerald); font-weight: 800; font-size: 1rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Giá gốc gạch đi (VNĐ)
                    </label>
                    <input type="number" name="original_price" value="{{ old('original_price', 500000) }}" min="0" step="1000"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-muted); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        % Giảm giá
                    </label>
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', 90) }}" min="0" max="100"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Tình trạng kho hàng
                    </label>
                    <select name="stock_status" style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                        <option value="in_stock" selected>Có sẵn (Giao ngay)</option>
                        <option value="out_of_stock">Hết hàng</option>
                        <option value="pre_order">Đặt trước (Pre-order)</option>
                    </select>
                </div>
            </div>

            {{-- Dynamic Variants Builder --}}
            <div style="border-top: 1px dashed var(--border-subtle); padding-top: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
                    <div>
                        <label style="font-size: 0.88rem; font-weight: 700; color: var(--text-main);">
                            Phân loại gói chọn mua (Tùy chọn Variant):
                        </label>
                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.15rem;">
                            Khách hàng sẽ bấm chọn gói để xem giá tương ứng (ví dụ 1 slot mail khách hoặc Cấp acc fam 5 slot).
                        </p>
                    </div>
                    <button type="button" onclick="addVariantRow()" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                        ➕ Thêm Gói Tùy Chọn
                    </button>
                </div>

                <div id="variants-list" style="display: flex; flex-direction: column; gap: 0.65rem;">
                    {{-- Row 1 --}}
                    <div class="variant-row" style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 0.75rem; align-items: center; background: var(--bg-surface-elevated); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                        <input type="text" name="variants[0][name]" value="1 slot mail khách - add family 12 tháng" placeholder="Tên gói (Ví dụ: 1 slot mail khách 12 tháng)"
                               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-main); font-size: 0.85rem;">
                        <input type="number" name="variants[0][price]" value="50000" placeholder="Giá (VNĐ)" step="1000"
                               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--accent-emerald); font-weight: 700; font-size: 0.85rem;">
                        <button type="button" onclick="this.closest('.variant-row').remove()" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">
                            ✕
                        </button>
                    </div>

                    {{-- Row 2 --}}
                    <div class="variant-row" style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 0.75rem; align-items: center; background: var(--bg-surface-elevated); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                        <input type="text" name="variants[1][name]" value="Cấp acc chính chủ fam 12 tháng - thêm được 5 slot" placeholder="Tên gói (Ví dụ: Cấp acc chính chủ fam)"
                               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-main); font-size: 0.85rem;">
                        <input type="number" name="variants[1][price]" value="139000" placeholder="Giá (VNĐ)" step="1000"
                               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--accent-emerald); font-weight: 700; font-size: 0.85rem;">
                        <button type="button" onclick="this.closest('.variant-row').remove()" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">
                            ✕
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 3. Thông Tin Liên Hệ & Chốt Đơn ── --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>💬</span> <span>3. Thông Tin Liên Hệ Nhận Đơn (Zalo &amp; Telegram)</span>
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Số điện thoại Zalo nhận đơn
                    </label>
                    <input type="text" name="zalo_contact" value="{{ old('zalo_contact', '0866655803') }}" placeholder="0866655803"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                    <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                        Khách bấm nút sẽ tự mở cửa sổ chat Zalo trực tiếp với bạn.
                    </small>
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                        Link Telegram nhận đơn
                    </label>
                    <input type="text" name="telegram_contact" value="{{ old('telegram_contact', 'https://t.me/hoannm') }}" placeholder="https://t.me/hoannm"
                           style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem;">
                    <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                        Khách bấm nút sẽ chuyển thẳng tới tài khoản Telegram của bạn.
                    </small>
                </div>
            </div>
        </div>

        {{-- ── 4. Mô Tả & Nội Dung Chi Tiết ── --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>📝</span> <span>4. Mô Tả &amp; Hướng Dẫn Kích Hoạt</span>
            </h3>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                    Tóm tắt ngắn (Hiện ở thẻ card danh mục)
                </label>
                <textarea name="summary" rows="2" placeholder="Nâng cấp tài khoản Google AI Pro (Gemini Advanced 5TB Cloud Storage + Antigravity) bản quyền 1 năm chính chủ..."
                          style="width: 100%; padding: 0.65rem 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.88rem;">{{ old('summary', 'Nâng cấp tài khoản Google AI Pro (Gemini Advanced + 5TB Google Drive + Antigravity) hạn 1 năm. Kích hoạt trực tiếp trên email chính chủ, riêng tư bảo mật dữ liệu tuyệt đối.') }}</textarea>
            </div>

            <div>
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.4rem;">
                    Mô tả chi tiết &amp; Hướng dẫn (Hỗ trợ Markdown)
                </label>
                <textarea name="description_markdown" rows="10" placeholder="Chi tiết tính năng, quyền lợi bản quyền, chính sách bảo hành..."
                          style="width: 100%; padding: 0.85rem; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-main); font-family: monospace; font-size: 0.88rem; line-height: 1.6;">{{ old('description_markdown', "## 🌟 Quyền Lợi Bản Quyền Đi Kèm:\n- **Gemini Advanced:** Sử dụng mô hình AI thông minh nhất thế giới của Google.\n- **Dung lượng 5TB Google Drive:** Lưu trữ dữ liệu thả ga, backup source code.\n- **Hỗ trợ Antigravity / Google AI Studio:** Mở khóa toàn bộ tính năng developer.\n- **Chính chủ 100%:** Nâng cấp trực tiếp trên email cá nhân của bạn.\n\n## 📋 Quy Trình Kích Hoạt & Bảo Hành\n- Nhắn tin qua Zalo hoặc Telegram để gửi email cần nâng cấp.\n- Kích hoạt xong trong 5 - 15 phút.\n- Bảo hành trọn gói 12 tháng 1-đổi-1.") }}</textarea>
            </div>
        </div>

        {{-- ── 5. Cài Đặt Trạng Thái & Nút Lưu ── --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.88rem; font-weight: 700; color: var(--text-main); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <span>Bật hiển thị sản phẩm</span>
                </label>

                <label style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.88rem; font-weight: 700; color: var(--text-main); cursor: pointer;">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <span>Sản phẩm nổi bật (Featured)</span>
                </label>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.deals.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.75rem; font-weight: 700;">
                    💾 Lưu &amp; Đăng Sản Phẩm
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let variantCount = 2;

function addVariantRow() {
    const list = document.getElementById('variants-list');
    const row = document.createElement('div');
    row.className = 'variant-row';
    row.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr auto; gap: 0.75rem; align-items: center; background: var(--bg-surface-elevated); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);';
    row.innerHTML = `
        <input type="text" name="variants[${variantCount}][name]" placeholder="Tên gói (Ví dụ: Gói 6 tháng)"
               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--text-main); font-size: 0.85rem;">
        <input type="number" name="variants[${variantCount}][price]" placeholder="Giá (VNĐ)" step="1000"
               style="padding: 0.5rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 4px; color: var(--accent-emerald); font-weight: 700; font-size: 0.85rem;">
        <button type="button" onclick="this.closest('.variant-row').remove()" class="btn btn-danger btn-sm" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">
            ✕
        </button>
    `;
    list.appendChild(row);
    variantCount++;
}
</script>
@endsection
