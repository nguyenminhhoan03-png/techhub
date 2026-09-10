# TÀI LIỆU TOÀN DIỆN: HỆ THỐNG TECHHUB WEBSITE BUILDER
*(Website Builder Features, Architecture, User Guide & Roadmap)*

---

## 1. TỔNG QUAN DỰ ÁN
**TechHub Website Builder** là nền tảng xây dựng website trực quan kéo thả (No-Code / Low-Code Visual Studio) được phát triển trên nền **Laravel 11 (Domain-Driven Design / Clean Architecture)** kết hợp với **GrapesJS Studio Engine**.

Nền tảng hướng tới mục tiêu:
1. Cho phép người dùng tạo và tùy biến website nhanh chóng như **WordPress (Elementor / Gutenberg)** hoặc **Webflow**.
2. Xuất bản tĩnh tốc độ cao (**Static Site Generation - SSG**) triển khai lên **AWS S3 / Cloudflare CDN**, đạt điểm số Google PageSpeed 95-100/100, bảo mật tuyệt đối không lo bị hack cơ sở dữ liệu.
3. Cô lập dữ liệu đa người dùng (**Multi-tenant Security**): mỗi tài khoản quản lý độc lập các website và trang của riêng mình.

---

## 2. BẢNG DANH MỤC TOÀN BỘ CHỨC NĂNG & HÀNH ĐỘNG HIỆN CÓ

