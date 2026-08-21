# 🛡️ 03. Bảo Mật & Hardening Hệ Thống (Security Guide)

Bảo mật là ưu tiên hàng đầu tại **TechHub**. Dự án áp dụng mô hình bảo vệ nhiều lớp (Defense-in-Depth) từ tầng ứng dụng, cơ sở dữ liệu, mạng cho đến HTTP response.

---

## 🔒 1. Chế Độ Nghiêm Ngặt Của Eloquent (Eloquent Strict Mode)

Trong file [`app/Providers/AppServiceProvider.php`](file:///e:/Project_ItWebDev/PHP/techhub/app/Providers/AppServiceProvider.php), hệ thống kích hoạt chế độ nghiêm ngặt trên môi trường Development và Testing:

```php
Model::shouldBeStrict(! $this->app->isProduction());
```

Lệnh này đồng thời bật 3 cơ chế bảo vệ cực kỳ quan trọng:

1. **`preventLazyLoading()` (Chặn N+1 Queries)**:
   * Khi bạn vô tình truy cập relationship mà chưa eager load (ví dụ `$user->posts`), Laravel sẽ lập tức ném ra ngoại lệ `LazyLoadingViolationException` thay vì âm thầm gửi thêm hàng chục câu query xuống database.
2. **`preventSilentlyDiscardingAttributes()` (Chặn gán lén thuộc tính)**:
   * Khi bạn cố gán một thuộc tính vào Model mà không khai báo trong `$fillable` hoặc vi phạm `$guarded`, Laravel sẽ báo lỗi ngay lập tức.
3. **`preventAccessingMissingAttributes()` (Chặn truy cập thuộc tính không tồn tại)**:
   * Khi bạn dùng `select('id', 'name')` nhưng lại truy cập `$user->email`, Laravel sẽ bắn lỗi `MissingAttributeException` thay vì trả về `null`.

---

## 🛡️ 2. Bảo Vệ Cơ Sở Dữ Liệu Production (Prohibit Destructive Commands)

Một trong những thảm họa lớn nhất là chạy nhầm lệnh xóa database trên production. Để triệt tiêu rủi ro này:

```php
DB::prohibitDestructiveCommands($this->app->isProduction());
```

Khi ở môi trường `production`, Laravel sẽ **chặn đứng hoàn toàn** các lệnh sau:
* `php artisan migrate:fresh`
* `php artisan migrate:reset`
* `php artisan migrate:rollback`
* `php artisan db:wipe`

---

## 🚦 3. Chiến Lược Giới Hạn Tần Suất Truy Cập (Rate Limiting)

Hệ thống thiết lập các bộ lọc Rate Limiter riêng biệt theo từng mức độ nhạy cảm của Endpoint:

| Tên Rate Limiter | Giới Hạn | Khóa Phân Biệt (Key) | Mục Đích Sử Dụng |
| :--- | :--- | :--- | :--- |
| **`api`** | 60 req / phút | `user_id` (nếu đã đăng nhập) hoặc `client_ip` | Áp dụng toàn cục cho tất cả API thông thường. |
| **`login`** | 5 req / phút | `mb_strtolower(email) + '|' + client_ip` | Chống tấn công Brute-force mật khẩu và Credential Stuffing. Trả về mã lỗi 429 và thông báo tiếng Anh chuẩn. |
| **`sensitive`** | 10 req / phút | `user_id` hoặc `client_ip` | Áp dụng cho các tính năng: Đổi mật khẩu, Gửi mã OTP, Thanh toán, Upload file. |

Cách áp dụng Rate Limiter trong Route:
```php
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

Route::post('/account/change-password', [PasswordController::class, 'update'])
    ->middleware(['auth:sanctum', 'throttle:sensitive']);
```

---

## 🌐 4. HTTP Security Headers Middleware

Mọi response trả về cho client đều đi qua [`SecurityHeadersMiddleware`](file:///e:/Project_ItWebDev/PHP/techhub/src/Shared/Infrastructure/Http/Middleware/SecurityHeadersMiddleware.php) để đính kèm các header bảo vệ tiêu chuẩn:

* **`X-Frame-Options: SAMEORIGIN`**: Ngăn chặn kẻ tấn công nhúng website vào iframe để thực hiện tấn công Clickjacking.
* **`X-Content-Type-Options: nosniff`**: Ngăn chặn trình duyệt tự ý phỏng đoán MIME type (MIME Sniffing), tránh thực thi file độc hại.
* **`X-XSS-Protection: 1; mode=block`**: Kích hoạt bộ lọc XSS tích hợp của trình duyệt cũ.
* **`Referrer-Policy: strict-origin-when-cross-origin`**: Bảo vệ thông tin nhạy cảm trong URL khi chuyển hướng sang domain khác.
* **`Permissions-Policy: camera=(), microphone=(), geolocation=()`**: Vô hiệu hóa các quyền phần cứng không cần thiết.
* **`Strict-Transport-Security (HSTS)`**: Ép trình duyệt luôn dùng giao thức HTTPS an toàn khi chạy trên môi trường production.

---

## 🔑 5. Tiêu Chuẩn Mật Khẩu (Password Security Policy)

Dự án áp dụng bộ quy tắc kiểm tra mật khẩu nghiêm ngặt được cấu hình tại `AppServiceProvider`:

```php
Password::defaults(function () {
    $rule = Password::min(8)
        ->letters()
        ->mixedCase()
        ->numbers()
        ->symbols();

    return $this->app->isProduction()
        ? $rule->uncompromised()
        : $rule;
});
```

* Độ dài tối thiểu: **8 ký tự**.
* Bắt buộc có cả **chữ hoa** và **chữ thường**.
* Bắt buộc có ít nhất một **chữ số**.
* Bắt buộc có ít nhất một **ký tự đặc biệt** (`@`, `#`, `$`, `%`...).
* Trên môi trường Production: Kiểm tra mật khẩu có nằm trong cơ sở dữ liệu bị lộ thông qua dịch vụ `HaveIBeenPwned` (`uncompromised()`).

---

## 🔍 6. Correlation ID (`X-Request-Id`) & Truy Vết Log

Mỗi request gửi đến máy chủ đều được [`AssignRequestIdMiddleware`](file:///e:/Project_ItWebDev/PHP/techhub/src/Shared/Infrastructure/Http/Middleware/AssignRequestIdMiddleware.php) gắn một mã UUID độc nhất:

1. Trả về trong Header phản hồi: `X-Request-Id: 9b2d861e-128a-4950-8b1b-74b78fe2fa4d`.
2. Tự động inject vào ngữ cảnh ghi log Monolog (`Log::withContext(['request_id' => ...])`).
3. Đính kèm vào mọi JSON Exception response khi gặp lỗi.

> [!NOTE]
> Nhờ `X-Request-Id`, khi người dùng hoặc frontend báo lỗi, đội ngũ kỹ thuật chỉ cần copy mã này và tìm kiếm trực tiếp trong hệ thống Log (Kibana, CloudWatch, Sentry) là sẽ thấy toàn bộ vết thực thi của request đó.
