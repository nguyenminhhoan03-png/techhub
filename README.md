# 🚀 TechHub — Developer Utilities, AI Content Engine & Gaming Portal

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Nền tảng dịch vụ tiện ích trực tuyến, studio sáng tạo nội dung AI và so sánh phần cứng thế hệ mới</strong><br>
  Xây dựng trên nền <strong>Laravel 12</strong>, kiến trúc <strong>Clean Architecture</strong>, <strong>Domain-Driven Design (DDD)</strong> và <strong>CQRS</strong>.
</p>

<p align="center">
  <a href="#-kiến-trúc--công-nghệ-cốt-lõi"><img src="https://img.shields.io/badge/PHP-8.3%20%7C%208.4-777bb4.svg?style=flat-square&logo=php" alt="PHP Version"></a>
  <a href="#-kiến-trúc--công-nghệ-cốt-lõi"><img src="https://img.shields.io/badge/Laravel-12.x-ff2d20.svg?style=flat-square&logo=laravel" alt="Laravel Version"></a>
  <a href="docs/02-architecture/clean-architecture-ddd-cqrs.md"><img src="https://img.shields.io/badge/Architecture-Clean%20%2F%20DDD%20%2F%20CQRS-indigo.svg?style=flat-square" alt="Clean Architecture"></a>
  <a href="docs/05-performance-and-quality/coding-standards-and-quality.md"><img src="https://img.shields.io/badge/PHPStan-Level%205+-blue.svg?style=flat-square" alt="PHPStan"></a>
  <a href="docs/05-performance-and-quality/coding-standards-and-quality.md"><img src="https://img.shields.io/badge/Pest-Architecture%20Tests-green.svg?style=flat-square" alt="Pest"></a>
</p>

---

## 🌟 Tổng Quan Dự Án

**TechHub** là giải pháp web ứng dụng cấp doanh nghiệp kết hợp giữa bộ công cụ tiện ích trực tuyến cho lập trình viên/chuyên viên SEO, cổng game giải trí HTML5, và hệ thống tổng hợp thông số so sánh phần cứng tự động bằng trí tuệ nhân tạo (LLM Gateway).

Mã nguồn được phân tách triệt để theo **Clean Architecture (5 Tầng)**, tách biệt hoàn toàn giữa nghiệp vụ lõi (Domain) và các công nghệ bên ngoài (HTTP, Database, Framework), đảm bảo khả năng bảo trì, mở rộng và kiểm thử tự động đạt mức cao nhất.

---

## 🎯 5 Phân Hệ Tính Năng Cốt Lõi

### 1. 🛠️ Kho 19 Công Cụ Tiện Ích Trực Tuyến (Online Tools Suite)
Hệ thống 19 công cụ xử lý nghiệp vụ với độ trễ dưới 5ms, giao diện Blade SSR tối ưu hóa SEO, hỗ trợ song ngữ (Vi/En):
* **Developer Tools (7 công cụ)**: JSON Formatter & Validator, Base64 Encoder/Decoder, Hash Generator (MD5, SHA256, Bcrypt), JWT Debugger & Inspector, Regex Tester, URL Encoder/Decoder, Proxy Checker (HTTP/HTTPS/SOCKS4/SOCKS5).
* **Calculators & Math (3 công cụ)**: Tính khoản vay trả góp ngân hàng (Loan EMI Calculator), Tính phần trăm (Percentage), Chỉ số khối cơ thể (BMI Calculator).
* **Image Utilities (2 công cụ)**: Trích xuất bảng màu ảnh (Color Extractor), Đọc metadata EXIF (Image Metadata Inspector).
* **SEO Onpage Tools (7 công cụ)**: Mô phỏng Google SERP Snippet theo Pixel Width, Trình tạo Schema Markup JSON-LD, Tạo thẻ Meta HTML5, Thẻ Open Graph & Twitter Cards, Trình tạo tệp Robots.txt, Trình tạo XML Sitemap, Trình sinh URL Slug lọc Stop Words tiếng Việt.

### 2. 🤖 AI Content Studio & Hardware Comparison Pipeline
* **Real-time LLM Gateway**: Kết nối trực tiếp với **Google Gemini 1.5 Flash / Pro** và **OpenAI GPT-4o**.
* **So Sánh Phần Cứng Đối Đầu (Head-to-Head)**: Tự động bóc tách thông số kỹ thuật (Specs), tính toán điểm Benchmark (Gaming, Productivity, Overall), phân tích ưu/nhược điểm giữa 2 linh kiện bất kỳ trên thế giới.
* **Web Article Scraper & Sanitizer**: Bóc tách bài viết từ URL bất kỳ, làm sạch DOM, trích xuất hình ảnh để nạp context cho AI viết bài tự động.

