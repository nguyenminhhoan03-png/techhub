<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Domain\Tool\Enums\ToolEngineType;
use Illuminate\Database\Seeder;

class DeveloperSuiteToolsSeeder extends Seeder
{
    public function run(): void
    {
        $categoryDev = ToolCategory::where('slug', 'developer')->first();
        $categoryImage = ToolCategory::where('slug', 'image')->first();
        $categoryPdf = ToolCategory::where('slug', 'pdf')->first();

        if ( ! $categoryDev) {
            $categoryDev = ToolCategory::create([
                'slug' => 'developer',
                'name' => 'Công cụ Lập trình',
                'description' => 'Bộ tiện ích trực tuyến cho lập trình viên.',
                'icon' => 'code-xml',
                'sort_order' => 1,
                'is_active' => true,
            ]);
        }

        if ( ! $categoryImage) {
            $categoryImage = ToolCategory::create([
                'slug' => 'image',
                'name' => 'Xử lý & Phân tích Ảnh',
                'description' => 'Tiện ích xử lý và nén ảnh trực tuyến.',
                'icon' => 'image',
                'sort_order' => 3,
                'is_active' => true,
            ]);
        }

        if ( ! $categoryPdf) {
            $categoryPdf = ToolCategory::create([
                'slug' => 'pdf',
                'name' => 'Công cụ PDF',
                'description' => 'Tiện ích chuyển đổi tài liệu PDF sang Excel và bảng tính.',
                'icon' => 'file-text',
                'sort_order' => 4,
                'is_active' => true,
            ]);
        }

        $tools = [
            [
                'category_id' => $categoryDev->id,
                'slug' => 'json-to-typescript',
                'name' => 'Chuyển Đổi JSON sang TypeScript (Interface / Type)',
                'summary' => 'Chuyển đổi dữ liệu JSON thô thành Interface hoặc Type TypeScript an toàn kiểu dữ liệu, tự động bóc tách các object lồng nhau.',
                'icon' => 'code',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'JSON to TypeScript Converter Online — Tạo Interface & Type Chuẩn | TechHub',
                'meta_description' => 'Công cụ chuyển đổi JSON sang TypeScript interface hoặc type tự động, hỗ trợ nested types, optional fields, định dạng code đẹp.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Công Cụ JSON to TypeScript
Công cụ **JSON to TypeScript** của TechHub hỗ trợ lập trình viên Frontend & Fullstack tự động sinh mã nguồn **TypeScript Interfaces** hoặc **Type Aliases** từ bất kỳ cấu trúc JSON thô nào (kết quả trả về từ REST API, Payload Webhook, tệp cấu hình...).

### ✨ Tính Năng Nổi Bật:
* **Tự động bóc tách kiểu lồng nhau (Nested Interfaces)**: Phân tích đệ quy các object con để tạo interface riêng biệt có tên tương ứng.
* **Suy luận kiểu chuẩn xác**: Hỗ trợ đầy đủ `string`, `number`, `boolean`, `null`, `any[]`, và union types `(string | number)[]`.
* **Tùy biến Root Type Name**: Đặt tên interface gốc linh hoạt (mặc định `RootObject` hoặc tùy chỉnh).

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao nên chuyển JSON sang TypeScript Interface?
Giúp code của bạn được kiểm tra kiểu tĩnh (Static Type Checking), tránh lỗi undefined runtime và tận dụng tính năng tự động gợi ý code (IntelliSense) của IDE.

### 2. Các thuộc tính có thể là null được xử lý thế nào?
Nếu giá trị trong JSON là `null`, công cụ sẽ gán kiểu `null` hoặc `unknown` để bạn dễ dàng khai báo union type `string | null`.

### 3. Công cụ có bảo mật dữ liệu JSON của tôi không?
Có. Toàn bộ quá trình chuyển đổi được thực thi trong bộ nhớ RAM tạm thời và không lưu trữ bất kỳ log dữ liệu nào lên server.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'json-to-php',
                'name' => 'Chuyển Đổi JSON sang PHP Array & DTO Class',
                'summary' => 'Chuyển đổi dữ liệu JSON sang cú pháp mảng ngắn PHP [...] hoặc sinh Class Data Transfer Object (DTO) chuẩn PHP 8.2+ Typed Properties.',
                'icon' => 'code-xml',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'JSON to PHP Array & DTO Generator — Chuẩn PHP 8.2+ | TechHub',
                'meta_description' => 'Chuyển đổi JSON sang mảng PHP hoặc DTO Class với Constructor Property Promotion và typed parameters tự động.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Công Cụ JSON to PHP
Chuyển đổi nhanh chuỗi JSON thành cú pháp mảng hiện đại trong PHP hoặc tạo các lớp **DTO (Data Transfer Object)** chuẩn PHP 8.2+ với Constructor Property Promotion và phương thức `fromArray()`.

### ✨ Tính Năng Nổi Bật:
* **Cú pháp mảng ngắn**: Tạo mảng `['key' => 'value']` với thụt lề chuẩn PSR-12.
* **Sinh DTO Class hoàn chỉnh**: `readonly class UserDto` với các thuộc tính khai báo kiểu rõ ràng (`public readonly string \$name`).
* **Hàm khởi tạo từ mảng**: Tự động sinh hàm `fromArray(array \$data): self` an toàn.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. DTO sinh ra tương thích với phiên bản PHP nào?
Mã nguồn DTO được sinh theo chuẩn PHP 8.2 và PHP 8.3 trở lên, sử dụng `final readonly class` và Constructor Property Promotion.

### 2. Sự khác nhau giữa mảng thông thường và DTO là gì?
DTO mang lại sự an toàn tuyệt đối về kiểu dữ liệu (Type-safety), trong khi mảng PHP thuần dễ gặp lỗi gõ nhầm key hoặc sai kiểu dữ liệu runtime.

### 3. Có thể dùng DTO này trong Laravel được không?
Hoàn toàn được. Bạn có thể đặt class này vào thư mục `app/DTOs` hoặc `src/Domain/.../Data` trong dự án Laravel của mình.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'sql-formatter',
                'name' => 'Định Dạng & Làm Đẹp Câu Lệnh SQL (SQL Formatter)',
                'summary' => 'Làm đẹp, thụt đầu dòng, viết hoa từ khóa và nén gọn câu lệnh SQL (MySQL, PostgreSQL, SQLite, SQL Server) trực tuyến.',
                'icon' => 'database',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'SQL Formatter Online — Định Dạng & Làm Đẹp Truy Vấn SQL | TechHub',
                'meta_description' => 'Công cụ format SQL online miễn phí: thụt lề câu lệnh, viết hoa từ khóa SELECT, FROM, WHERE, JOIN, hỗ trợ MySQL, PostgreSQL.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu SQL Formatter & Beautifier
Các câu lệnh truy vấn SQL phức tạp khi copy từ log hoặc code thường bị dồn thành 1 dòng hoặc trình bày lộn xộn. **SQL Formatter** giúp bạn định dạng lại cú pháp rõ ràng, dễ đọc và tối ưu cho việc debug.

### ✨ Tính Năng Nổi Bật:
* **Viết hoa từ khóa tự động**: Tự động chuyển các mệnh đề `SELECT`, `FROM`, `WHERE`, `JOIN`, `GROUP BY`, `ORDER BY` thành chữ hoa chuẩn conventions.
* **Thụt lề phân cấp**: Tùy chỉnh thụt lề 2 hoặc 4 spaces.
* **Chế độ Minify**: Nén câu lệnh thành 1 dòng duy nhất để nhúng vào file config hoặc biến môi trường.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Công cụ hỗ trợ những hệ quản trị CSDL nào?
Hỗ trợ tất cả các hệ quản trị chuẩn ANSI SQL: MySQL, MariaDB, PostgreSQL, SQLite, Oracle và Microsoft SQL Server.

### 2. Có ảnh hưởng đến tham số hoặc chuỗi ký tự trong truy vấn không?
Không. Thuật toán giữ nguyên vẹn các giá trị chuỗi nằm trong dấu nháy đơn hoặc nháy kép.

### 3. Có thể format câu lệnh CREATE TABLE hay ALTER TABLE không?
Có. Công cụ hỗ trợ đầy đủ cả DDL (`CREATE`, `ALTER`, `DROP`) lẫn DML (`SELECT`, `INSERT`, `UPDATE`, `DELETE`).
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'sql-to-laravel-migration',
                'name' => 'Chuyển Đổi SQL CREATE TABLE sang Laravel Migration',
                'summary' => 'Chuyển đổi các câu lệnh SQL CREATE TABLE thành tệp Migration Schema::create của Laravel với đầy đủ kiểu dữ liệu, index và foreign key.',
                'icon' => 'arrow-right-left',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'SQL to Laravel Migration Generator Online | TechHub',
                'meta_description' => 'Chuyển đổi cú pháp SQL CREATE TABLE sang file migration Laravel Schema::create tự động với foreignId, timestamps, softDeletes.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu SQL to Laravel Migration
Công cụ hỗ trợ các nhà phát triển Laravel chuyển đổi nhanh chóng các bảng CSDL được xuất ra từ phpMyAdmin, Navicat, DBeaver hoặc MySQL Workbench thành các tệp **Laravel Migration** chuẩn mực.

### ✨ Tính Năng Nổi Bật:
* **Ánh xạ kiểu dữ liệu thông minh**: `varchar` -> `string()`, `tinyint(1)` -> `boolean()`, `decimal` -> `decimal()`, `json` -> `json()`.
* **Tự động nhận diện khóa ngoại**: Các cột kết thúc bằng `_id` được chuyển thành `$table->foreignId('...')->constrained()`.
* **Khung class hiện đại**: Sinh Anonymous Migration class theo chuẩn Laravel 10, 11 và 12 (`return new class extends Migration`).

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Công cụ có xử lý được ràng buộc DEFAULT và NULL không?
Có. Các modifier như `->nullable()`, `->default('...')`, `->unique()` đều được tự động phân tích và chuyển đổi chính xác.

### 2. timestamps() và softDeletes() có được hỗ trợ không?
Nếu trong câu SQL có cột `created_at`/`updated_at` hoặc `deleted_at`, công cụ sẽ tự động thay thế bằng `$table->timestamps()` và `$table->softDeletes()`.

### 3. Làm thế nào để áp dụng file migration này vào dự án?
Bạn chỉ cần tạo file mới trong `database/migrations/xxxx_create_table.php` và dán toàn bộ đoạn mã đã sinh ra vào.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'sql-to-laravel-model',
                'name' => 'Chuyển Đổi SQL sang Laravel Eloquent Model',
                'summary' => 'Tự động tạo Model Eloquent PHP hoàn chỉnh từ câu lệnh SQL CREATE TABLE với \$fillable, hàm casts(): array và relationship BelongsTo.',
                'icon' => 'box',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'SQL to Laravel Eloquent Model Generator | TechHub',
                'meta_description' => 'Sinh Model Laravel Eloquent từ SQL: tự động gán \$fillable, casts() methods, quan hệ belongsTo theo chuẩn Laravel 11/12.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu SQL to Laravel Model
Giúp lập trình viên Laravel tiết kiệm thời gian gõ lặp lại các thuộc tính `$fillable`, danh sách `$casts` và các hàm quan hệ (Relationships) khi tạo Model từ cấu trúc bảng CSDL có sẵn.

### ✨ Tính Năng Nổi Bật:
* **Tự động đặt tên Model**: Tự động chuyển đổi tên bảng số nhiều sang dạng số ít PascalCase (ví dụ: `order_items` -> `OrderItem`).
* **Hàm `casts(): array` thế hệ mới**: Tuân thủ chuẩn Laravel 11/12, tự động cast các trường boolean, json, decimal, datetime.
* **Tự động sinh quan hệ BelongsTo**: Phát hiện cột `user_id`, `category_id` để sinh hàm quan hệ `$this->belongsTo(...)`.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Hàm casts() mới trong Laravel 11/12 có gì khác với thuộc tính \$casts cũ?
Trong Laravel 11/12, phương thức `protected function casts(): array` được khuyến nghị thay thế cho thuộc tính `$casts`, cho phép sử dụng Enums và closure casts linh hoạt hơn.

### 2. Các trường khóa chính và timestamps có bị đưa vào \$fillable không?
Không. Các trường `id`, `created_at`, `updated_at`, `deleted_at` được tự động loại trừ khỏi `$fillable` để đảm bảo an toàn Mass Assignment.

### 3. Model có hỗ trợ SoftDeletes không?
Nếu bảng có chứa cột `deleted_at`, công cụ sẽ tự động thêm trait `use SoftDeletes;` và import tương ứng.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'laravel-crud-generator',
                'name' => 'Trình Tạo Khung Laravel CRUD RESTful Hoàn Chỉnh',
                'summary' => 'Sinh toàn bộ cấu trúc CRUD: Migration, Model, Form Requests, API Controller với 5 hàm chuẩn RESTful và API Resource.',
                'icon' => 'cpu',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'Laravel CRUD Generator Online — Tạo Scaffolding RESTful API | TechHub',
                'meta_description' => 'Tự động sinh trọn bộ scaffolding Laravel: Migration, Eloquent Model, Form Request, API Controller và API Resource chuẩn Senior.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Laravel CRUD Generator
Tạo nhanh chóng toàn bộ mã nguồn cần thiết cho một chức năng CRUD (Create, Read, Update, Delete) hoàn chỉnh trong ứng dụng Laravel, tuân thủ kiến trúc Clean Code và chuẩn RESTful API.

### ✨ Các Tệp Được Tạo Ra:
1. **Migration**: `Schema::create` với đầy đủ các cột và kiểu dữ liệu.
2. **Model**: Class Eloquent với `$fillable`.
3. **Form Request**: `StoreRequest` với các quy tắc validation tương ứng với từng kiểu trường.
4. **API Controller**: Cài đặt sẵn `index()`, `store()`, `show()`, `update()`, `destroy()` chuẩn JSON Envelope.
5. **API Resource**: `JsonResource` định dạng dữ liệu trả về cho Frontend.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Định dạng khai báo trường (Fields) như thế nào?
Bạn nhập danh sách trường cách nhau bằng dấu phẩy theo cú pháp: `tên_trường:kiểu_dữ_liệu`. Ví dụ: `title:string, price:decimal, content:text, is_active:boolean, category_id:foreignId`.

### 2. Controller sinh ra có hỗ trợ phân trang không?
Có. Phương thức `index()` mặc định gọi `latest()->paginate(20)` kèm cấu trúc metadata phân trang.

### 3. Mã nguồn sinh ra có cần sửa đổi gì thêm không?
Bạn chỉ cần tùy chỉnh thêm logic nghiệp vụ đặc thù hoặc quyền hạn (Authorization Policy) trước khi đưa vào chạy thực tế.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'uuid-generator',
                'name' => 'Trình Tạo Mã UUID & ULID Hàng Loạt',
                'summary' => 'Sinh ngẫu nhiên UUID v4, UUID v7 sắp xếp theo thời gian, UUID v1 và chuỗi định danh ULID với tùy chọn số lượng và định dạng gạch nối.',
                'icon' => 'fingerprint',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'UUID & ULID Bulk Generator Online — Tạo Định Danh Bảo Mật | TechHub',
                'meta_description' => 'Công cụ tạo UUID v4 ngẫu nhiên, UUID v7 theo thời gian và ULID hàng loạt miễn phí với độ ngẫu nhiên mã hóa cao.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu UUID & ULID Generator
**UUID (Universally Unique Identifier)** và **ULID (Universally Unique Lexicographically Sortable Identifier)** là các chuẩn định danh duy nhất toàn cầu, chống trùng lặp tuyệt đối khi làm việc với hệ thống phân tán và CSDL lớn.

### ✨ Tính Năng Nổi Bật:
* **UUID v4**: Ngẫu nhiên hoàn toàn (Cryptographically secure).
* **UUID v7**: Chuẩn thế hệ mới kết hợp dấu thời gian Unix timestamp ở phần đầu, giúp tối ưu hóa B-Tree Indexing trong CSDL.
* **ULID**: Độ dài 26 ký tự, có thể sắp xếp tự nhiên theo thời gian.
* **Tùy biến linh hoạt**: Tùy chọn chữ hoa/thường, bật/tắt dấu gạch nối `-` và sinh số lượng từ 1 đến 50 mã cùng lúc.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao nên dùng UUID v7 thay cho UUID v4 trong Database?
UUID v4 phân tán ngẫu nhiên nên gây phân mảnh chỉ mục (Index Fragmentation) khi INSERT dữ liệu lớn. UUID v7 được sắp xếp tuần tự theo thời gian, khắc phục hoàn toàn nhược điểm này.

### 2. Tỷ lệ trùng lặp của UUID v4 là bao nhiêu?
Xác suất để xảy ra va chạm (collision) giữa 2 mã UUID v4 là gần như bằng 0 (khoảng $1 / 10^{36}$).

### 3. ULID có điểm gì vượt trội hơn UUID?
ULID chỉ dài 26 ký tự (ngắn hơn 36 ký tự của UUID), sử dụng bảng chữ cái Crockford Base32 an toàn cho URL và không phân biệt chữ hoa/thường.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'cron-generator',
                'name' => 'Trình Tạo & Dịch Biểu Thức Cron (Cron Generator)',
                'summary' => 'Tạo biểu thức Cron 5 trường trực quan, dịch ngược cú pháp Cron sang tiếng Việt/Anh và tính toán lịch chạy tự động tiếp theo.',
                'icon' => 'clock',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'Cron Expression Generator & Explainer Online | TechHub',
                'meta_description' => 'Tạo và giải thích biểu thức Cron định kỳ tự động, hỗ trợ cú pháp Laravel Task Scheduling và tính toán các mốc chạy kế tiếp.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Cron Expression Generator
**Cron** là tiện ích quản lý lịch trình chạy tác vụ tự động trên Linux và các framework web hiện đại. Công cụ giúp bạn cấu hình biểu thức 5 trường (`Phút Giờ Ngày Tháng Thứ`) trực quan mà không lo sai cú pháp.

### ✨ Tính Năng Nổi Bật:
* **Chế độ Generator**: Chọn nhanh các lịch trình thông dụng (Mỗi phút, Mỗi giờ, 0h đêm mỗi ngày, Cuối tuần, Ngày trong tuần...).
* **Chế độ Explainer**: Nhập bất kỳ biểu thức cron nào (ví dụ `*/15 * * * *` hoặc `0 4 * * 1-5`) để nhận lời giải thích chi tiết bằng tiếng Việt.
* **Tích hợp Laravel**: Tự động sinh cú pháp lệnh `$schedule->command('...')->dailyAt('04:00');`.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Cấu trúc 5 trường của Cron biểu thị những gì?
Thứ tự từ trái sang phải gồm: `[Phút] [Giờ] [Ngày trong tháng] [Tháng] [Thứ trong tuần (0-7, 0 là Chủ nhật)]`.

### 2. Ký hiệu dấu sao (*) và dấu gạch chéo (/) có ý nghĩa gì?
Dấu sao `*` nghĩa là "mọi giá trị". Dấu gạch chéo `/` nghĩa là bước nhảy (ví dụ `*/5` ở cột phút nghĩa là mỗi 5 phút).

### 3. Làm thế nào để chạy tác vụ Cron trong Laravel?
Trên máy chủ Linux / VPS, bạn chỉ cần cấu hình 1 dòng cron duy nhất: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'html-formatter',
                'name' => 'Định Dạng & Nén Gọn Mã HTML (HTML Formatter)',
                'summary' => 'Làm đẹp, thụt dòng phân cấp các thẻ DOM hoặc nén gọn mã nguồn HTML, loại bỏ comment và tối ưu dung lượng trang web.',
                'icon' => 'file-code',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'HTML Formatter & Minifier Online — Làm Đẹp & Nén HTML | TechHub',
                'meta_description' => 'Công cụ format và minify HTML trực tuyến: làm đẹp mã nguồn, thụt lề thẻ DOM, nén gọn HTML giúp tăng tốc độ tải trang.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu HTML Formatter & Minifier
Hỗ trợ lập trình viên web căn chỉnh lại các đoạn mã HTML rối mắt thành cấu trúc cây DOM thụt lề chuẩn mực, hoặc nén gọn tối đa kích thước mã nguồn trước khi xuất bản lên môi trường Production.

### ✨ Tính Năng Nổi Bật:
* **Làm đẹp chuẩn DOM**: Tự động nhận diện cấu trúc phân cấp thẻ lồng nhau, thụt lề 2 hoặc 4 khoảng trắng.
* **Bảo toàn thẻ đặc biệt**: Không làm hỏng nội dung bên trong các thẻ `<pre>`, `<code>`, `<script>`, `<style>` và `<textarea>`.
* **Nén gọn (Minify)**: Loại bỏ toàn bộ ghi chú và khoảng trắng dư thừa, hiển thị tỷ lệ phần trăm dung lượng tiết kiệm được.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Nén HTML có giúp tăng tốc độ website không?
Có. Giảm bớt dung lượng HTML truyền tải qua mạng giúp trình duyệt tải về nhanh hơn và cải thiện điểm số Google PageSpeed / Lighthouse.

### 2. Công cụ có loại bỏ các đoạn ghi chú (Comments) không?
Trong chế độ Minify, toàn bộ các comment dạng `<!-- ... -->` sẽ được loại bỏ sạch sẽ (trừ các thẻ điều kiện IE hợp lệ).

### 3. Mã HTML bị thiếu thẻ đóng có format được không?
Bộ phân tích cú pháp HTML5 tích hợp sẽ tự động phát hiện và xử lý an toàn các thẻ tự đóng (void tags) như `<img>`, `<br>`, `<input>`.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'css-minifier',
                'name' => 'Nén & Tối Ưu Bảng Kiểu CSS (CSS Minifier)',
                'summary' => 'Nén gọn file CSS, loại bỏ chú thích, thu gọn mã màu HEX và khoảng trắng thừa, hoặc giải nén làm đẹp file CSS bị nén.',
                'icon' => 'file-spreadsheet',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'CSS Minifier & Beautifier Online — Nén & Tối Ưu CSS | TechHub',
                'meta_description' => 'Công cụ nén CSS online miễn phí: loại bỏ comment, rút gọn mã màu, tối ưu dung lượng stylesheet giúp web tải nhanh hơn.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu CSS Minifier
Tối ưu hóa các file stylesheet CSS bằng cách loại bỏ comment `/* ... */`, các khoảng trắng thừa, dấu chấm phẩy không cần thiết và thu gọn mã màu, giúp giảm tải băng thông và tăng tốc độ kết xuất CSS của trình duyệt.

### ✨ Tính Năng Nổi Bật:
* **Tối ưu mã màu**: Rút gọn các mã màu 6 ký tự sang 3 ký tự (ví dụ `#ffffff` -> `#fff`).
* **Khử đơn vị số 0**: Tự động chuyển `0px`, `0em`, `0rem` thành `0`.
* **Chế độ Beautify**: Mở rộng các rule CSS bị gom trên 1 dòng thành nhiều dòng có thụt lề trực quan.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Nén CSS có làm thay đổi giao diện website không?
Hoàn toàn không. Quá trình nén chỉ loại bỏ khoảng trắng và comment vô nghĩa đối với trình duyệt, giữ nguyên 100% logic hiển thị của các thuộc tính CSS.

### 2. Tỷ lệ tiết kiệm dung lượng trung bình là bao nhiêu?
Tùy thuộc vào số lượng comment và khoảng trắng ban đầu, mức giảm dung lượng thường đạt từ **20% đến 45%**.

### 3. Có thể nén code SCSS hoặc SASS bằng công cụ này không?
Bạn nên biên dịch SCSS/SASS sang CSS thuần trước khi đưa vào công cụ để đạt hiệu quả nén tốt nhất.
MD,
            ],
            [
                'category_id' => $categoryImage->id,
                'slug' => 'image-compressor',
                'name' => 'Nén & Chuyển Đổi Ảnh Thông Minh (WebP / JPEG / PNG)',
                'summary' => 'Nén dung lượng ảnh kỹ thuật số, tùy chỉnh mức chất lượng (Quality), thay đổi kích thước và chuyển đổi sang định dạng WebP thế hệ mới.',
                'icon' => 'image',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'Image Compressor Online — Nén Ảnh WebP, PNG, JPEG | TechHub',
                'meta_description' => 'Nén ảnh trực tuyến không giảm chất lượng: giảm dung lượng ảnh WebP, JPEG, PNG, xem trước before/after, tối ưu SEO ảnh.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Smart Image Compressor
Tối ưu hóa hình ảnh là yếu tố quan trọng hàng đầu để đạt điểm xanh trên Google PageSpeed Insights và Core Web Vitals (LCP). Công cụ giúp nén ảnh nhanh chóng, giữ lại độ nét tối đa và chuyển đổi sang định dạng **WebP** hiện đại.

### ✨ Tính Năng Nổi Bật:
* **Hỗ trợ định dạng WebP**: WebP giúp giảm từ **40% đến 80%** dung lượng so với JPEG/PNG gốc mà mắt thường khó nhận ra sự khác biệt.
* **Tùy chỉnh chất lượng linh hoạt**: Kéo thanh trượt từ 10% đến 100% để cân bằng giữa dung lượng tệp và độ chi tiết của ảnh.
* **Hỗ trợ Resize tự động**: Thu nhỏ chiều rộng tối đa (Max Width) mà vẫn giữ nguyên tỷ lệ khung hình gốc (Aspect Ratio).

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao nên sử dụng định dạng ảnh WebP cho website?
WebP được Google phát triển đặc biệt cho web, cung cấp khả năng nén vượt trội cả về Lossless (không mất dữ liệu) lẫn Lossy (có mất dữ liệu) và được hỗ trợ trên 97% trình duyệt hiện nay.

### 2. Ảnh sau khi nén có bị xóa nền trong suốt (Transparency) không?
Nếu bạn chọn định dạng WebP hoặc PNG, kênh độ trong suốt (Alpha Channel) sẽ được bảo toàn nguyên vẹn.

### 3. Tệp ảnh tải lên có bị lưu giữ lại trên máy chủ không?
Không. Ảnh chỉ được nạp vào bộ nhớ đệm RAM để xử lý và trả về dưới dạng DataURL, không lưu trữ file trên ổ cứng máy chủ.
MD,
            ],
            [
                'category_id' => $categoryPdf->id,
                'slug' => 'pdf-to-excel',
                'name' => 'Chuyển Đổi Bảng Biểu PDF sang Excel / CSV',
                'summary' => 'Bóc tách cấu trúc dòng cột và bảng dữ liệu từ tài liệu PDF thành bảng tính tương thích Excel (.csv / .xlsx) tải về 1-click.',
                'icon' => 'table',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'PDF to Excel / CSV Converter Online — Chuyển Bảng PDF Sang Excel | TechHub',
                'meta_description' => 'Trích xuất bảng dữ liệu và số liệu từ tài liệu PDF sang file Excel CSV trực tuyến nhanh chóng, chính xác, không cần cài phần mềm.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu PDF to Excel Converter
Giải quyết bài toán bóc tách dữ liệu từ các báo cáo tài chính, hóa đơn, bảng kê số liệu lưu trong tệp PDF thành bảng tính Excel có thể chỉnh sửa và tính toán công thức.

### ✨ Tính Năng Nổi Bật:
* **Tự động nhận diện phân cách cột**: Hỗ trợ phân cách bằng ký tự Tab, nhiều khoảng trắng liên tiếp, dấu phẩy hoặc ký tự phân tách `|`.
* **Hiển thị bảng xem trước (Interactive Preview Table)**: Cho phép kiểm tra trực tiếp dữ liệu trước khi tải về máy.
* **Xuất file CSV chuẩn**: Tương thích 100% với Microsoft Excel, Google Sheets, LibreOffice Calc.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Làm thế nào để lấy dữ liệu từ file PDF đưa vào đây?
Bạn mở file PDF, quét chọn (bôi đen) vùng bảng dữ liệu cần chuyển đổi, nhấn `Ctrl + C` để sao chép rồi dán vào khung dữ liệu của công cụ.

### 2. Các ký tự tiếng Việt có dấu có bị lỗi font khi mở trong Excel không?
File CSV được xuất với bảng mã UTF-8 chuẩn mực, đảm bảo hiển thị hoàn hảo các ký tự tiếng Việt khi mở trên Excel hoặc Google Sheets.

### 3. Dữ liệu bảng lớn có bị giới hạn số dòng không?
Công cụ hỗ trợ xử lý hàng ngàn dòng dữ liệu một cách mượt mà và hiển thị bản xem trước 30 dòng đầu tiên.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'csv-to-json',
                'name' => 'Chuyển Đổi Dữ Liệu CSV sang JSON',
                'summary' => 'Chuyển đổi bảng dữ liệu CSV thành mảng JSON đối tượng (Key-Value) với khả năng tự động nhận diện kiểu số, boolean và null.',
                'icon' => 'arrow-right-left',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'CSV to JSON Converter Online — Chuyển Bảng CSV Sang JSON | TechHub',
                'meta_description' => 'Chuyển đổi file CSV sang JSON array và object trực tuyến, tự động ép kiểu dữ liệu số, hỗ trợ nhiều loại dấu phân cách.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu CSV to JSON Converter
Chuyển đổi dữ liệu bảng tính xuất ra từ Excel (dạng CSV) thành định dạng JSON chuẩn để nạp vào cơ sở dữ liệu NoSQL (MongoDB), viết API hoặc cấu hình dữ liệu Seeder cho ứng dụng.

### ✨ Tính Năng Nổi Bật:
* **Nhận diện dòng tiêu đề (Headers)**: Tự động lấy dòng đầu tiên làm key cho các object JSON con.
* **Ép kiểu dữ liệu thông minh**: Tự động chuyển đổi các giá trị số (`123`, `45.6`), boolean (`true`, `false`) và `null` thay vì để tất cả dưới dạng chuỗi string.
* **Đa dạng dấu phân cách**: Hỗ trợ dấu phẩy `,`, chấm phẩy `;`, tab `\t` và dấu gạch đứng `|`.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Dữ liệu CSV không có dòng tiêu đề có chuyển đổi được không?
Có. Nếu bạn bỏ chọn mục "Dòng đầu tiên là tiêu đề", công cụ sẽ sinh mảng JSON 2 chiều chứa các giá trị theo thứ tự cột.

### 2. Dữ liệu có chứa dấu phẩy bên trong dấu ngoặc kép có bị tách nhầm không?
Không. Thuật toán tuân thủ đặc tả RFC 4180 về CSV, giữ nguyên chuỗi văn bản nằm trong cặp dấu ngoặc kép.

### 3. Kết quả JSON có được làm đẹp (Pretty print) không?
Có. Kết quả JSON được format thụt lề 2 spaces rõ ràng và có nút sao chép 1-click tiện lợi.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'xml-to-json',
                'name' => 'Chuyển Đổi Tài Liệu XML sang JSON',
                'summary' => 'Phân tích cây phần tử XML, bóc tách thuộc tính và chuyển đổi thành cấu trúc JSON chuẩn mực, hỗ trợ RSS feeds và sitemap.',
                'icon' => 'file-code',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'XML to JSON Converter Online — Chuyển Đổi XML Sang JSON | TechHub',
                'meta_description' => 'Công cụ chuyển đổi XML sang JSON trực tuyến nhanh chóng, bóc tách các node con và thuộc tính, hỗ trợ định dạng đẹp.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu XML to JSON Converter
XML là định dạng truyền thống được sử dụng nhiều trong các dịch vụ SOAP, RSS Feed và tệp cấu hình. **XML to JSON** giúp bạn dễ dàng chuyển đổi sang JSON để tiêu thụ trong các framework JavaScript hiện đại (React, Vue, Node.js).

### ✨ Tính Năng Nổi Bật:
* **Kiểm tra lỗi cú pháp (Syntax Validation)**: Báo lỗi chính xác dòng và cột nếu XML bị thiếu thẻ đóng hoặc sai định dạng.
* **Hỗ trợ CDATA**: Xử lý mượt mà các khối dữ liệu đặc biệt `<![CDATA[...]]>`.
* **Tùy chọn Minify / Pretty**: Cho phép xuất JSON đẹp hoặc gom thành 1 dòng nén gọn.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Các thuộc tính (attributes) của thẻ XML được chuyển sang JSON như thế nào?
Các thuộc tính sẽ được đưa vào object con tương ứng với phần tử hoặc ánh xạ trực tiếp thành các thuộc tính của object.

### 2. Công cụ có chuyển đổi được RSS Feed hoặc Sitemap XML không?
Hoàn toàn được. Bạn có thể dán toàn bộ mã nguồn XML của file `sitemap.xml` hoặc `feed.xml` để nhận kết quả JSON.

### 3. XML kích thước lớn có xử lý được không?
Có. Bộ phân tích SimpleXML hiệu năng cao của PHP xử lý các tài liệu XML dung lượng hàng megabytes trong vòng vài mili-giây.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'api-tester',
                'name' => 'Kiểm Tra & Gọi Thử RESTful API (API Tester)',
                'summary' => 'Gửi yêu cầu HTTP (GET, POST, PUT, DELETE) trực tuyến, kiểm tra mã trạng thái, độ trễ phản hồi (ms), headers và format JSON body.',
                'icon' => 'globe',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'REST API Tester Online — Kiểm Tra Endpoint HTTP Trực Tuyến | TechHub',
                'meta_description' => 'Công cụ test API trực tuyến miễn phí: gửi request GET, POST, PUT, DELETE, đo độ trễ response time, kiểm tra headers và JSON body.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu REST API Tester
Một giải pháp Client HTTP trực tuyến tinh gọn (tương tự Postman rút gọn) giúp bạn kiểm tra nhanh chóng các API Endpoint công khai mà không cần cài đặt phần mềm nặng nề.

### ✨ Tính Năng Nổi Bật:
* **Đầy đủ phương thức HTTP**: Hỗ trợ `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `HEAD`.
* **Đo độ trễ chính xác**: Đo lường chính xác thời gian phản hồi của máy chủ theo mili-giây (Latency ms).
* **Bảo vệ chống tấn công SSRF (Server-Side Request Forgery)**: Tự động ngăn chặn các yêu cầu gửi đến dải IP nội bộ hoặc loopback để bảo vệ hệ thống.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Có thể gửi Header xác thực (Bearer Token) không?
Có. Bạn chỉ cần nhập header vào ô Custom Headers theo định dạng `Authorization: Bearer YOUR_TOKEN`.

### 2. Kết quả trả về dạng JSON có được làm đẹp không?
Nếu API trả về `Content-Type: application/json`, công cụ sẽ tự động format thụt lề có màu sắc trực quan.

### 3. Tại sao tôi không thể gọi đến URL localhost (127.0.0.1)?
Để bảo vệ an ninh máy chủ, công cụ kích hoạt cơ chế bảo vệ SSRF, chỉ cho phép gửi yêu cầu tới các tên miền và IP công khai trên Internet.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'http-status-checker',
                'name' => 'Kiểm Tra Mã Trạng Thái HTTP & Chuỗi Chuyển Hướng (Redirect Tracer)',
                'summary' => 'Kiểm tra mã trạng thái HTTP, truy vết toàn bộ chuỗi chuyển hướng 301/302 từng bước và kiểm tra chứng chỉ bảo mật SSL/HTTPS.',
                'icon' => 'shield-check',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'HTTP Status Code & Redirect Chain Checker Online | TechHub',
                'meta_description' => 'Kiểm tra mã phản hồi HTTP, truy vết chuỗi chuyển hướng 301/302, kiểm tra vòng lặp redirect loop và thời gian phản hồi máy chủ.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu HTTP Status & Redirect Checker
Kiểm tra phản hồi thực tế của máy chủ web đối với một URL bất kỳ. Đặc biệt hữu ích cho chuyên viên SEO và Quản trị web khi cần kiểm tra các liên kết chuyển hướng (301 Permanent Redirect) hoặc phát hiện vòng lặp chuyển hướng vô tận (Redirect Loop).

### ✨ Tính Năng Nổi Bật:
* **Theo dõi từng chặng (Hop-by-hop Tracer)**: Hiển thị chi tiết từng bước chuyển hướng từ URL ban đầu đến đích cuối cùng.
* **Thời gian phản hồi từng chặng**: Đo lường độ trễ mạng của từng máy chủ trung gian.
* **Giải nghĩa mã trạng thái**: Giải thích ý nghĩa của các mã phổ biến: `200 OK`, `301 Moved Permanently`, `302 Found`, `403 Forbidden`, `404 Not Found`, `500 Server Error`.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao chuỗi chuyển hướng nhiều bước (Redirect Chain) lại có hại cho SEO?
Mỗi lần chuyển hướng làm gia tăng thời gian chờ tải trang (TTFB) và làm hao hụt ngân sách thu thập dữ liệu (Crawl Budget) của bot tìm kiếm Google.

### 2. Chuyển hướng 301 khác gì so với 302?
`301` là chuyển hướng vĩnh viễn (truyền toàn bộ giá trị PageRank/Link Equity sang URL mới). `302` là chuyển hướng tạm thời (không truyền PageRank).

### 3. Vòng lặp chuyển hướng (Redirect Loop) là gì?
Là hiện tượng trang A chuyển hướng sang B, và trang B lại chuyển hướng ngược về A, khiến trình duyệt bị treo và báo lỗi `ERR_TOO_MANY_REDIRECTS`.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'password-generator',
                'name' => 'Tạo Mật Khẩu Mạnh & Đo Độ Bảo Mật (Shannon Entropy)',
                'summary' => 'Tạo mật khẩu ngẫu nhiên an toàn cao bằng thuật toán mã hóa ngẫu nhiên, tùy biến độ dài, lọc ký tự dễ nhầm và phân tích độ mạnh Entropy.',
                'icon' => 'lock-closed',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'Strong Password Generator & Entropy Analyzer Online | TechHub',
                'meta_description' => 'Tạo mật khẩu ngẫu nhiên bảo mật cao, đo độ mạnh mật khẩu theo bit Entropy Shannon, chống bẻ khóa brute-force hiệu quả.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Password Generator & Analyzer
Tạo ra các chuỗi mật khẩu có độ phức tạp cao, hoàn toàn ngẫu nhiên và không thể đoán biết, kết hợp thanh đo độ mạnh dựa trên lý thuyết thông tin **Shannon Entropy**.

### ✨ Tính Năng Nổi Bật:
* **Mã hóa ngẫu nhiên an toàn**: Sử dụng hàm `random_int()` của PHP lấy entropy trực tiếp từ hệ điều hành.
* **Loại trừ ký tự dễ nhầm lẫn**: Tùy chọn loại bỏ các ký tự gây nhầm mắt như số 0 với chữ O, số 1 với chữ l hay dấu gạch đứng `|`.
* **Đo lường độ mạnh theo Bits**: Đánh giá chính xác khả năng kháng cự trước các cuộc tấn công Brute-force và từ điển.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Độ mạnh mật khẩu theo chuẩn bit Entropy tính như thế nào?
Mật khẩu có trên **60 bits** entropy được coi là mạnh, và trên **80 bits** là cực kỳ mạnh mẽ (mất hàng nghìn năm để siêu máy tính có thể bẻ khóa).

### 2. Độ dài mật khẩu khuyến nghị là bao nhiêu?
Nên sử dụng mật khẩu có độ dài tối thiểu từ **12 đến 16 ký tự** kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt.

### 3. Mật khẩu sinh ra có được lưu trữ trên hệ thống TechHub không?
Hoàn toàn không. Mật khẩu được sinh ngẫu nhiên tức thời và không bao giờ lưu trữ trên bất kỳ tệp log hay cơ sở dữ liệu nào.
MD,
            ],
            [
                'category_id' => $categoryDev->id,
                'slug' => 'timestamp-converter',
                'name' => 'Chuyển Đổi Dấu Thời Gian Unix Epoch Timestamp',
                'summary' => 'Chuyển đổi 2 chiều giữa dấu thời gian Unix Timestamp (giây/mili-giây) và ngày giờ người đọc (UTC, GMT+7, ISO 8601) kèm thời gian tương đối.',
                'icon' => 'calendar',
                'engine_type' => ToolEngineType::ServerSync->value,
                'meta_title' => 'Unix Epoch Timestamp Converter Online — Chuyển Đổi Thời Gian | TechHub',
                'meta_description' => 'Chuyển đổi Unix timestamp sang ngày giờ thực tế và ngược lại, hỗ trợ đa múi giờ UTC, GMT+7, tính toán relative time chính xác.',
                'description_markdown' => <<<'MD'
## 📌 Giới Thiệu Unix Timestamp Converter
**Unix Epoch Timestamp** là số giây trôi qua kể từ thời điểm `00:00:00 UTC ngày 01 tháng 01 năm 1970`. Đây là định dạng lưu trữ thời gian tiêu chuẩn trong hầu hết các hệ quản trị CSDL và API hệ thống.

### ✨ Tính Năng Nổi Bật:
* **Chuyển đổi 2 chiều**: Epoch -> Ngày giờ dễ đọc và Ngày giờ -> Epoch.
* **Tự động nhận diện mili-giây**: Nhận biết chuỗi thời gian 10 chữ số (giây) hoặc 13 chữ số (mili-giây trong JavaScript).
* **Đầy đủ định dạng**: Hiển thị giờ địa phương (Việt Nam GMT+7), giờ quốc tế UTC, định dạng ISO 8601, RFC 2822 và thời gian tương đối ("2 giờ trước").

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Sự cố năm 2038 (Year 2038 Problem) là gì?
Các hệ thống sử dụng số nguyên 32-bit có dấu sẽ bị tràn số (overflow) vào ngày 19/01/2038 khi timestamp vượt quá $2^{31}-1$. TechHub sử dụng số nguyên 64-bit nên hoạt động an toàn đến hàng tỷ năm sau.

### 2. Làm thế nào để lấy timestamp hiện tại trong JavaScript và PHP?
Trong JavaScript: `Math.floor(Date.now() / 1000)` (giây) hoặc `Date.now()` (mili-giây). Trong PHP: `time()`.

### 3. Múi giờ mặc định của công cụ là gì?
Công cụ hiển thị song song cả múi giờ địa phương Việt Nam (`Asia/Ho_Chi_Minh` - GMT+7) và múi giờ chuẩn quốc tế (`UTC`).
MD,
            ],
        ];

        foreach ($tools as $toolData) {
            Tool::updateOrCreate(
                ['slug' => $toolData['slug']],
                $toolData,
            );
        }
    }
}
