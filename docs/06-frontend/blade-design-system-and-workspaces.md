# 🎨 Frontend Architecture: Blade SSR, Design System & Interactive Workspaces

> **Tài liệu chuẩn Senior** về kiến trúc giao diện người dùng (Frontend Architecture), hệ thống Design System Tokens, Swiper Carousel, cơ chế xử lý tệp ảnh Base64 và bộ hiển thị đồ họa tương tác (Rich Visual Renderers).

---

## 📐 1. Triết Lý Thiết Kế & Công Nghệ

Frontend của **TechHub** được xây dựng dựa trên nguyên tắc:
1. **Server-Side Rendering (Blade SSR)**: Tối ưu SEO tuyệt đối, chỉ số First Contentful Paint (FCP) dưới 200ms, không có hiện tượng giật màn hình (No Layout Shift) hay độ trễ Hydration của SPA.
2. **Vanilla CSS Design System**: Tự xây dựng toàn bộ Design Tokens theo biến CSS (`:root`), không phụ thuộc TailwindCSS để đảm bảo kích thước file CSS cực nhẹ (< 25KB) và tùy biến linh hoạt.
3. **Vanilla JS Modular Architecture**: Xử lý tương tác AJAX mượt mà, hỗ trợ kéo thả file, tự động nạp dữ liệu mẫu, sao chép 1-click và render đồ họa thời gian thực.
4. **Chuẩn SEO 100%**: Tích hợp Schema.org JSON-LD, OpenGraph, Twitter Cards, Hreflang song ngữ và Dynamic XML Sitemap.

---

## 🎨 2. Hệ Thống Design Tokens (`public/css/techhub.css`)

Bảng màu được cấu hình với độ tương phản cao (**High Contrast Light Theme**), tối ưu cho lập trình viên làm việc ban ngày:

```css
:root {
  /* Surface Tokens */
  --bg-main: #f8fafc;                /* Nền tổng thể trang (Slate-50) */
  --bg-surface: #ffffff;             /* Nền thanh Header & Navbar */
  --bg-surface-elevated: #f1f5f9;    /* Nền thẻ phụ & khối công cụ */
  --bg-card: #ffffff;                /* Nền thẻ công cụ chính */
  --bg-input: #f8fafc;               /* Nền ô nhập liệu Textarea */

  /* Text Readability Tokens */
  --text-main: #0f172a;              /* Chữ chính (Slate-900: Đen xanh đậm, cực nét) */
  --text-sub: #475569;               /* Chữ phụ miêu tả (Slate-600) */
  --text-muted: #64748b;             /* Chữ chú thích, thời gian (Slate-500) */

  /* Accent & Brand Tokens */
  --accent-indigo: #4f46e5;          /* Màu chủ đạo Indigo */
  --accent-cyan: #0284c7;            /* Màu điểm nhấn Cyan */
  --accent-emerald: #059669;         /* Màu thành công / Đang bật */
  --accent-rose: #e11d48;            /* Màu cảnh báo / Lỗi */
  --accent-amber: #d97706;           /* Màu đánh giá sao */

  /* Gradients */
  --gradient-brand: linear-gradient(135deg, #4f46e5 0%, #0284c7 100%);
  --gradient-glow: linear-gradient(135deg, #2563eb 0%, #7c3aed 50%, #0284c7 100%);
}
```

---

## 🧩 3. Cấu Trúc Thư Mục Giao Diện (`resources/views/`)

```
resources/views/
├── layouts/
│   └── app.blade.php              # Layout chính của người dùng (SEO Meta, Header, Footer, Swiper CDN)
├── pages/
│   ├── home.blade.php             # Trang chủ (Hero, Swiper danh mục, Live filter, FAQ, Why choose us)
│   └── tools/
│       ├── index.blade.php        # Kho thư viện toàn bộ công cụ & bộ lọc
│       └── show.blade.php         # Không gian làm việc (Workspace) chuyên dụng cho từng công cụ
├── admin/                         # Giao diện Quản trị viên (/admin)
│   ├── layouts/app.blade.php      # Layout Sidebar & Navbar Admin
│   ├── auth/login.blade.php       # Trang đăng nhập bảo mật Admin
│   ├── dashboard/index.blade.php  # Dashboard thống kê KPI & Lịch sử Real-time
│   ├── users/index.blade.php      # Quản lý người dùng, phân quyền, khóa tài khoản
│   ├── tools/index.blade.php      # Quản lý bật/tắt công cụ, chỉnh sửa thông số
│   ├── ads/index.blade.php        # Quản lý Banner tài trợ & Google AdSense
│   └── settings/index.blade.php   # Quản lý Text động & Cấu hình hệ thống
└── components/                    # Các Blade Components tái sử dụng
```

---

## ⚡ 4. Cơ Chế Xử Lý Frontend Trong `public/js/techhub.js`

File `techhub.js` bao gồm 10 module chức năng chính:

1. **`initCategoriesSwiper()`**: Kích hoạt thanh trượt danh mục với cảm ứng vuốt chạm mượt mà, cuộn ngang bằng chuột (mousewheel), tự động căn giữa tab đang chọn.
2. **`initCategoryLiveFilter()`**: Lọc công cụ tức thì trên trang chủ mà không cần tải lại trang, cập nhật URL qua `history.pushState`.
3. **`initKeyboardShortcuts()`**: Phím tắt toàn cục `Ctrl + K` (hoặc `Cmd + K`) mở nhanh thanh tìm kiếm.
4. **`initCopyButtons()`**: Sao chép nội dung 1-click vào Clipboard kèm thông báo Toast Notification.
5. **`initDropzones()`**: Tự động chuyển đổi hình ảnh tải lên thành chuỗi `Base64 DataURL`, hiển thị ảnh xem trước thu nhỏ (Thumbnail), tên tệp và dung lượng KB/MB.
6. **`initSampleLoaders()`**: Nạp dữ liệu mẫu cho JSON, JWT, Regex, URL, Base64, Hash khi người dùng bấm nút 📋.
7. **`initToolForm()`**: Bắt sự kiện submit form, gửi AJAX POST đến `/api/tools/{slug}/execute`, hiển thị thời gian thực thi (ms).
8. **`renderRichOutput()`**: Bộ vẽ đồ họa chuyên biệt cho từng công cụ:
   * **Bảng màu ảnh (`image-color-extractor`)**: Hiển thị bảng mã màu kèm nút sao chép mã HEX, RGB, HSL.
   * **Thông số EXIF (`image-metadata-inspector`)**: Hiển thị bảng 4 thẻ KPI đo kích thước, tỷ lệ, dung lượng, MIME type.
   * **Khoản vay EMI (`loan-calculator`)**: Hiển thị 3 thẻ KPI tổng kết và bảng lịch trả nợ chi tiết từng tháng.
   * **Chỉ số BMI (`bmi-calculator`)**: Hiển thị điểm BMI, nhãn phân loại WHO và cân nặng lý tưởng.
   * **Token JWT (`jwt-debugger`)**: Hiển thị 3 khối màu trực quan (Header, Payload, Signature) và hạn dùng.
   * **Regex (`regex-tester`)**: Hiển thị số kết quả khớp, vị trí offset và các capture group.
   * **Mã băm (`hash-generator`)**: Hiển thị danh sách thẻ mã băm kèm nút copy từng mã.