### 3. 🎮 Cổng Web Games HTML5 (Gaming Portal)
* Tích hợp kho game phong phú với cơ chế Sandbox Iframe an toàn.
* Xử lý triệt tiêu lỗi màn đen Canvas (Watchdog Timer + Two-Phase Load Delay).
* Thống kê lượt chơi, bảng xếp hạng và giao diện tương thích hoàn hảo mọi thiết bị di động.

### 4. 🚀 Search Engine Indexing Suite
* **Google Indexing API**: Bắn tín hiệu lập chỉ mục tự động qua Google Service Account JWT, hỗ trợ gom cụm Batch 100 URL/lần.
* **IndexNow Protocol**: Tự động thông báo cập nhật URL mới tới Bing, Yahoo, Yandex, Naver và Copilot AI.

### 5. 🛡️ Admin Control Center & Monetization
* Bảng điều khiển KPI thời gian thực, quản lý người dùng, phân quyền quản trị.
* Quản lý banner quảng cáo AdSense / Nhà tài trợ theo từng vị trí chiến lược.
* Cài đặt tham số động (Dynamic Settings) kết hợp cơ chế xóa cache thông minh (Instant Invalidation).

---

## 🏛️ Kiến Trúc Hệ Thống (Clean Architecture & DDD)

Toàn bộ mã nguồn nghiệp vụ được tổ chức tại thư mục `/src`:

```
src/
├── Domain/              # 🟢 Nghiệp vụ lõi thuần túy (Entities, Value Objects, Tool Engines, Contracts)
├── Application/         # 🔵 Điều phối nghiệp vụ, CQRS Commands/Queries, AI Services, Crawler
├── Infrastructure/      # 🟡 Hiện thực Repositories, Database Persistence, External Adapters
├── Presentation/        # 🔴 Controllers, Form Requests, Resources, Routes (Web & API)
└── Shared/              # 🟣 Middleware bảo mật, Enums chung, Traits (HasUlid, ApiResponse)
```

---

## ⚡ Bắt Đầu Nhanh (Quick Start)

### Cách 1: Chạy Môi Trường Local Với PHP & SQLite/MySQL

```bash
# 1. Clone repository
git clone https://github.com/nguyenminhhoan03-png/techhub.git
cd techhub

# 2. Cài đặt các thư viện PHP
composer install --optimize-autoloader

# 3. Tạo file cấu hình môi trường
cp .env.example .env

# 4. Sinh Application Key
php artisan key:generate

# 5. Chạy Migration và nạp dữ liệu mẫu ban đầu
php artisan migrate --seed

# 6. Tạo link thư mục lưu trữ
php artisan storage:link

# 7. Khởi động Web Server
php artisan serve --port=9022
```