| Nhóm Tính Năng | Hành Động / Chức Năng Cụ Thể | Trạng Thái | Mô Tả Chi Tiết |
| :--- | :--- | :---: | :--- |
| **Bố Cục Bản Vẽ (Canvas)** | **Bố Cục Chuẩn (Flow Layout)** | ✅ Hoàn thành | Mặc định chuẩn WordPress / Elementor. Các khối tự động bắt vào khung chứa/cột, đẩy nhau xuống, không bị đè chữ. |
| | **Vạch chỉ dẫn thả khối** | ✅ Hoàn thành | Vạch xanh phát sáng kèm huy hiệu `⬇ Thả khối vào đây` khi rê chuột kéo khối vào bản vẽ. |
| | **Kéo Tự Do (Free Absolute Drag)** | ✅ Hoàn thành | Chế độ kéo theo pixel tự do (cho nhãn dán, badge, sticker bay nổi). |
| | **✨ Căn Chuẩn Bố Cục (1-Click)** | ✅ Hoàn thành | Tự động quét và dọn dẹp các phần tử bị trôi tọa độ (`position: absolute`, `top`, `left`), đưa toàn bộ về dòng chảy chuẩn ngay ngắn. |
| **Thiết Bị & Responsive** | **Chế độ Desktop (1200px)** | ✅ Hoàn thành | Xem và căn chỉnh giao diện máy tính. |
| | **Chế độ Tablet (768px)** | ✅ Hoàn thành | Xem và căn chỉnh giao diện máy tính bảng. |
| | **Chế độ Mobile (375px)** | ✅ Hoàn thành | Xem và căn chỉnh giao diện điện thoại thông minh. |
| | **3 Tay cầm co giãn (Resize Handles)** | ✅ Hoàn thành | Kéo trực tiếp mép Trái, Phải, Đáy của bản vẽ để test kích thước tùy ý. |
| | **Huy hiệu kích thước thực (W x H px)** | ✅ Hoàn thành | Hiển thị real-time chiều rộng x chiều cao (vd: `1200 x 850px`). |
| **Điều Hướng Bản Vẽ (Pan & Zoom)** | **Thu phóng (Zoom Level)** | ✅ Hoàn thành | Hỗ trợ 50%, 66%, 75%, 90%, 100%, 125%, 150%, 200%. |
| | **Vừa màn hình (Fit to Screen)** | ✅ Hoàn thành | Tự động tính toán tỷ lệ zoom tối ưu vừa vặn cửa sổ trình duyệt. |
| | **Kéo bản vẽ qua lại (Pan Hand Tool)** | ✅ Hoàn thành | Giữ phím `Space` hoặc ấn chuột giữa / bật nút bàn tay để kéo rê canvas mượt mà như Figma. |
| | **Căn giữa lại (Reset Pan)** | ✅ Hoàn thành | Đưa canvas về ngay tâm màn hình chỉ với 1 nhấp. |
| **Kho Khối (Block Library)** | **Khối Bố Cục (Layout Blocks)** | ✅ Hoàn thành | Khung chứa (Container), Hàng 2 cột (50/50), Hàng 3 cột (33/33/33), Thẻ Card hiện đại. |
| | **Khối Nội Dung & Media** | ✅ Hoàn thành | Tiêu đề H1, Đoạn văn P, Nút chính (Primary), Nút viền (Outline), Ảnh (Image), Video. |
| | **Khối Dựng Sẵn (Prebuilt Sections)** | ✅ Hoàn thành | Header hiện đại, Hero công nghệ, Tính năng nổi bật (Features Grid), Bảng giá (Pricing Table), Đánh giá (Testimonials), Kêu gọi hành động (CTA Banner), Footer đen chuyên nghiệp. |
| **Thanh Inspector Thông Minh** | **Cấu hình Nút & Liên kết** | ✅ Hoàn thành | Đổi nhãn nút (Button text), **Dropdown chọn trang nội bộ trong website**, ô nhập Href, checkbox mở tab mới. |
| | **Cấu hình Hình ảnh (Image Setup)** | ✅ Hoàn thành | Nhập URL ảnh, **Tải ảnh trực tiếp từ máy tính lên Cloud**, chọn tỷ lệ hiển thị (Cover, Contain, Fill). |
| | **Cấu hình Video (Video Setup)** | ✅ Hoàn thành | Nhúng link YouTube / file MP4, Tự phát (Autoplay), Lặp lại (Loop), Nút điều khiển (Controls), Tắt tiếng (Muted), Ảnh bìa (Poster). |
| | **Sửa Chữ Trực Tiếp (Text Setup)** | ✅ Hoàn thành | Nhấp đúp hoặc dùng bút chì trên toolbar để gõ chữ trực tiếp trên canvas. |
| | **Mô hình khoảng cách (Box Model)** | ✅ Hoàn thành | Sơ đồ đồ họa trực quan Margin (4 hướng) và Padding (4 hướng) nhập số pixel trực tiếp. |
| | **Bảng Màu Nhanh 1-Chạm** | ✅ Hoàn thành | 10 màu phối sẵn hiện đại (Brand Blue, Violet, Emerald, Amber, Rose, Slate, Pure White...). Chọn tô Màu Chữ hoặc Màu Nền. |
| | **Hiệu Ứng Bo Góc & Bóng Đổ** | ✅ Hoàn thành | Bo góc 0, 6px, 12px, 20px, Pill. Bóng đổ: Tắt, Nhẹ, Nổi, Đậm, Hào quang xanh (Glow). Căn lề Trái, Giữa, Phải, 100%. |
| | **StyleManager Chuyên Sâu** | ✅ Hoàn thành | Đầy đủ thuộc tính CSS: Typography, Background, Border, Flexbox, Layout. |
| **Quản Lý Đa Trang (Pages)** | **Danh Sách Trang (Drawer)** | ✅ Hoàn thành | Xem toàn bộ các trang con thuộc website hiện tại. |
| | **Tạo Trang Mới (+ Tạo Trang)** | ✅ Hoàn thành | Modal nhập Tiêu đề trang và Đường dẫn URL (Slug). Validate chống trùng lặp. |
| | **Chuyển Đổi Trang Nhanh** | ✅ Hoàn thành | Dropdown switcher trên topbar chuyển qua lại giữa các trang đang thiết kế. |
| | **Đánh dấu Trang Chủ (Home)** | ✅ Hoàn thành | Gán cờ `is_home` (slug `/` hoặc `index.html`). |
| **Lưu & Phiên Bản** | **Tự Động Lưu (Autosave)** | ✅ Hoàn thành | Tự động lưu ngầm vào database sau khi người dùng dừng thao tác (debounce). |
| | **Lưu Nháp Thủ Công (Save Draft)** | ✅ Hoàn thành | Phím tắt `Ctrl + S` hoặc bấm nút "Lưu Nháp". |
| | **Optimistic Locking (Chống ghi đè)** | ✅ Hoàn thành | Quản lý bằng `version_number`, chống lỗi 2 người hoặc 2 tab ghi đè nhau. |
| | **Lịch Sử Bất Biến (Page Versions)** | ✅ Hoàn thành | Bảng `page_versions` lưu snapshot mỗi lần phát hành. |
| **Xem Trước & Xuất Bản** | **Xem Thử Trực Tiếp (Live Preview)** | ✅ Hoàn thành | Route `/builder/preview/{websiteId}/{slug?}` biên dịch HTML/CSS tức thì, hỗ trợ nhấp link chuyển qua lại giữa các trang con. |
| | **Xem Mã Nguồn (`</>`)** | ✅ Hoàn thành | Modal xem nhanh mã HTML & CSS đã biên dịch. |
| | **Xuất Bản Tĩnh Tốc Độ Cao (SSG)** | ✅ Hoàn thành | Biên dịch toàn bộ trang thành `index.html` và `{slug}.html`, minify CSS, upload lên AWS S3 / CDN. |
| | **Tự động sinh Sitemap & Robots** | ✅ Hoàn thành | Tự động sinh `sitemap.xml` và `robots.txt` chuẩn SEO Google đưa lên S3 khi xuất bản. |
| **Bảo Mật & Phân Quyền** | **Xác thực tài khoản (Auth Guard)** | ✅ Hoàn thành | Bắt buộc đăng nhập (`auth` middleware). |
| | **Cô lập dữ liệu người dùng (Data Isolation)**| ✅ Hoàn thành | Người dùng A không thể xem, sửa hoặc xuất bản website của người dùng B (403 Forbidden). |

