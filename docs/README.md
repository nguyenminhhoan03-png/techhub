# 📚 TechHub Master Technical Documentation

Chào mừng bạn đến với kho tài liệu kiến trúc kỹ thuật của dự án **TechHub** — nền tảng xây dựng trên **Laravel 12**, áp dụng mô hình kiến trúc **Clean Architecture**, **Domain-Driven Design (DDD)** và **CQRS (Command Query Responsibility Segregation)**.

---

## 🗺️ Bản Đồ Phân Cấp Thư Mục Tài Liệu (`docs/`)

Toàn bộ tài liệu được phân chia thành **8 nhóm thư mục chuyên sâu** kèm các cẩm nang vận hành & triển khai thực tế:

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
│   └── 🗄️ database-architecture-and-schema.md      # Thiết kế CSDL 24 bảng, ERD, Migration & Indexing
│
├── 📁 04-api-and-security/
│   ├── 🌐 api-standards-and-error-handling.md      # Chuẩn RESTful API, JSON Envelope, Exception Handling
│   └── 🛡️ security-and-hardening.md                # Bảo mật đa tầng, Rate Limiting, Safeguards
│
├── 📁 05-performance-and-quality/
│   ├── ⚡ performance-and-optimization.md          # Tối ưu N+1, Strict Mode, Redis Cache Strategy
│   └── 📏 coding-standards-and-quality.md          # PSR-12 Pint, PHPStan Level 8+, Pest Architecture Tests
│
├── 📁 06-frontend/
│   └── 🎨 blade-design-system-and-workspaces.md    # Design Tokens, Swiper, Base64 Drag-Drop & Rich Outputs
│
├── 📁 07-ai-content-engine-and-crawling/
│   └── 🤖 ai-engine-and-hardware-pipeline.md       # AI Content Studio, Real-time LLM Gateway, Web Crawler
│
├── 📁 08-seo-tools-architecture/
│   ├── 🛠️ seo-tools-suite.md                       # Kiến trúc 7 công cụ SEO Onpage
│   ├── 🚀 search-engine-indexing-suite.md          # Google Indexing API & IndexNow Protocol
│   └── 📈 topical-authority-and-seo-strategy.md    # Chiến lược SEO 2 tầng, Topic Clusters & Lộ trình 5 Phase
│
├── 🐳 DOCKER-GUIDE.md                               # Cẩm nang triển khai container hóa Docker Compose
├── 🚀 VPS-DEPLOYMENT-GUIDE.md                       # Hướng dẫn đưa lên VPS Ubuntu, Nginx, SSL & SCP Proxy
└── 📝 DEVELOPMENT_NOTES.md                          # Nhật ký khắc phục lỗi & các pattern kinh nghiệm quý báu
```

---

## 📖 Bảng Điều Hướng Chi Tiết

| Thư Mục / Chủ Đề | Tệp Tài Liệu Chi Tiết | Mô Tả Trọng Tâm | Đối Tượng |
| :--- | :--- | :--- | :--- |
| **01. Khởi Động** | 🚀 [**Onboarding & Setup Môi Trường**](./01-getting-started/onboarding-and-environment-setup.md) | Các bước thiết lập ban đầu khi clone dự án, cấu hình `.env`, chạy migration, seeders và xử lý sự cố. | All Developers |
| **02. Kiến Trúc** | 🏛️ [**Clean Architecture, DDD & CQRS**](./02-architecture/clean-architecture-ddd-cqrs.md) | Giải phẫu 5 tầng (`Domain`, `Application`, `Infrastructure`, `Presentation`, `Shared`), Command/Query Bus. | Architects / Senior Dev |
| **02. Kiến Trúc** | 🧭 [**Vòng Đời Thực Thi & Mổ Xẻ `/src`**](./02-architecture/src-execution-lifecycle-deep-dive.md) | Luồng chạy chi tiết của Request (Route -> Controller -> Request -> Command -> Handler -> Engine -> Repo). | Backend / Fullstack |
| **03. Cơ Sở Dữ Liệu** | 🗄️ [**Thiết Kế CSDL Toàn Diện (Schema & ERD)**](./03-database/database-architecture-and-schema.md) | Thiết kế 24 bảng CSDL chuẩn Senior cho các Module (Tools, Content, Hardware, Compare, Deals, Games). | DBAs / Backend Dev |
| **04. API & Bảo Mật** | 🌐 [**Chuẩn REST API & Xử Lý Lỗi**](./04-api-and-security/api-standards-and-error-handling.md) | Chuẩn RESTful, JSON Response Envelope, Mã lỗi nghiệp vụ, Xử lý Exception tập trung, Phân trang. | Backend & Frontend |
| **04. API & Bảo Mật** | 🛡️ [**Bảo Mật & Hardening Hệ Thống**](./04-api-and-security/security-and-hardening.md) | Rate Limiting đa tầng, Eloquent Strict Mode, DB Destructive Safeguard, Security Headers, Correlation ID. | SecOps / DevOps |
| **05. Hiệu Năng & Code** | ⚡ [**Tối Ưu Hiệu Năng & Cache Strategy**](./05-performance-and-quality/performance-and-optimization.md) | Xử lý N+1 Query, chiến lược Cache Redis, Queue Workers, OPcache, Caching Artisan, Tối ưu Index. | Backend Engineers |
| **05. Hiệu Năng & Code** | 📏 [**Tiêu Chuẩn Code & Đảm Bảo Chất Lượng**](./05-performance-and-quality/coding-standards-and-quality.md) | Strict Types, Laravel Pint, PHPStan Level 8+, Pest Architecture Tests, Quy tắc Git Commit. | All Developers |
| **06. Giao Diện** | 🎨 [**Kiến Trúc Frontend & Workspaces**](./06-frontend/blade-design-system-and-workspaces.md) | Design System Tokens, Swiper Carousel, Xử lý kéo thả Base64 và 11 Bộ hiển thị đồ họa tương tác. | Frontend / Fullstack |
| **07. Phân Hệ AI** | 🤖 [**AI Engine & Hardware Pipeline**](./07-ai-content-engine-and-crawling/ai-engine-and-hardware-pipeline.md) | Phân tích linh kiện thời gian thực bằng Google Gemini/OpenAI, Scraper Web Crawler và lưu trữ so sánh. | AI / Backend Dev |
| **08. Tối Ưu SEO** | 🛠️ [**Kiến Trúc Bộ Công Cụ SEO Onpage**](./08-seo-tools-architecture/seo-tools-suite.md) | Kiến trúc 7 công cụ SEO Onpage: SERP Pixel Snippet, Schema JSON-LD, Robots.txt, Meta Tags, Slug Stop Words. | SEO / Webmasters |
| **08. Tối Ưu SEO** | 🚀 [**Google Indexing & IndexNow Suite**](./08-seo-tools-architecture/search-engine-indexing-suite.md) | Đẩy chỉ mục siêu tốc với Google Indexing API Batch và IndexNow Protocol, CLI Artisan & Cron. | SEO / Backend Dev |
| **08. Tối Ưu SEO** | 📈 [**Topical Authority & Chiến Lược SEO**](./08-seo-tools-architecture/topical-authority-and-seo-strategy.md) | Chiến lược 2 tầng Technical vs Ranking Signals, Topic Clusters, Internal Linking 2 chiều, Lộ trình 5 Phase. | SEO / Growth / Product |
| **Vận Hành & DevOps** | 🐳 [**Cẩm Nang Docker Compose**](./DOCKER-GUIDE.md) | Hướng dẫn triển khai cô lập toàn bộ stack (PHP 8.3, Nginx, MySQL, Redis, phpMyAdmin) qua Docker. | DevOps / Fullstack |
| **Vận Hành & DevOps** | 🚀 [**Hướng Dẫn Triển Khai VPS & SSL**](./VPS-DEPLOYMENT-GUIDE.md) | Hướng dẫn chi tiết triển khai lên VPS Ubuntu, cấu hình Nginx Reverse Proxy, Certbot SSL và lệnh SCP. | DevOps / SysAdmin |
| **Vận Hành & DevOps** | 📝 [**Development Notes & Kinh Nghiệm**](./DEVELOPMENT_NOTES.md) | Nhật ký tổng hợp kinh nghiệm xử lý lỗi (Black Screen iframe, Lock Wait Timeout, Lighthouse Rules). | All Developers |

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
