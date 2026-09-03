# ⚡ 05. Tối Ưu Hiệu Năng & Cache Strategy (Performance Guide)

Để đáp ứng hàng triệu lượt truy vấn mỗi ngày với độ trễ (latency) thấp nhất, **TechHub** áp dụng các giải pháp tối ưu hóa hiệu năng toàn diện từ tầng PHP Engine, Database đến Caching.

---

## ⚡ 1. Chiến Lược Caching Trên Production

Khi triển khai ứng dụng lên môi trường Production, bắt buộc phải chạy bộ lệnh tối ưu hóa bộ nhớ đệm (Cache) của Laravel:

```bash
# 1. Cache lại toàn bộ file cấu hình (config)
php artisan config:cache

# 2. Cache lại toàn bộ route (bỏ qua bước parse regex URL)
php artisan route:cache

# 3. Cache lại các file Blade template (nếu có)
php artisan view:cache

# 4. Cache danh sách Event và Listener
php artisan event:cache

# 5. Tối ưu autoloader của Composer
composer dump-autoload --optimize --no-dev --classmap-authoritative
```

> [!WARNING]
> Tuyệt đối KHÔNG chạy `php artisan config:cache` trên môi trường Local Development, vì bất kỳ sửa đổi nào trong `.env` sẽ không có tác dụng cho đến khi bạn xóa cache (`php artisan config:clear`).

---

## 🐘 2. Cấu Hình PHP OPcache & JIT

PHP 8.2+ kết hợp với **OPcache** và **JIT (Just-In-Time Compiler)** giúp tăng tốc độ xử lý mã nguồn lên 2x - 3x lần. Cấu hình khuyến nghị trong file `php.ini`:

```ini
[opcache]
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0       ; Tắt kiểm tra file thay đổi trên Production
opcache.save_comments=1
opcache.jit_buffer_size=100M
opcache.jit=tracing
```

---

## 🗄️ 3. Tối Ưu Hóa Truy Vấn Cơ Sở Dữ Liệu

### 1. Tránh Tuyệt Đối N+1 Queries
Nhờ chế độ `Model::preventLazyLoading(!app()->isProduction())`, bạn sẽ được cảnh báo ngay lập tức nếu quên Eager Loading.

* ❌ **Sai (Gây ra N+1 queries)**:
  ```php
  $users = User::all();
  foreach ($users as $user) {
      echo $user->profile->avatar; // Gửi 1 câu query cho mỗi user!
  }
  ```
* ✅ **Đúng (Eager Loading)**:
  ```php
  $users = User::with('profile')->get(); // Chỉ gửi đúng 2 câu query
  ```

### 2. Sử Dụng `select()` Chỉ Lấy Các Cột Cần Thiết
Tránh dùng `SELECT *` đối với các bảng có cột lớn như `text`, `longtext`, `json`.
```php
// Tối ưu bộ nhớ RAM và băng thông network
$users = User::query()
    ->select(['id', 'name', 'email', 'created_at'])
    ->paginate(20);
```

### 3. Phân Trang (Pagination) Hiệu Quả
* Với danh sách thông thường: Sử dụng `paginate($perPage)`.
* Với danh sách dữ liệu rất lớn (hàng triệu bản ghi) hoặc infinite scroll: Sử dụng **`simplePaginate()`** hoặc **`cursorPaginate()`** để tránh phải chạy câu lệnh `SELECT COUNT(*)` đắt đỏ.

---

## 🏎️ 4. Sử Dụng Redis Caching & Queue Workers

### 1. Caching Dữ Liệu Hay Đọc (Read-Heavy)
Sử dụng mẫu **Cache-Aside (Remember pattern)**:
```php
use Illuminate\Support\Facades\Cache;

$categories = Cache::remember('categories:active', now()->addHours(6), function () {
    return Category::query()->where('is_active', true)->get();
});
```

### 2. Xử Lý Tác Vụ Nặng Qua Hàng Đợi (Asynchronous Queues)
Các tác vụ tốn thời gian như:
* Gửi Email / Notification
* Xử lý hình ảnh / nén video
* Đồng bộ dữ liệu sang bên thứ ba (Webhooks, CRM)

Bắt buộc phải đẩy vào **Queue** thông qua `ShouldQueue` Interface và chạy ngầm bằng Supervisor:
```bash
php artisan queue:work redis --sleep=3 --tries=3 --timeout=90
```

---

## 🕒 5. Immutable Carbon Dates (`Date::use(CarbonImmutable::class)`)

Mặc định, `Carbon` trong PHP là mutable (dễ bị thay đổi giá trị gốc khi thao tác cộng trừ ngày tháng, dẫn đến các bug khó phát hiện).
Hệ thống đã kích hoạt `CarbonImmutable`:
```php
$date = now(); // Instance của CarbonImmutable
$nextWeek = $date->addWeek();

// $date vẫn giữ nguyên giá trị ban đầu, không bị thay đổi!
```
