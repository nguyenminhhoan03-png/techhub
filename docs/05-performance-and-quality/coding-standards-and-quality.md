# 📏 05. Tiêu Chuẩn Viết Code & Đảm Bảo Chất Lượng (Code Standards)

Chất lượng mã nguồn tại **TechHub** được duy trì thông qua các quy chuẩn nghiêm ngặt và hệ thống kiểm tra tự động trước khi merge code vào nhánh chính.

---

## 🎯 1. Quy Chuẩn PHP & Strict Typing

1. **Khai báo `strict_types=1`**: Mọi file PHP trong dự án bắt buộc phải có `declare(strict_types=1);` ở ngay đầu file.
2. **Khai báo kiểu dữ liệu tường minh (Explicit Typehints)**:
   * Tất cả tham số hàm, thuộc tính class và giá trị trả về phải có typehint rõ ràng (`string`, `int`, `bool`, `array`, `?User`, `void`).
   * Hạn chế tối đa sử dụng `mixed`.
3. **Sử dụng PHP 8.2+ Features**:
   * Constructor Property Promotion:
     ```php
     public function __construct(
         private readonly UserRepositoryContract $userRepository,
         private readonly CommandBusContract $commandBus,
     ) {}
     ```
   * Enums cho các giá trị cố định.
   * Readonly classes hoặc readonly properties cho DTOs và Value Objects.

---

## 🎨 2. Định Dạng Mã Nguồn Tự Động (Laravel Pint)

Dự án sử dụng **Laravel Pint** (dựa trên PHP-CS-Fixer) để tự động định dạng mã nguồn theo chuẩn PSR-12 và Laravel Style.

* **Kiểm tra style (không sửa file)**:
  ```bash
  vendor/bin/pint --test
  ```
* **Tự động format toàn bộ mã nguồn**:
  ```bash
  vendor/bin/pint
  ```

---

## 🔍 3. Phân Tích Tĩnh (Static Analysis - PHPStan / Larastan)

Dự án cấu hình **PHPStan** ở mức độ kiểm tra nghiêm ngặt (`level: 5` hoặc cao hơn trong [`phpstan.neon`](file:///e:/Project_ItWebDev/PHP/techhub/phpstan.neon)).

* **Chạy phân tích**:
  ```bash
  vendor/bin/phpstan analyse
  ```
* Mọi Pull Request (PR) phải vượt qua PHPStan với kết quả `[OK] No errors`.

---

## 🧪 4. Kiểm Thử Tự Động Với Pest PHP

Hệ thống kiểm thử được chia làm 3 loại chính:

### 1. Unit Tests (`tests/Unit/`)
Kiểm tra các hàm tính toán, Value Objects, Domain Logic độc lập mà không cần khởi động database hay framework.

### 2. Feature Tests (`tests/Feature/`)
Kiểm tra toàn bộ luồng API từ HTTP Request, Validation, Database Insertion đến HTTP Response Status Code.

### 3. Architecture Tests (`tests/Architecture/`)
Kiểm tra cấu trúc phân tầng, cấm gọi chéo giữa các layer Clean Architecture, cấm hàm debug `dd()`.

* **Chạy toàn bộ test suite**:
  ```bash
  vendor/bin/pest
  ```
* **Chạy kèm báo cáo độ phủ mã nguồn (Coverage)**:
  ```bash
  vendor/bin/pest --coverage
  ```

---

## 🌿 5. Quy Trình Git & Commit Convention

Dự án tuân theo quy chuẩn **Conventional Commits**:

```
<type>(<scope>): <mô tả ngắn gọn bằng tiếng Anh hoặc tiếng Việt>
```

* **`feat`**: Tính năng mới (ví dụ: `feat(user): add user registration command handler`)
* **`fix`**: Sửa lỗi (ví dụ: `fix(auth): fix password rate limiter key lowercase`)
* **`refactor`**: Tái cấu trúc mã nguồn không làm thay đổi hành vi logic
* **`perf`**: Cải thiện hiệu năng (ví dụ: `perf(query): add index on email column`)
* **`docs`**: Cập nhật tài liệu kỹ thuật
* **`test`**: Bổ sung hoặc sửa đổi bài kiểm thử

### Checklist Trước Khi Tạo Pull Request (PR)
1. [ ] Đã chạy `vendor/bin/pint` để format code.
2. [ ] Đã chạy `vendor/bin/phpstan analyse` và không còn lỗi.
3. [ ] Đã chạy `vendor/bin/pest` và toàn bộ test đều pass.
4. [ ] Không để sót các lệnh `dd()`, `dump()`, `console.log()` hoặc code thừa.