---

## 3. HƯỚNG DẪN CHI TIẾT: LIÊN KẾT GIỮA CÁC TRANG (INTERNAL LINKING)

Khi xây dựng một website có nhiều trang (ví dụ: Trang chủ `/home`, trang Đăng ký `/dang-ky`, trang Bảng giá `/pricing`):

### 3.1. Phân biệt 3 loại liên kết trong Web Builder:
1. **Liên kết trang nội bộ (Internal Page Link)**:
   * **Cú pháp**: `/slug-trang` (ví dụ: `/dang-ky`, `/pricing`, `/`)
   * **Cách dùng**: Nhấp vào nút bấm hoặc thẻ liên kết > Ở bảng bên phải (Button & Link), chọn trang mong muốn tại ô **"Chọn trang trong website (Internal Page)"**. Hệ thống sẽ tự động điền `/dang-ky` vào ô Href.
   * Khi xuất bản SSG hoặc xem trong Live Preview: Trình duyệt sẽ chuyển hướng chính xác đến trang tương ứng (`dang-ky.html`).

2. **Liên kết neo cùng trang (Anchor Link)**:
   * **Cú pháp**: `#id-khoi` (ví dụ: `#features`, `#pricing`, `#contact`)
   * **Cách dùng**: Nhập dấu thăng `#` kèm ID của phần tử. Khi người dùng bấm nút, trang web sẽ cuộn mượt (smooth scroll) đến đúng vị trí khối đó trên cùng một trang.
   * *(Lưu ý: Bạn chỉ dùng `#dang-ky` nếu trên chính trang hiện tại có một phần tử mang `id="dang-ky"`).*

3. **Liên kết ngoài (External Link)**:
   * **Cú pháp**: `https://facebook.com/...`, `https://google.com`
   * **Cách dùng**: Nhập đầy đủ giao thức `https://`, đồng thời tích chọn ô **"Mở liên kết trong tab mới (_blank)"**.

---

## 4. QUẢN LÝ HEADER & FOOTER DÙNG CHUNG (GLOBAL COMPONENTS)

### 4.1. Vấn đề thực tế
Một website hoàn chỉnh thường có 3-10 trang. Người dùng mong muốn:
* Thanh điều hướng **Header** (Logo, Menu, Nút Đăng nhập/Liên hệ) và **Footer** (Bản quyền, Liên kết chân trang) hiển thị đồng bộ trên mọi trang.
* Khi đổi số điện thoại hoặc đổi logo ở Header trên 1 trang, tất cả các trang khác đều tự động cập nhật theo mà không cần mở từng trang ra sửa lại.

### 4.2. Cách làm hiện tại trên hệ thống
* Trong mục **⭐ Khối Dựng Sẵn (Templates)** ở thanh công cụ bên trái:
  * Khối **Header Hiện Đại** (`prebuilt-header-modern`)
  * Khối **Chân Trang** (`prebuilt-footer-dark`)
* Người dùng có thể kéo thả khối Header lên đầu trang và Footer xuống cuối trang cho mỗi trang mới tạo.

### 4.3. Giải pháp kiến trúc chuẩn nâng cao (Đề xuất triển khai - Master Layout System)
Tương tự như **Theme Builder** của WordPress Elementor hoặc **Symbols** của Webflow, kiến trúc tối ưu cho TechHub Web Builder sẽ gồm 2 bước:

1. **Ở Cơ sở dữ liệu (Database)**:
   * Bổ sung trường `global_header_content` (JSON AST) và `global_footer_content` (JSON AST) vào bảng `websites` hoặc `website_settings`.
   * Thêm tùy chọn `use_global_header` (boolean, default true) và `use_global_footer` (boolean, default true) vào bảng `pages` (cho phép các trang Landing Page đặc biệt có thể ẩn Header/Footer nếu muốn).

2. **Ở Trình biên dịch (StaticSiteCompilerService)**:
   * Khi người dùng xuất bản hoặc xem trước bất kỳ trang nào, nếu trang đó bật `use_global_header`:
     * Compiler tự động chèn HTML của `global_header_content` vào vị trí đầu thẻ `<body>`.
     * Tự động chèn HTML của `global_footer_content` vào trước khi đóng thẻ `</body>`.
   * **Kết quả**: Người dùng chỉ cần thiết kế Header và Footer một lần duy nhất tại phần Cài đặt Website (hoặc từ bất kỳ trang nào có nút "Lưu làm Header chung"), mọi trang con khác sẽ tự động có chung Header và Footer đồng bộ 100%!

