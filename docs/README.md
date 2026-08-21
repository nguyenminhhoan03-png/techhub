# 📚 TechHub Documentation & Architecture Hub

Chào mừng bạn đến với kho tài liệu kỹ thuật của dự án **TechHub** — nền tảng xây dựng trên **Laravel 12**, áp dụng mô hình kiến trúc **Clean Architecture**, **Domain-Driven Design (DDD)** và **CQRS (Command Query Responsibility Segregation)**.

---

## 🗺️ Mục Lục Tài Liệu Kỹ Thuật

Bộ tài liệu được chia thành các chương chuyên sâu, phục vụ từ việc onboarding cho thành viên mới đến các chuẩn mực cao cấp về Security, Performance và Coding Conventions:

| File | Nội dung chính | Đối tượng |
| :--- | :--- | :--- |
| 🚀 [**01. Onboarding & Setup Môi Trường**](./01-onboarding-and-environment-setup.md) | Quy trình từng bước khi mới clone dự án về, cấu hình `.env`, database, migrations, queue, troubleshooting. | All Developers / Onboarding |
| 🏛️ [**02. Kiến Trúc Clean Architecture, DDD & CQRS**](./02-architecture-and-ddd-cqrs.md) | Giải phẫu 5 tầng (`Domain`, `Application`, `Infrastructure`, `Presentation`, `Shared`), luồng xử lý Command / Query, Dependency Rule. | Backend Engineers / Architects |
| 🛡️ [**03. Bảo Mật & Hardening Hệ Thống**](./03-security-and-hardening.md) | Rate Limiting đa tầng, Eloquent Strict Mode, DB Destructive Safeguard, Security Headers, Password Policy, Correlation ID. | All Developers / DevOps / SecOps |
| ⚡ [**04. Tối Ưu Hiệu Năng & Cache Strategy**](./04-performance-and-optimization.md) | Xử lý N+1 Query, chiến lược Cache Redis, Queue Workers, OPcache, Caching Artisan, Tối ưu Index CSDL. | Backend Engineers / DevOps |
| 📏 [**05. Tiêu Chuẩn Code & Đảm Bảo Chất Lượng**](./05-coding-standards-and-quality.md) | Strict Types, Laravel Pint, PHPStan Level 8+, Pest Architecture Tests, Quy tắc Git Commit & PR. | All Developers |
| 🌐 [**06. Chuẩn Thiết Kế REST API & Xử Lý Lỗi**](./06-api-standards-and-error-handling.md) | Chuẩn RESTful, JSON Response Envelope, Mã lỗi nghiệp vụ, Xử lý Exception tập trung, Phân trang chuẩn. | Backend & Frontend Engineers |
| 🗄️ [**07. Thiết Kế CSDL Toàn Diện (Schema & ERD)**](./07-database-architecture-and-schema.md) | Thiết kế 22 bảng CSDL chuẩn Senior cho 6 Module (Tools, Content, Hardware, Compare, Deals, Billing). | Backend Engineers / DBAs |

---

## ⚡ Quick Start (Bắt Đầu Nhanh)

Nếu bạn vừa clone repository này về máy, hãy chạy nhanh các lệnh sau:

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
```

---

## 🧩 Tổng Quan Ngắn Gọn Về Cấu Trúc Mã Nguồn

```
techhub/
├── app/                  # Application Providers & Bootstrap core
├── bootstrap/            # App lifecycle, Global Middleware & Exception Handling
├── config/               # Cấu hình dịch vụ (auth, cache, database, session,...)
├── database/             # Migrations, Seeders, Factories
├── docs/                 # Toàn bộ tài liệu chuẩn dự án (Bạn đang ở đây)
├── routes/               # Route console / health check
├── src/                  # Core Business Logic (Clean Architecture + DDD)
│   ├── Domain/           # Entities, Value Objects, Domain Events, Repository Contracts
│   ├── Application/      # Commands, Queries, Command/Query Bus, Handlers, Services
│   ├── Infrastructure/   # Repository Implementations, External Adapters, DB Persistence
│   ├── Presentation/     # HTTP Controllers, API Routes, Requests, Resources/DTOs
│   └── Shared/           # Middleware, Helpers, Base Enums, Shared Traits & Contracts
└── tests/                # Feature, Unit và Pest Architecture Tests
```
