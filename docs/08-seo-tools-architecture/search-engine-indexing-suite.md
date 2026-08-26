# 🚀 Kiến Trúc Hệ Thống Đẩy Chỉ Mục Tự Động (Search Engine Indexing Suite)

## 1. Giới Thiệu Tổng Quan

Hệ thống **Search Engine Indexing Suite** trong TechHub được thiết kế để giải quyết bài toán cốt lõi của SEO hiện đại: **Rút ngắn thời gian lập chỉ mục (Time-to-Index)** của các công cụ tìm kiếm từ vài tuần xuống còn **vài phút đến vài tiếng**.

Hệ thống tích hợp hai cơ chế Indexing mạnh mẽ nhất hiện nay theo chuẩn **Clean Architecture**:
1. **Google Indexing API**: Bắn tín hiệu trực tiếp vào hàng đợi crawl ưu tiên cao của Googlebot thông qua Google Cloud Service Account.
2. **IndexNow Protocol**: Giao thức mở được hỗ trợ bởi Microsoft Bing, Yahoo, Yandex, Naver, Seznam và Copilot AI.

---

## 2. Bản Đồ Kiến Trúc (Architecture Overview)

Tuân thủ nghiêm ngặt mô hình Clean Architecture (DDD):

```
src/Application/Seo/
├── 📁 Commands/
│   ├── ⚡ SeoIndexUrlsCommand.php        # Artisan command: seo:push-index (Google)
│   └── ⚡ SubmitIndexNowCommand.php       # Artisan command: seo:indexnow (IndexNow)
└── 📁 Services/
    ├── 🌐 GoogleIndexingService.php      # Giao tiếp với Google Indexing Batch API
    └── 🌐 IndexNowService.php            # Giao tiếp với IndexNow API Endpoint
```

### Luồng Hoạt Động (Execution Flow)
1. **CLI / Cron Trigger**: `routes/console.php` hoặc Quản trị viên kích hoạt Command.
2. **Application Service**:
   - `GoogleIndexingService::getLatestUrls($limit)` hoặc `IndexNowService::collectAllUrls()` thu thập các URL hợp lệ từ Domain Entities (`Tool`, `Article`, `Game`, Static Pages).
3. **External API Dispatch**:
   - **Google**: Xác thực qua JWT Service Account Key (`storage/app/google-service-account.json`), gom Batch (100 URLs/batch) và gửi tới `https://indexing.googleapis.com/v3/urlNotifications:publish`.
   - **IndexNow**: Gom Batch (5,000 URLs/batch), đính kèm khóa xác thực `51fc886668034faeaef27f8d1e361511.txt` và gửi tới `https://api.indexnow.org/indexnow`.

---

## 3. Hướng Dẫn Cấu Hình Chi Tiết

### 3.1. Cấu hình Google Indexing API

#### Bước 1: Tạo Service Account trên Google Cloud
1. Truy cập [Google Cloud Console](https://console.cloud.google.com/).
2. Tạo Project mới (ví dụ: `techhub`).
3. Bật **Web Search Indexing API** trong thư viện API.
4. Vào **IAM & Admin -> Service Accounts** -> Bấm **Create Service Account** (ví dụ: `techhub-indexer`).
5. Vào Service Account vừa tạo -> Tab **Keys** -> **Add Key** -> **Create new key (JSON)**.
6. Tải file về và đổi tên thành: `google-service-account.json`.

#### Bước 2: Phân quyền trong Google Search Console
1. Mở file `google-service-account.json`, copy giá trị của trường `"client_email"` (dạng `...@...iam.gserviceaccount.com`).
2. Vào [Google Search Console](https://search.google.com/search-console) của website `muabanwebsite.io.vn`.
3. Vào **Settings** -> **Users and permissions** -> Bấm **Add user**.
4. Dán email vừa copy vào, chọn quyền là **Owner (Chủ sở hữu)** -> Bấm **Add**.

#### Bước 3: Đưa file JSON lên máy chủ VPS
File cần được đặt tại đường dẫn: `storage/app/google-service-account.json`
```bash
# Cách nhanh nhất: tạo trực tiếp trên terminal VPS
cat << 'EOF' > ~/techhub/storage/app/google-service-account.json
# (Paste toàn bộ nội dung JSON vào đây)
EOF
```

---

### 3.2. Cấu hình IndexNow Protocol
1. Khóa IndexNow mặc định được cấu hình trong `config/services.php`:
   ```php
   'indexnow' => [
       'key' => env('INDEXNOW_KEY', '51fc886668034faeaef27f8d1e361511'),
   ]
   ```
2. Tệp khóa xác thực tĩnh đã được đặt tại: `public/51fc886668034faeaef27f8d1e361511.txt`.

---

## 4. Hướng Dẫn Vận Hành CLI (Commands)

### 4.1. Lệnh Google Indexing

```bash
# 1. Chạy thử nghiệm với 1 URL trang chủ (Kiểm tra kết nối và quyền Search Console)
docker compose exec app php artisan seo:push-index --test

# 2. Đẩy tối đa 100 URL mới nhất (Mặc định)
docker compose exec app php artisan seo:push-index

# 3. Tùy chỉnh số lượng URL cần đẩy
docker compose exec app php artisan seo:push-index --limit=50
```

### 4.2. Lệnh IndexNow (Bing, Yahoo, Yandex, AI)

```bash
# Đẩy toàn bộ danh mục bài viết, game, công cụ lên IndexNow
docker compose exec app php artisan seo:indexnow
```

---

## 5. Tự Động Hóa Lịch Chạy (Task Scheduling)

Hệ thống được cấu hình tự động đẩy dữ liệu định kỳ trong `routes/console.php`:

```php
// ── Lịch Chạy SEO Indexing Tự Động Hàng Ngày ──────────────────────────────────
// 1. Gửi toàn bộ URL tới mạng lưới IndexNow vào 03:00 sáng
Schedule::command('seo:indexnow')->dailyAt('03:00')->withoutOverlapping();

// 2. Gửi các URL mới nhất tới Google Indexing API vào 04:00 sáng
Schedule::command('seo:push-index')->dailyAt('04:00')->withoutOverlapping();
```

---

## 6. Hạn Mức & Lưu Ý Vận Hành (Best Practices)

| Tiêu Chí | Google Indexing API | IndexNow Protocol |
|---|---|---|
| **Hạn mức (Quota)** | 200 URLs / ngày / project | 10,000 URLs / lần gửi |
| **Công cụ hỗ trợ** | Googlebot | Bing, Yahoo, Yandex, Naver, Copilot AI |
| **Tốc độ bot phản hồi** | 5 phút – 2 tiếng | Tức thì (Realtime Webhook) |
| **Quyền bắt buộc** | Owner trong Search Console | File key `.txt` public ở root domain |
| **Xử lý Batch** | Chia chunk 100 URLs / request | Chia chunk 5,000 URLs / request |
