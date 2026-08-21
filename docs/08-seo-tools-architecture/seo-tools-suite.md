# Kiến Trúc Bộ Công Cụ Tối Ưu SEO Onpage (SEO Tools Suite)

## 1. Giới Thiệu Tổng Quan

Bộ công cụ **SEO Tools Suite** trong TechHub được xây dựng nhằm cung cấp các tiện ích trực tuyến chuyên sâu, hỗ trợ Webmasters, SEO Specialists và Developers phân tích, tối ưu hóa các yếu tố Onpage chuẩn xác theo các nguyên tắc khắt khe nhất của Google Search Central.

Bộ công cụ bao gồm **7 công cụ độc lập**, được thiết kế theo chuẩn **Domain-Driven Design (DDD)** với độ trễ xử lý dưới 5ms:

| Slug | Tên Công Cụ (Tiếng Việt) | Chức Năng Chính | Chuẩn Kỹ Thuật |
|---|---|---|---|
| `serp-preview` | **Mô Phỏng Hiển Thị Google SERP Snippet** | Đo độ rộng Pixel tiêu chuẩn (600px Desktop, 960px Mobile) và mô phỏng giao diện Google SERP | Google SERP Pixel Width Algorithm |
| `meta-tag-generator` | **Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage** | Sinh thẻ Title, Meta Description, Canonical, Robots, Keywords và phân tích Onpage | HTML5 & W3C Recommendations |
| `schema-generator` | **Tạo Schema Markup (JSON-LD) Chuẩn Google** | Tạo mã dữ liệu có cấu trúc Schema.org cho Article, FAQPage, Product, LocalBusiness, Breadcrumbs | Schema.org & Google Rich Results |
| `open-graph-generator` | **Tạo Thẻ Open Graph & Twitter Cards** | Tối ưu hóa thumbnail, tiêu đề, mô tả khi chia sẻ link trên Facebook, Twitter/X, Zalo, LinkedIn | Open Graph Protocol & Twitter Card Specs |
| `robots-txt-generator` | **Tạo & Kiểm Tra Tệp Robots.txt** | Thiết lập quyền thu thập dữ liệu bot tìm kiếm, chặn AI bots (GPTBot, CCBot), kiểm tra cú pháp | RFC 9309 Robots Exclusion Standard |
| `sitemap-generator` | **Tạo Sơ Đồ Trang Web XML Sitemap** | Sinh tệp XML Sitemap hợp lệ với thuộc tính Priority, Changefreq, Lastmod và kiểm tra cú pháp | Sitemaps.org Protocol v0.9 |
| `slug-generator` | **Tạo URL Slug Chuẩn SEO (Lọc Stop Words)** | Chuyển đổi tiếng Việt có dấu, loại bỏ Stop Words dư thừa, đánh giá độ chuẩn SEO URL | RFC 3986 URI Specification |

---

## 2. Chi Tiết Thuật Toán & Kiến Trúc Từng Công Cụ

### 2.1. Google SERP Snippet Preview (`SerpPreviewTool.php`)
* **Thuật toán đo độ rộng Pixel**: Google cắt ngắn tiêu đề (Title) dựa trên độ rộng pixel thực tế (~600px trên Desktop với font Arial 20px) chứ không chỉ dựa trên số ký tự thuần túy.
* Thuật toán sử dụng bảng tra cứu trọng số tỷ lệ ký tự (Character Width Proportional Weight Table) để tính toán chính xác độ rộng pixel và hiển thị thanh đo trực quan:
  * Xanh lá (Green): 200px - 580px (Độ dài hoàn hảo).
  * Vàng (Yellow): < 200px (Quá ngắn) hoặc 581px - 600px (Sắp bị cắt).
  * Đỏ (Red): > 600px (Google sẽ cắt ngắn bằng dấu `...`).

### 2.2. Schema Markup JSON-LD Generator (`SchemaGeneratorTool.php`)
* Khởi tạo các Type dữ liệu có cấu trúc được Google ưu tiên hiển thị Rich Results:
  * `Article` / `NewsArticle`: Đính kèm tác giả, ngày đăng, nhà xuất bản và ảnh đại diện.
  * `FAQPage`: Đính kèm mảng Question - Answer để kích hoạt accordion FAQ trên trang tìm kiếm.
  * `Product`: Đính kèm giá tiền, đơn vị tiền tệ, tình trạng hàng (InStock), đánh giá sao (AggregateRating).
  * `LocalBusiness`: Đính kèm địa chỉ, số điện thoại, giờ mở cửa.
  * `BreadcrumbList`: Định dạng cấu trúc phân cấp danh mục.

### 2.3. Stop-Word Filtered Slug Generator (`SlugGeneratorTool.php`)
* Bộ lọc từ dừng (Vietnamese Stop Words Filter) tích hợp sẵn từ điển hơn 100 từ dừng thông dụng (như: *và, là, của, những, các, để, với, trong, trên, một, có, được...*).
* Loại bỏ từ dừng giúp URL ngắn gọn, thân thiện với bot tìm kiếm và tập trung mật độ từ khóa mục tiêu.

---

## 3. Quy Trình Mở Rộng & Bổ Sung Công Cụ Mới

Để thêm một công cụ mới vào bộ SEO Tools:
1. Tạo Domain Class tại `src/Domain/Tool/Tools/Seo/NewSeoTool.php` thực thi `ToolContract`.
2. Khai báo form giao diện tại `resources/views/pages/tools/show.blade.php`.
3. Khai báo hàm JavaScript xử lý tương tác tại `public/js/techhub.js`.
4. Đăng ký thông tin trong `database/seeders/ToolSeeder.php` và chạy `php artisan db:seed --class=ToolSeeder`.
