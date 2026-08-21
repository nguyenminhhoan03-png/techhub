# 📚 TechHub Master Technical Documentation

Chào mừng bạn đến với kho tài liệu kiến trúc kỹ thuật của dự án **TechHub** — nền tảng xây dựng trên **Laravel 12**, áp dụng mô hình kiến trúc **Clean Architecture**, **Domain-Driven Design (DDD)** và **CQRS (Command Query Responsibility Segregation)**.

---

## 🗺️ Bản Đồ Phân Cấp Thư Mục Tài Liệu (`docs/`)

Toàn bộ tài liệu được phân chia thành **6 nhóm thư mục chuyên sâu** khoa học và chuẩn mực:

```
docs/
├── 📁 01-getting-started/
│   └── 🚀 onboarding-and-environment-setup.md       # Quy trình Onboarding, setup .env, DB, troubleshooting
│
├── 📁 02-architecture/
│   ├── 🏛️ clean-architecture-ddd-cqrs.md           # Kiến trúc 5 tầng, CQRS Bus, Dependency Inversion
│   ├── 🧭 src-execution-lifecycle-deep-dive.md     # Deep-dive luồng chạy của Request khi đi vào /src
│   └── 📦 blueprints/
│       └── 📄 TECHHUB_Laravel_Clean_Architecture_Blueprint.docx
│
├── 📁 03-database/
│   └── 🗄️ database-architecture-and-schema.md      # Thiết kế CSDL 22 bảng, ERD, Migration & Indexing
│
├── 📁 04-api-and-security/
│   ├── 🌐 api-standards-and-error-handling.md      # Chuẩn RESTful API, JSON Envelope, Exception Handling
│   └── 🛡️ security-and-hardening.md                # Bảo mật đa tầng, Rate Limiting, Safeguards
│
├── 📁 05-performance-and-quality/
│   ├── ⚡ performance-and-optimization.md          # Tối ưu N+1, Strict Mode, Redis Cache Strategy
│   └── 📏 coding-standards-and-quality.md          # PSR-12 Pint, PHPStan Level 8+, Pest Architecture Tests
│
└── 📁 06-frontend/
    └── 🎨 blade-design-system-and-workspaces.md    # Design Tokens, Swiper, Base64 Drag-Drop & Rich Outputs
```

---

## 📖 Bảng Điều Hướng Chi Tiết

| Thư Mục / Chủ Đề | Tệp Tài Liệu Chi Tiết | Mô Tả Trọng Tâm | Đối Tượng |
| :--- | :--- | :--- | :--- |
| **01. Khởi Động** | 🚀 [**Onboarding & Setup Môi Trường**](./01-getting-started/onboarding-and-environment-setup.md) | Các bước thiết lập ban đầu khi clone dự án, cấu hình `.env`, chạy migration, seeders và xử lý sự cố. | All Developers |
| **02. Kiến Trúc** | 🏛️ [**Clean Architecture, DDD & CQRS**](./02-architecture/clean-architecture-ddd-cqrs.md) | Giải phẫu 5 tầng (`Domain`, `Application`, `Infrastructure`, `Presentation`, `Shared`), Command/Query Bus. | Architects / Senior Dev |
| **02. Kiến Trúc** | 🧭 [**Vòng Đời Thực Thi & Mổ Xẻ `/src`**](./02-architecture/src-execution-lifecycle-deep-dive.md) | Luồng chạy chi tiết của Request (Route -> Controller -> Request -> Command -> Handler -> Engine -> Repo). | Backend / Fullstack |
| **03. Cơ Sở Dữ Liệu** | 🗄️ [**Thiết Kế CSDL Toàn Diện (Schema & ERD)**](./03-database/database-architecture-and-schema.md) | Thiết kế 22 bảng CSDL chuẩn Senior cho 6 Module (Tools, Content, Hardware, Compare, Deals, Billing). | DBAs / Backend Dev |
| **04. API & Bảo Mật** | 🌐 [**Chuẩn REST API & Xử Lý Lỗi**](./04-api-and-security/api-standards-and-error-handling.md) | Chuẩn RESTful, JSON Response Envelope, Mã lỗi nghiệp vụ, Xử lý Exception tập trung, Phân trang. | Backend & Frontend |
| **04. API & Bảo Mật** | 🛡️ [**Bảo Mật & Hardening Hệ Thống**](./04-api-and-security/security-and-hardening.md) | Rate Limiting đa tầng, Eloquent Strict Mode, DB Destructive Safeguard, Security Headers, Correlation ID. | SecOps / DevOps |
| **05. Hiệu Năng & Code** | ⚡ [**Tối Ưu Hiệu Năng & Cache Strategy**](./05-performance-and-quality/performance-and-optimization.md) | Xử lý N+1 Query, chiến lược Cache Redis, Queue Workers, OPcache, Caching Artisan, Tối ưu Index. | Backend Engineers |
| **05. Hiệu Năng & Code** | 📏 [**Tiêu Chuẩn Code & Đảm Bảo Chất Lượng**](./05-performance-and-quality/coding-standards-and-quality.md) | Strict Types, Laravel Pint, PHPStan Level 8+, Pest Architecture Tests, Quy tắc Git Commit. | All Developers |
| **06. Giao Diện** | 🎨 [**Kiến Trúc Frontend & Workspaces**](./06-frontend/blade-design-system-and-workspaces.md) | Design System Tokens, Swiper Carousel, Xử lý kéo thả Base64 và 11 Bộ hiển thị đồ họa tương tác. | Frontend / Fullstack |

---

## ⚡ Quick Start (Khởi Động Nhanh)

```bash
# 1. Cài đặt các thư viện PHP
composer install --optimize-autoloader

# 2. Tạo file cấu hình môi trường
cp .env.example .env

# 3. Tạo Application Key
php artisan key:generate

# 4. Chạy Migration và Seed CSDL
php artisan migrate --seed

# 5. Chạy Test Suite và Architecture Tests
vendor/bin/pest

# 6. Kiểm tra Code Quality & Static Analysis
vendor/bin/pint --test
vendor/bin/phpstan analyse

# 7. Khởi động Web Server
php artisan serve --port=9022
```

* 🌐 **Trang Chủ**: `http://127.0.0.1:9022/`
* 🛡️ **Quản Trị Admin**: `http://127.0.0.1:9022/admin` (Tài khoản: `admin@techhub.local` / Mật khẩu: `Admin@123456`)
* 🗺️ **Sitemap XML**: `http://127.0.0.1:9022/sitemap.xml`