* 🌐 **Trang chủ**: [http://127.0.0.1:9022](http://127.0.0.1:9022)
* 🛡️ **Trang Admin**: [http://127.0.0.1:9022/admin](http://127.0.0.1:9022/admin)
  * Tài khoản: `admin@techhub.local`
  * Mật khẩu: `Admin@123456`

---

### Cách 2: Triển Khai Bằng Docker Compose (Khuyên Dùng)

```bash
# 1. Tạo file cấu hình cho Docker
cp .env.docker.example .env

# 2. Khởi động toàn bộ stack (Nginx, PHP 8.3 FPM, MySQL 8.0, Redis 7, phpMyAdmin)
docker compose up -d --build

# 3. Chạy Migration và nạp dữ liệu
docker compose exec app php artisan migrate --seed

# 4. Import kho game mẫu
docker compose exec app php artisan games:import --amount=50
```

* 🔗 **Website**: [http://localhost:8088](http://localhost:8088)
* 🗄️ **phpMyAdmin**: [http://localhost:8081](http://localhost:8081)

---

## 📚 Kho Tài Liệu Kỹ Thuật Chuyên Sâu (`docs/`)

Hệ thống tài liệu đầy đủ được lưu trữ tại thư mục [**`docs/`**](docs/README.md):

| Nhóm Tài Liệu | Tệp Chi Tiết | Trọng Tâm |
| :--- | :--- | :--- |
| **01. Khởi Động** | 🚀 [**Onboarding & Setup Môi Trường**](docs/01-getting-started/onboarding-and-environment-setup.md) | Thiết lập môi trường từ A-Z, biến `.env`, xử lý sự cố. |
| **02. Kiến Trúc** | 🏛️ [**Clean Architecture, DDD & CQRS**](docs/02-architecture/clean-architecture-ddd-cqrs.md) | Giải phẫu 5 tầng, Command/Query Bus, Dependency Rule. |
| **02. Kiến Trúc** | 🧭 [**Vòng Đời Thực Thi & Mổ Xẻ `/src`**](docs/02-architecture/src-execution-lifecycle-deep-dive.md) | Luồng chạy chi tiết của Request, cơ chế 18 Tool Engines. |
| **03. Cơ Sở Dữ Liệu** | 🗄️ [**Thiết Kế CSDL Toàn Diện (Schema & ERD)**](docs/03-database/database-architecture-and-schema.md) | Thiết kế 24 bảng CSDL, ERD, Indexing & Partitioning. |
| **04. API & Bảo Mật** | 🌐 [**Chuẩn REST API & Xử Lý Lỗi**](docs/04-api-and-security/api-standards-and-error-handling.md) | Chuẩn RESTful, JSON Envelope, Mã lỗi chuẩn hóa, Phân trang. |
| **04. API & Bảo Mật** | 🛡️ [**Bảo Mật & Hardening Hệ Thống**](docs/04-api-and-security/security-and-hardening.md) | Rate Limiting, Eloquent Strict Mode, Security Headers, Request ID. |
| **05. Hiệu Năng & Code**| ⚡ [**Tối Ưu Hiệu Năng & Cache Strategy**](docs/05-performance-and-quality/performance-and-optimization.md) | Chặn N+1, OPcache JIT, Redis Cache, Hàng đợi Queue Worker. |
| **05. Hiệu Năng & Code**| 📏 [**Tiêu Chuẩn Code & Đảm Bảo Chất Lượng**](docs/05-performance-and-quality/coding-standards-and-quality.md) | Strict Types, Laravel Pint, PHPStan Level 5+, Pest Architecture. |
| **06. Giao Diện** | 🎨 [**Kiến Trúc Frontend & Workspaces**](docs/06-frontend/blade-design-system-and-workspaces.md) | Design Tokens Vanilla CSS, Swiper, Kéo thả Base64. |
| **07. Phân Hệ AI** | 🤖 [**AI Engine & Hardware Pipeline**](docs/07-ai-content-engine-and-crawling/ai-engine-and-hardware-pipeline.md) | Real-time LLM Gateway (Gemini/OpenAI), Web Scraper, Auto-Upsert. |
| **08. Tối Ưu SEO** | 🛠️ [**Kiến Trúc Bộ Công Cụ SEO Onpage**](docs/08-seo-tools-architecture/seo-tools-suite.md) | Kiến trúc 7 công cụ SEO Onpage, Thuật toán SERP Pixel Snippet. |
| **08. Tối Ưu SEO** | 🚀 [**Google Indexing & IndexNow Suite**](docs/08-seo-tools-architecture/search-engine-indexing-suite.md) | Đẩy chỉ mục Google Indexing API Batch & IndexNow Protocol. |
| **08. Tối Ưu SEO** | 📈 [**Topical Authority & Chiến Lược SEO**](docs/08-seo-tools-architecture/topical-authority-and-seo-strategy.md) | Chiến lược 2 tầng Technical vs Ranking Signals, Topic Clusters. |
| **Vận Hành & Triển Khai**| 🐳 [**Cẩm Nang Triển Khai Docker**](docs/DOCKER-GUIDE.md) | Hướng dẫn triển khai Docker Compose chỉ với một dòng lệnh. |
| **Vận Hành & Triển Khai**| 🚀 [**Hướng Dẫn Đưa Lên VPS & Cài Đặt SSL**](docs/VPS-DEPLOYMENT-GUIDE.md) | Deploy VPS Ubuntu, Nginx Reverse Proxy, Certbot HTTPS, SCP Proxy. |
| **Vận Hành & Triển Khai**| 📝 [**Development Notes & Bài Học Kinh Nghiệm**](docs/DEVELOPMENT_NOTES.md) | Tổng hợp lỗi đã fix, quy tắc tránh lỗi màn đen game, tối ưu Lighthouse. |

---

## 🧪 Kiểm Thử Tự Động & Đảm Bảo Chất Lượng

```bash
# Chạy toàn bộ Pest test suite và Architecture Tests
vendor/bin/pest

# Kiểm tra định dạng code chuẩn PSR-12 / Pint
vendor/bin/pint --test

# Phân tích tĩnh phát hiện lỗi tiềm ẩn (PHPStan)
vendor/bin/phpstan analyse
```

---

## 📄 Bản Quyền & Giấy Phép

Dự án phát triển bởi đội ngũ kỹ thuật **TechHub Team** dưới giấy phép [MIT License](LICENSE).
