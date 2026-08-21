# 🚀 01. Onboarding & Setup Môi Trường Dự Án (Developer Guide)

Tài liệu này hướng dẫn chi tiết quy trình chuẩn mà một Developer (từ Junior đến Senior) cần thực hiện ngay sau khi clone source code **TechHub** về máy tính cá nhân.

---

## 📋 1. Yêu Cầu Môi Trường (Prerequisites)

Trước khi bắt đầu, đảm bảo máy của bạn đã cài đặt các công cụ sau với phiên bản tối thiểu:

* **PHP**: `>= 8.2` (Khuyến nghị **PHP 8.3** hoặc **8.4**)
* **Các PHP Extensions bắt buộc**:
  * `ext-bcmath`
  * `ext-ctype`
  * `ext-curl`
  * `ext-dom`
  * `ext-fileinfo`
  * `ext-json`
  * `ext-mbstring`
  * `ext-openssl`
  * `ext-pcre`
  * `ext-pdo`
  * `ext-pdo_sqlite` (cho Local/Testing) hoặc `ext-pdo_mysql` / `ext-pdo_pgsql`
  * `ext-redis` (phục vụ Caching & Queue)
* **Composer**: `>= 2.7`
* **Node.js & NPM**: Node `>= 20.x`, NPM `>= 10.x`
* **Database**: SQLite (Mặc định cho Dev nhanh) hoặc MySQL `>= 8.0` / PostgreSQL `>= 15.0`
* **Redis**: `>= 7.0` (Khuyến nghị)
* **Docker & Docker Compose** (Tùy chọn nếu muốn chạy qua Laravel Sail)

---

## 🛠️ 2. Quy Trình Cài Đặt Chi Tiết Từng Bước

### Bước 1: Clone Repository
```bash
git clone <repository-url> techhub
cd techhub
```

### Bước 2: Cài Đặt Dependencies Qua Composer
```bash
composer install --optimize-autoloader
```
> [!TIP]
> Tham số `--optimize-autoloader` giúp Composer tạo ra bản đồ class map tối ưu, giúp tăng tốc độ tải file khi chạy ứng dụng.

### Bước 3: Thiết Lập File Biến Môi Trường (`.env`)
```bash
# Trên Linux / macOS / Git Bash:
cp .env.example .env

# Trên Windows PowerShell:
Copy-Item .env.example .env
```

Mở file `.env` và kiểm tra/chỉnh sửa các thông số quan trọng:
```ini
APP_NAME=TechHub
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Cấu hình CSDL (Mặc định dùng SQLite để chạy ngay không cần cài đặt CSDL rời)
DB_CONNECTION=sqlite
# Nếu dùng MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=techhub
# DB_USERNAME=root
# DB_PASSWORD=secret

# Cấu hình Queue & Cache cho môi trường Local
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

### Bước 4: Tạo Khóa Mã Hóa Ứng Dụng (Application Key)
```bash
php artisan key:generate
```
Lệnh này sẽ tự động sinh mã `APP_KEY` trong file `.env` (dùng để mã hóa session, password hash, Sanctum token).

### Bước 5: Tạo File Database & Chạy Migrations
Nếu bạn sử dụng SQLite mặc định:
```bash
# Tạo file SQLite nếu chưa có
# Trên Linux/macOS:
touch database/database.sqlite

# Trên Windows PowerShell:
New-Item -ItemType File -Path database/database.sqlite -Force

# Chạy migration và nạp dữ liệu mẫu (Seed)
php artisan migrate --seed
```

### Bước 6: Tạo Symbolic Link Cho Thư Mục Upload (Storage)
```bash
php artisan storage:link
```

---

## 🧪 3. Kiểm Tra Tính Đúng Đắn Của Hệ Thống

Sau khi cài đặt xong, hãy chạy toàn bộ các bộ kiểm tra tự động để đảm bảo môi trường hoạt động 100%:

```bash
# 1. Chạy Unit Test, Feature Test và Architecture Tests
vendor/bin/pest

# 2. Kiểm tra chuẩn Code Style (Laravel Pint)
vendor/bin/pint --test

# 3. Phân tích tĩnh Static Analysis (PHPStan)
vendor/bin/phpstan analyse
```

---

## 🚀 4. Khởi Chạy Ứng Dụng

### Cách 1: Chạy trực tiếp bằng PHP Artisan (Phổ biến nhất cho Dev)
Mở 2 terminal riêng biệt:

**Terminal 1 (HTTP Server):**
```bash
php artisan serve --port=8000
```
Truy cập: `http://localhost:8000/up` (Healthcheck endpoint trả về HTTP 200).

**Terminal 2 (Queue Worker xử lý tác vụ ngầm):**
```bash
php artisan queue:work
```

### Cách 2: Chạy thông qua Laravel Sail (Docker)
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

---

## ⚠️ 5. Các Vấn Đề Thường Gặp (Troubleshooting)

| Lỗi gặp phải | Nguyên nhân | Cách khắc phục |
| :--- | :--- | :--- |
| `No application encryption key has been specified.` | Chưa chạy lệnh sinh key | Chạy `php artisan key:generate`. |
| `Database file at [...] does not exist.` | SQLite chưa có file vật lý | Tạo file `database/database.sqlite` rồi chạy lại `php artisan migrate`. |
| `Class '...' not found` sau khi thêm file mới | Composer autoload chưa nhận diện namespace mới | Chạy `composer dump-autoload`. |
| `Permission denied` trong thư mục `storage` hoặc `bootstrap/cache` | Quyền ghi thư mục chưa được cấp (Linux/macOS/Docker) | Chạy `chmod -R 775 storage bootstrap/cache` và gán quyền `chown -R www-data:www-data storage`. |
| Lỗi N+1 Query `Attempted to lazy load [...] on model [...]` | Chế độ Eloquent Strict Mode đang chặn Lazy Loading | Đây là tính năng bảo vệ của dự án! Hãy dùng Eager Loading `Model::with('relation')` thay vì truy cập quan hệ trực tiếp trong vòng lặp. |