---

## 5. ĐÁNH GIÁ SO SÁNH VỚI WORDPRESS / ELEMENTOR

| Tiêu Chí | WordPress + Elementor | TechHub Website Builder (Hiện Tại) | Đánh Giá & Hướng Nâng Cấp |
| :--- | :--- | :--- | :--- |
| **Bố cục kéo thả** | Flexbox Container / Inner Section | Flow Container + Free Drag Toggle + 1-Click Auto Clean | TechHub tương đương về độ dễ thao tác, có thêm chế độ Free drag linh hoạt. |
| **Tốc độ tải trang** | Trung bình (PHP + MySQL runtime, nhiều plugin nặng) | Cực nhanh (100% Static HTML/CSS xuất bản S3/CDN) | **TechHub vượt trội**, điểm Google Speed 95-100. |
| **Bảo mật** | Thường xuyên bị tấn công SQL Injection, Brute force WP-Admin | Bảo mật tuyệt đối (Website chạy static không có DB ở frontend) | **TechHub an toàn tuyệt đối**. |
| **Menu điều hướng** | Quản lý qua Appearance > Menus | Nhập trực tiếp vào các thẻ link trên Header | TechHub cần thêm trình quản lý Menu chung cấp website. |
| **Quản lý Header/Footer** | Elementor Theme Builder | Kéo thả khối dựng sẵn trên từng trang | TechHub cần triển khai Master Header/Footer tự động như mục 4.3. |
| **Form liên hệ** | Contact Form 7 / Elementor Form | Form tĩnh cơ bản | TechHub cần bổ sung Form Submission API để lưu lead khách hàng. |

---

## 6. LỘ TRÌNH CÁC TÍNH NĂNG CẦN BỔ SUNG (ROADMAP)

Dưới đây là danh sách các tính năng được xếp theo mức độ ưu tiên để hoàn thiện hệ thống thành một nền tảng Web Builder toàn diện:

### 🔴 Ưu tiên 1 (Thiết yếu cho website hoàn chỉnh - Giai đoạn kế tiếp):
1. **Hệ Thống Master Header & Footer Dùng Chung**:
   * Thiết kế 1 lần ở cấp Website, tự động nhúng vào đầu và cuối tất cả các trang con khi xem thử và xuất bản.
2. **Bộ Xử Lý Form Liên Hệ & Thu Thập Khách Hàng (Form Submissions Engine)**:
   * Tạo bảng `website_form_submissions` (id, website_id, form_name, payload_json, ip_address, status).
   * Cung cấp API endpoint `/api/builder/forms/submit` để các form Đăng ký / Liên hệ trên web tĩnh gửi dữ liệu về.
   * Thông báo email tức thì cho chủ website khi có khách để lại thông tin.
3. **Form SEO Chi Tiết Từng Trang Con (Page-Level SEO)**:
   * Mở rộng bảng `pages`: thêm `meta_title`, `meta_description`, `og_image_url`, `canonical_url` riêng cho từng trang con (hiện tại mới có SEO cấp Website).

### 🟡 Ưu tiên 2 (Tăng trải nghiệm và chuyên nghiệp):
4. **Trình Quản Lý Menu Điều Hướng (Navigation Menu Builder)**:
   * Cho phép tạo và sắp xếp danh sách menu (kèm menu con dropdown) ở cấp Website.
5. **Gắn Tên Miền Riêng (Custom Domain DNS Manager)**:
   * Kết nối Cloudflare for SaaS hoặc kiểm tra bản ghi CNAME / A record để khách hàng dùng tên miền riêng (vd: `congtyabc.com`).
6. **Kho Giao Diện Toàn Trang (Full Site Templates)**:
   * Cung cấp các bộ mẫu hoàn chỉnh gồm 3-5 trang (Trang chủ, Giới thiệu, Dịch vụ, Bảng giá, Liên hệ) chỉ cần bấm 1 nút là nhân bản toàn bộ.
7. **Lịch Sử & Khôi Phục Phiên Bản (Version History Rollback UI)**:
   * Giao diện xem lại các phiên bản đã xuất bản trước đây và nút bấm "Khôi phục phiên bản này" (Rollback).

### 🟢 Ưu tiên 3 (Tối ưu nâng cao):
8. **Công Cụ Cắt & Nén Ảnh (Image Cropper & WebP Auto-Converter)**:
   * Tự động chuyển đổi ảnh tải lên thành định dạng WebP siêu nhẹ và hỗ trợ cắt/xoay ảnh trực tiếp trên trình duyệt.
9. **Kho Khối UI Bổ Sung (Advanced Blocks)**:
   * Accordion (Hỏi đáp FAQ xổ xuống), Tab chuyển nội dung, Slider băng chuyền (Carousel), Bảng so sánh (Comparison Table).
