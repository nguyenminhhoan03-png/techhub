<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Domain\Tool\Enums\ToolEngineType;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'developer',
                'name' => 'Công cụ Lập trình',
                'description' => 'Bộ tiện ích trực tuyến cần thiết cho Developer: Định dạng JSON, Base64, Hash, Regex, JWT.',
                'icon' => 'code-xml',
                'sort_order' => 1,
                'is_active' => true,
                'meta_title' => 'Công cụ Lập trình Trực tuyến & Tiện ích Code - TechHub',
                'meta_description' => 'Hộp công cụ lập trình miễn phí với bộ định dạng JSON, kiểm tra Regex, giải mã JWT và tạo mã băm.',
            ],
            [
                'slug' => 'calculators',
                'name' => 'Máy tính Trực tuyến',
                'description' => 'Máy tính tài chính, tính lãi suất vay ngân hàng, phần trăm và chỉ số sức khỏe BMI.',
                'icon' => 'calculator',
                'sort_order' => 2,
                'is_active' => true,
                'meta_title' => 'Máy tính Tài chính & Toán học Trực tuyến - TechHub',
                'meta_description' => 'Công cụ tính lãi vay trả góp, EMI, tính phần trăm và chỉ số BMI chuẩn xác.',
            ],
            [
                'slug' => 'image',
                'name' => 'Xử lý & Phân tích Ảnh',
                'description' => 'Tiện ích xử lý hình ảnh: Trích xuất bảng màu ảnh chủ đạo, đọc thông tin EXIF và độ phân giải.',
                'icon' => 'image',
                'sort_order' => 3,
                'is_active' => true,
                'meta_title' => 'Tiện ích Xử lý Ảnh & Trích xuất Màu - TechHub',
                'meta_description' => 'Kiểm tra thông số ảnh EXIF và trích xuất bảng mã màu HEX/RGB trực tiếp trên trình duyệt.',
            ],
            [
                'slug' => 'pdf',
                'name' => 'Công cụ PDF',
                'description' => 'Ghép, tách, nén và chuyển đổi định dạng tài liệu PDF.',
                'icon' => 'file-text',
                'sort_order' => 4,
                'is_active' => true,
                'meta_title' => 'Tiện ích PDF Trực tuyến Miễn phí - TechHub',
                'meta_description' => 'Ghép file, tách trang và tối ưu dung lượng file PDF.',
            ],
            [
                'slug' => 'text',
                'name' => 'Xử lý Văn bản',
                'description' => 'Đếm từ, so sánh văn bản (Diff checker), chuyển đổi chữ hoa/thường và tạo Slug.',
                'icon' => 'type',
                'sort_order' => 5,
                'is_active' => true,
                'meta_title' => 'Công cụ Phân tích & Xử lý Chuỗi Văn bản - TechHub',
                'meta_description' => 'Đếm số từ, so sánh hai đoạn văn bản và tạo slug chuẩn SEO.',
            ],
            [
                'slug' => 'color',
                'name' => 'Màu sắc & Thiết kế',
                'description' => 'Bảng chọn màu, kiểm tra tương phản chuẩn WCAG và tạo dải màu Gradient.',
                'icon' => 'palette',
                'sort_order' => 6,
                'is_active' => true,
                'meta_title' => 'Công cụ Màu sắc & Đồ họa UI/UX - TechHub',
                'meta_description' => 'Kiểm tra độ tương phản màu sắc và tạo bảng màu CSS Gradient hiện đại.',
            ],
            [
                'slug' => 'ai',
                'name' => 'Tiện ích AI',
                'description' => 'Công cụ tối ưu câu lệnh Prompt, giải thích mã nguồn và tóm tắt văn bản.',
                'icon' => 'sparkles',
                'sort_order' => 7,
                'is_active' => true,
                'meta_title' => 'Tiện ích Trí tuệ Nhân tạo AI - TechHub',
                'meta_description' => 'Bộ công cụ hỗ trợ AI nâng cao hiệu suất làm việc.',
            ],
            [
                'slug' => 'seo',
                'name' => 'Công cụ SEO',
                'description' => 'Bộ công cụ tối ưu SEO Onpage: Xem trước Google SERP, tạo Meta tags, Schema JSON-LD, Open Graph, Robots.txt, Sitemap XML và tối ưu URL Slug.',
                'icon' => 'globe-alt',
                'sort_order' => 8,
                'is_active' => true,
                'meta_title' => 'Công cụ SEO Onpage & Tiện ích Tối ưu Web - TechHub',
                'meta_description' => 'Hộp công cụ SEO miễn phí giúp xem trước SERP, tạo Meta Tags, Schema Markup, Open Graph, Sitemap XML và Robots.txt chuẩn Google.',
            ],
        ];

        $categoryMap = [];
        foreach ($categories as $catData) {
            $cat = ToolCategory::query()->updateOrCreate(
                ['slug' => $catData['slug']],
                $catData,
            );
            $categoryMap[$cat->slug] = $cat->id;
        }

        $tools = [
            // ==========================================
            // DEVELOPER TOOLS
            // ==========================================
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'json-formatter',
                'name' => 'JSON Formatter Online',
                'meta_title' => 'JSON Formatter Online — Định Dạng, Validate & Nén JSON Chuẩn 100% | TechHub',
                'meta_description' => 'Công cụ JSON Formatter trực tuyến miễn phí: Định dạng làm đẹp (Beautify), kiểm tra lỗi cú pháp (Validate) và nén gọn (Minify) chuỗi JSON tức thì dưới 5ms.',
                'summary' => 'Làm đẹp (Beautify), nén gọn (Minify) và kiểm tra lỗi cú pháp JSON tức thì với tùy chọn thụt đầu dòng linh hoạt.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ JSON Formatter & Validator Trực Tuyến

**JSON (JavaScript Object Notation)** là định dạng trao đổi dữ liệu tiêu chuẩn phổ biến nhất hiện nay trong phát triển web, ứng dụng di động và hệ thống RESTful API / GraphQL. Tuy nhiên, dữ liệu JSON thô khi truyền tải qua mạng thường ở dạng nén (minified - gom thành một dòng) hoặc chứa các lỗi cú pháp khó quan sát bằng mắt thường.

Công cụ **JSON Formatter** của TechHub giúp lập trình viên và quản trị viên hệ thống:
* **Định dạng cấu trúc (Beautify)**: Tự động thụt lề (2 spaces, 4 spaces hoặc Tab), xuống dòng và phân cấp rõ ràng các object/array lồng nhau.
* **Kiểm tra lỗi cú pháp (Validate)**: Xác định chính xác vị trí dòng và ký tự bị sai cú pháp (thiếu dấu ngoặc, thừa dấu phẩy trailing comma, dùng nhầm dấu nháy đơn...).
* **Nén gọn dữ liệu (Minify)**: Loại bỏ toàn bộ khoảng trắng thừa để tối ưu băng thông khi truyền tải qua API hoặc lưu trữ vào cơ sở dữ liệu.
* **Tốc độ siêu tốc & Bảo mật tuyệt đối**: Xử lý với độ trễ dưới 5ms, áp dụng chính sách **Zero Data Retention** (dữ liệu chỉ chạy trong RAM và không bao giờ lưu trữ trên server).

---

## 🛠️ Hướng Dẫn Sử Dụng JSON Formatter Từng Bước

1. **Bước 1**: Dán đoạn mã JSON cần xử lý vào khung nhập dữ liệu bên trái (hoặc nhấn nút **Nạp JSON Mẫu** để thử nghiệm).
2. **Bước 2**: Lựa chọn chế độ xử lý mong muốn:
   - **Định dạng đẹp (Format / Beautify)**: Căn chỉnh thụt đầu dòng trực quan.
   - **Nén gọn (Minify)**: Gom toàn bộ JSON thành 1 dòng duy nhất.
3. **Bước 3**: Nhấn nút **Thực Thi** để nhận kết quả phân tích tức thì.
4. **Bước 4**: Nhấn nút **Sao Chép Kết Quả** để đưa vào clipboard và sử dụng trong dự án của bạn.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao mã JSON của tôi báo lỗi "Syntax Error"?
Các lỗi cú pháp JSON phổ biến nhất bao gồm:
* Dùng dấu nháy đơn (\') thay vì dấu nháy kép (") cho tên thuộc tính hoặc chuỗi.
* Có dấu phẩy thừa ở phần tử cuối cùng của object hoặc array (Trailing Comma).
* Thiếu dấu đóng ngoặc {} hoặc [].

### 2. Dữ liệu JSON nhạy cảm (API Keys, thông tin user) dán vào đây có an toàn không?
TechHub cam kết an toàn tuyệt đối. Quá trình định dạng được thực hiện tức thì trên bộ nhớ đệm và bị hủy ngay sau khi trả kết quả, không lưu bất kỳ log dữ liệu nào vào database.

### 3. Công cụ có hỗ trợ JSON dung lượng lớn (hàng chục MB) không?
Có. Hệ thống được tối ưu hóa bằng thuật toán stream phân tích hiệu năng cao, hỗ trợ xử lý mượt mà các file JSON lớn mà không gây treo trình duyệt.',
                'icon' => 'brackets',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1250,
                'view_count' => 5400,
                'rating_avg' => 4.95,
                'rating_count' => 84,
            ],
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'base64-encode-decode',
                'name' => 'Mã Hóa & Giải Mã Base64',
                'meta_title' => 'Base64 Encode & Decode Online — Mã Hóa & Giải Mã Chuẩn RFC 4648 | TechHub',
                'meta_description' => 'Công cụ mã hóa văn bản sang Base64 và giải mã chuỗi Base64 sang văn bản gốc trực tuyến miễn phí, hỗ trợ URL-safe và định dạng UTF-8 chuẩn xác.',
                'summary' => 'Chuyển đổi văn bản hoặc tệp nhị phân sang chuỗi Base64 và ngược lại, hỗ trợ chuẩn mã hóa URL-safe.',
                'description_markdown' => '## 📌 Giới Thiệu Về Mã Hóa Base64 Chuẩn RFC 4648

**Base64** là một nhóm các thuật toán mã hóa nhị phân thành chuỗi ký tự ASCII, cho phép biểu diễn dữ liệu nhị phân (như hình ảnh, tệp tin, khóa mã hóa) dưới dạng văn bản an toàn để truyền tải qua các giao thức chỉ hỗ trợ text như HTTP, SMTP (Email) hoặc nhúng trực tiếp vào mã nguồn HTML/CSS (Data URI).

Công cụ **Base64 Tool** của TechHub hỗ trợ:
* **Mã Hóa (Encode)**: Chuyển đổi văn bản thuần (kể cả tiếng Việt Unicode có dấu) sang chuỗi Base64 an toàn.
* **Giải Mã (Decode)**: Khôi phục chuỗi Base64 về định dạng văn bản gốc nguyên bản.
* **Hỗ trợ chuẩn URL-Safe**: Tự động thay thế các ký tự `+` thành `-` và `/` thành `_` để an toàn khi truyền qua URL Query Parameters.

---

## 🛠️ Hướng Dẫn Sử Dụng Base64 Encoder / Decoder

1. **Bước 1**: Nhập hoặc dán chuỗi văn bản cần xử lý vào ô dữ liệu.
2. **Bước 2**: Chọn chế độ **Mã Hóa (Encode)** hoặc **Giải Mã (Decode)**.
3. **Bước 3**: (Tùy chọn) Bật chế độ *URL Safe* nếu bạn muốn sử dụng chuỗi kết quả trong đường dẫn URL.
4. **Bước 4**: Nhấn nút **Thực Thi** và sao chép kết quả chỉ với 1 cú click chuột.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Base64 có phải là một phương thức bảo mật / mã hóa mật khẩu không?
**Không**. Base64 chỉ là một chuẩn **chuyển đổi định dạng dữ liệu (Encoding)**, không phải là thuật toán mã hóa bảo mật (Encryption). Bất kỳ ai cũng có thể giải mã chuỗi Base64 về dữ liệu ban đầu. Không bao giờ dùng Base64 để lưu trữ mật khẩu!

### 2. Ký tự `=` ở cuối chuỗi Base64 có ý nghĩa gì?
Dấu `=` được gọi là ký tự đệm (Padding). Khi số byte nhị phân đầu vào không chia hết cho 3, thuật toán sẽ thêm 1 hoặc 2 dấu `=` vào cuối chuỗi Base64 để đảm bảo độ dài chuỗi luôn là bội số của 4.',
                'icon' => 'binary',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 890,
                'view_count' => 3200,
                'rating_avg' => 4.90,
                'rating_count' => 42,
            ],
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'hash-generator',
                'name' => 'Tạo Mã Băm Hash & Checksum',
                'meta_title' => 'Hash Generator Online — Tạo Mã Băm MD5, SHA256, SHA512, Bcrypt | TechHub',
                'meta_description' => 'Công cụ tạo mã băm mật mã học trực tuyến miễn phí: MD5, SHA-1, SHA-256, SHA-512, Bcrypt, hỗ trợ chữ hoa/thường và khóa bí mật HMAC an toàn.',
                'summary' => 'Tạo mã băm bảo mật MD5, SHA-1, SHA-256, SHA-512, Bcrypt với tùy chọn khóa bí mật HMAC an toàn.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ Tạo Mã Băm Mật Mã Học (Hash Generator)

**Hàm băm mật mã học (Cryptographic Hash Function)** là thuật toán toán học một chiều (One-way function) biến đổi một khối dữ liệu có độ dài bất kỳ thành một chuỗi ký tự có độ dài cố định. Mã băm được ứng dụng rộng rãi trong lưu trữ mật khẩu an toàn, kiểm tra tính toàn vẹn của tệp tin (Checksum) và xác thực chữ ký điện tử.

Công cụ của TechHub hỗ trợ đầy đủ các thuật toán mã băm hàng đầu:
* **MD5 & SHA-1**: Thuật toán phổ biến dùng để kiểm tra tính toàn vẹn file (Checksum).
* **SHA-256 & SHA-512 (SHA-2 Family)**: Tiêu chuẩn bảo mật cao cấp trong SSL/TLS, Blockchain và xác thực dữ liệu.
* **Bcrypt**: Thuật toán băm mật khẩu chuyên dụng có tích hợp Salt chống tấn công Brute-force và Rainbow Table.
* **HMAC (Hash-based Message Authentication Code)**: Ký mã băm với khóa bí mật để xác thực danh tính API Request.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Có thể giải mã ngược chuỗi SHA-256 về văn bản ban đầu được không?
**Không thể**. Hàm băm là hàm một chiều không thể đảo ngược. Cách duy nhất để "tìm lại" mật khẩu là so sánh mã băm với bảng đối chiếu mẫu (Rainbow Table) hoặc thử ngẫu nhiên từng ký tự (Brute Force).

### 2. Thuật toán nào an toàn nhất để lưu trữ mật khẩu người dùng?
Hiện nay, **Bcrypt**, **Argon2** và **PBKDF2** là các thuật toán tiêu chuẩn được khuyến nghị cho lưu trữ mật khẩu vì chúng có cơ chế tăng độ phức tạp tính toán (Work Factor) và tự động tạo muối (Salt).',
                'icon' => 'hash',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 640,
                'view_count' => 2100,
                'rating_avg' => 4.88,
                'rating_count' => 31,
            ],
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'jwt-debugger',
                'name' => 'Giải Mã & Kiểm Tra Token JWT',
                'meta_title' => 'JWT Debugger & Decoder Online — Giải Mã Token JWT (Header, Payload, Sign) | TechHub',
                'meta_description' => 'Công cụ giải mã và kiểm tra JSON Web Token (JWT) trực quan: Xem JOSE header, payload claims (exp, iat, sub), kiểm tra thuật toán ký và trạng thái hết hạn token.',
                'summary' => 'Giải mã trực quan cấu trúc Header, Payload claims, thuật toán mã hóa và thời hạn sống của JSON Web Token.',
                'description_markdown' => '## 📌 Giới Thiệu Trình Debug & Giải Mã JSON Web Token (JWT)

**JWT (JSON Web Token - RFC 7519)** là phương thức truyền tải thông tin an toàn và nhỏ gọn giữa các bên dưới dạng JSON Object. JWT là tiêu chuẩn công nghiệp được sử dụng trong hầu hết các hệ thống xác thực hiện đại (OAuth 2.0, OpenID Connect, Single Sign-On).

Một token JWT hợp lệ luôn bao gồm 3 phần được ngăn cách bởi dấu chấm `.`:
1. **Header (Đỏ)**: Chứa thông tin về loại token (`JWT`) và thuật toán ký mã hóa (`HS256`, `RS256`, `ES256`).
2. **Payload (Tím)**: Chứa các Claims (thông tin người dùng, quyền hạn, thời điểm tạo `iat`, thời điểm hết hạn `exp`).
3. **Signature (Xanh lam)**: Chữ ký số dùng để xác minh tính toàn vẹn của token.

---

## 🛠️ Hướng Dẫn Debug Token JWT Với TechHub

1. **Bước 1**: Dán chuỗi JWT (bắt đầu bằng `eyJ...`) vào khung nhập dữ liệu.
2. **Bước 2**: Hệ thống tự động phân tách và tô màu 3 thành phần Header, Payload, Signature.
3. **Bước 3**: Kiểm tra ngày giờ hết hạn (Expiration Time) và các thông tin Payload Claims trực quan.
4. **Bước 4**: Toàn bộ quá trình giải mã diễn ra an toàn, không gửi token về máy chủ lưu trữ.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. JWT có mã hóa dữ liệu người dùng bên trong không?
**Không**. Phần Payload của JWT chỉ được mã hóa theo chuẩn Base64Url (ai cũng có thể đọc được). Do đó, tuyệt đối **không đặt mật khẩu, mã PIN hoặc thông tin thẻ tín dụng** vào trong JWT Payload.

### 2. Làm sao biết một token JWT đã hết hạn chưa?
Trong phần Payload, thuộc tính `exp` chứa mốc thời gian Unix Timestamp hết hạn. Công cụ TechHub sẽ tự động đối chiếu `exp` với thời gian hiện tại và thông báo token còn hiệu lực hay đã Expired.',
                'icon' => 'key-round',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1100,
                'view_count' => 4500,
                'rating_avg' => 4.98,
                'rating_count' => 95,
            ],
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'regex-tester',
                'name' => 'Kiểm Tra Biểu Thức Regex',
                'meta_title' => 'Regex Tester Online — Kiểm Tra & Bóc Tách Biểu Thức Chính Quy PCRE | TechHub',
                'meta_description' => 'Công cụ kiểm tra biểu thức chính quy (Regex Tester) thời gian thực: Bóc tách match groups, giải thích cú pháp, kiểm tra định dạng email, số điện thoại, URL chuẩn xác.',
                'summary' => 'Kiểm tra biểu thức chính quy (PCRE) thời gian thực, bóc tách capture group và giải thích lỗi chi tiết.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ Kiểm Tra Biểu Thức Chính Quy (Regex Tester)

**Regular Expression (Regex / Biểu thức chính quy)** là chuỗi các ký tự đặc biệt dùng để mô tả một mẫu tìm kiếm (Search Pattern) trong văn bản. Regex là kỹ năng thiết yếu giúp lập trình viên kiểm tra tính hợp lệ của dữ liệu đầu vào (Validation), tìm kiếm nâng cao và thay thế chuỗi phức tạp.

Công cụ **Regex Tester** của TechHub cung cấp:
* Hỗ trợ chuẩn **PCRE (Perl Compatible Regular Expressions)** và **JavaScript RegExp**.
* Tùy chọn các cờ (Flags) thông dụng: `g` (Global), `i` (Case-insensitive), `m` (Multiline), `s` (Dotall), `u` (Unicode).
* Phân tích và hiển thị trực quan các nhóm bóc tách dữ liệu (Capture Groups).

---

## 📋 Một Số Mẫu Regex Thông Dụng Cho Dự Án

* **Kiểm tra Email hợp lệ**: `^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$`
* **Kiểm tra Số điện thoại Việt Nam**: `^(0|\+84)(3|5|7|8|9)[0-9]{8}$`
* **Kiểm tra URL hợp lệ**: `^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$`
* **Kiểm tra Mật khẩu mạnh (ít nhất 8 ký tự, có chữ hoa, thường, số, ký tự đặc biệt)**: `^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$`',
                'icon' => 'regex',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 970,
                'view_count' => 3800,
                'rating_avg' => 4.92,
                'rating_count' => 58,
            ],
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'url-encoder-decoder',
                'name' => 'Mã Hóa & Giải Mã URL',
                'meta_title' => 'URL Encoder & Decoder Online — Mã Hóa Ký Tự Chuẩn RFC 3986 | TechHub',
                'meta_description' => 'Công cụ mã hóa URL (URL Encode) và giải mã URL (URL Decode) trực tuyến miễn phí: Chuyển đổi dấu tiếng Việt và ký tự đặc biệt sang định dạng phần trăm an toàn.',
                'summary' => 'Mã hóa ký tự đặc biệt theo chuẩn RFC 3986 và giải mã các tham số truy vấn URL an toàn.',
                'description_markdown' => '## 📌 Giới Thiệu Về URL Encoding / Percent-Encoding

**URL Encoding (Mã hóa phần trăm)** là cơ chế chuyển đổi các ký tự không an toàn hoặc ký tự ngoài bảng mã ASCII (như tiếng Việt có dấu, khoảng trắng, ký tự `&`, `?`, `#`) thành định dạng `%` theo sau bởi 2 chữ số thập lục phân (Hexadecimal) theo chuẩn **RFC 3986**.

Việc mã hóa URL giúp đảm bảo các tham số truy vấn (Query String) được truyền tải chính xác qua trình duyệt web mà không làm hỏng cấu trúc URL hoặc gây lỗi bảo mật.',
                'icon' => 'link-2',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 430,
                'view_count' => 1800,
                'rating_avg' => 4.85,
                'rating_count' => 20,
            ],

            // ==========================================
            // CALCULATOR TOOLS
            // ==========================================
            [
                'category_id' => $categoryMap['calculators'],
                'slug' => 'loan-calculator',
                'name' => 'Tính Lãi Suất Vay Ngân Hàng',
                'meta_title' => 'Máy Tính Lãi Suất Vay Ngân Hàng — Bảng Tính Trả Góp Gốc & Lãi Hàng Tháng | TechHub',
                'meta_description' => 'Công cụ tính lãi suất vay mua nhà, mua xe, vay tiêu dùng theo dư nợ giảm dần hoặc dư nợ gốc. Bảng lịch biểu trả gốc lãi chi tiết từng tháng chuẩn xác 100%.',
                'summary' => 'Tính số tiền trả hàng tháng (EMI), tổng tiền lãi, chi phí khoản vay và bảng lịch biểu trả nợ chi tiết.',
                'description_markdown' => '## 📌 Giới Thiệu Máy Tính Lãi Suất Vay & Trả Góp Ngân Hàng

Công cụ **Tính Lãi Suất Vay** của TechHub được xây dựng dựa trên công thức tài chính tiêu chuẩn của các ngân hàng thương mại hàng đầu tại Việt Nam (Vietcombank, Techcombank, BIDV, MBBank, VPBank...), giúp bạn dễ dàng lập kế hoạch tài chính cho các khoản vay mua nhà, vay mua ô tô, vay kinh doanh hoặc vay tiêu dùng.

### 🌟 2 Phương Thức Tính Lãi Suất Phổ Biến:
1. **Theo Dư Nợ Giảm Dần (Chuẩn Ngân Hàng)**: Tiền lãi được tính trên số tiền gốc thực tế còn nợ lại sau mỗi kỳ trả góp. Số tiền lãi sẽ giảm dần theo thời gian.
2. **Theo Dư Nợ Gốc (Lãi Suất Phẳng / Cố Định)**: Tiền lãi được tính cố định dựa trên số tiền vay ban đầu trong suốt thời gian vay.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Công thức tính tiền trả góp hàng tháng (EMI) như thế nào?
Công thức chuẩn: $EMI = [P \times r \times (1 + r)^n] / [(1 + r)^n - 1]$, trong đó:
* $P$: Số tiền vay gốc.
* $r$: Lãi suất theo tháng (Lãi năm / 12).
* $n$: Tổng số tháng vay.

### 2. Có nên chọn gói vay lãi suất ưu đãi năm đầu không?
Hầu hết các ngân hàng áp dụng mức lãi suất ưu đãi trong 6 - 24 tháng đầu (khoảng 6 - 8%/năm), sau đó sẽ thả nổi theo công thức: *Lãi suất tiết kiệm 12 tháng + Biên độ 3 - 4%*. Hãy tính toán trước khả năng tài chính khi hết hạn ưu đãi.',
                'icon' => 'landmark',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 2300,
                'view_count' => 9800,
                'rating_avg' => 4.96,
                'rating_count' => 140,
            ],
            [
                'category_id' => $categoryMap['calculators'],
                'slug' => 'percentage-calculator',
                'name' => 'Máy Tính Phần Trăm Online',
                'meta_title' => 'Máy Tính Phần Trăm Online — Tính % Tăng Giảm, Chiết Khấu & Giảm Giá | TechHub',
                'meta_description' => 'Công cụ tính phần trăm trực tuyến nhanh chóng: Tính X% của Y, tỷ lệ phần trăm chênh lệch tăng giảm giữa 2 số, tính giá trị sau chiết khấu khuyến mãi.',
                'summary' => 'Tính phần trăm giá trị, tỷ lệ tăng giảm %, tính mức chiết khấu giảm giá và tỷ lệ nhanh chóng.',
                'description_markdown' => '## 📌 Giới Thiệu Máy Tính Phần Trăm Đa Năng

Công cụ **Tính Phần Trăm** của TechHub giúp giải quyết nhanh mọi bài toán phần trăm trong kinh doanh, mua sắm giảm giá, tính thuế VAT và học tập:
* Tính $X\%$ của số $Y$ là bao nhiêu?
* Số $X$ chiếm bao nhiêu phần trăm của số $Y$?
* Tỷ lệ phần trăm tăng/giảm từ số $A$ lên số $B$.
* Tính giá sau chiết khấu khuyến mãi và số tiền tiết kiệm được.',
                'icon' => 'percent',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1800,
                'view_count' => 7600,
                'rating_avg' => 4.90,
                'rating_count' => 88,
            ],
            [
                'category_id' => $categoryMap['calculators'],
                'slug' => 'bmi-calculator',
                'name' => 'Tính Chỉ Số Khối Cơ Thể (BMI)',
                'meta_title' => 'Tính Chỉ Số BMI Online — Đánh Giá Thể Trạng & Cân Nặng Chuẩn WHO | TechHub',
                'meta_description' => 'Công cụ tính chỉ số khối cơ thể (BMI) trực tuyến cho nam và nữ theo chuẩn WHO và người Châu Á (IDI&WPRO). Đánh giá mức độ béo phì và cân nặng lý tưởng.',
                'summary' => 'Tính chỉ số thể trọng BMI, phân loại tình trạng sức khỏe theo WHO và xác định cân nặng lý tưởng.',
                'description_markdown' => '## 📌 Giới Thiệu Chỉ Số Khối Cơ Thể BMI (Body Mass Index)

**BMI (Body Mass Index)** là chỉ số khối lượng cơ thể được Tổ chức Y tế Thế giới (WHO) sử dụng để đánh giá tình trạng thể trạng của một người trưởng thành là gầy, bình thường, thừa cân hay béo phì dựa trên chiều cao và cân nặng.

### 📐 Công Thức Tính BMI:
$$BMI = \frac{\text{Cân nặng (kg)}}{\text{Chiều cao (m)}^2}$$

### 📊 Bảng Phân Loại BMI Cho Người Trưởng Thành Châu Á (IDI & WPRO):
* **BMI < 18.5**: Cân nặng thấp (Gầy / Thiếu cân).
* **BMI 18.5 - 22.9**: Thể trạng bình thường (Cân đối, khỏe mạnh).
* **BMI 23.0 - 24.9**: Thừa cân (Tiền béo phì).
* **BMI 25.0 - 29.9**: Béo phì độ I.
* **BMI ≥ 30.0**: Béo phì độ II (Nguy hiểm, cần can thiệp y tế).',
                'icon' => 'activity',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1500,
                'view_count' => 6200,
                'rating_avg' => 4.89,
                'rating_count' => 72,
            ],

            // ==========================================
            // IMAGE & MEDIA TOOLS
            // ==========================================
            [
                'category_id' => $categoryMap['image'],
                'slug' => 'image-metadata-inspector',
                'name' => 'Kiểm Tra Thông Số Ảnh & EXIF',
                'meta_title' => 'Đọc Thông Số Ảnh & EXIF Online — Xem Kích Thước Pixel & Metadata | TechHub',
                'meta_description' => 'Xem chi tiết thông số ảnh trực tuyến: Đọc dữ liệu EXIF camera, kích thước width x height, độ sâu màu bit depth, định dạng MIME và dung lượng tệp tin.',
                'summary' => 'Phân tích kích thước pixel, tỷ lệ khung hình, độ sâu màu, định dạng MIME và siêu dữ liệu camera EXIF.',
                'description_markdown' => '## 📌 Giới Thiệu Về Siêu Dữ Liệu Ảnh EXIF

**EXIF (Exchangeable Image File Format)** là chuẩn định dạng lưu trữ các thông tin chi tiết về bức ảnh được máy ảnh kỹ thuật số hoặc điện thoại thông minh ghi lại tại thời điểm chụp:
* Thông số thiết bị: Hãng sản xuất, model camera, ống kính (Lens).
* Thông số phơi sáng: Khẩu độ (F-stop), tốc độ màn trập (Shutter speed), độ nhạy sáng ISO, tiêu cự (Focal length).
* Dữ liệu hình ảnh: Kích thước độ phân giải thực, không gian màu sRGB/Display-P3, dung lượng tệp.',
                'icon' => 'file-search',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 520,
                'view_count' => 2400,
                'rating_avg' => 4.87,
                'rating_count' => 26,
            ],
            [
                'category_id' => $categoryMap['image'],
                'slug' => 'image-color-extractor',
                'name' => 'Trích Xuất Bảng Màu Từ Ảnh',
                'meta_title' => 'Trích Xuất Bảng Màu Từ Ảnh Online — Lấy Mã Màu HEX, RGB, HSL | TechHub',
                'meta_description' => 'Tải ảnh lên để tự động trích xuất bảng màu (Color Palette) chủ đạo. Sao chép nhanh mã màu HEX, RGB, HSL phục vụ thiết kế đồ họa và UI/UX web.',
                'summary' => 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ Trích Xuất Bảng Màu Ảnh (Color Palette Generator)

Tải bất kỳ bức ảnh phong cảnh, ảnh sản phẩm hoặc bản thiết kế nào lên để hệ thống tự động quét và phân tích bảng màu chủ đạo (Dominant Colors). Công cụ xuất ra đầy đủ mã màu định dạng **HEX**, **RGB** và **HSL** sẵn sàng để sử dụng trong CSS, Figma hoặc Photoshop.',
                'icon' => 'pipette',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 780,
                'view_count' => 3100,
                'rating_avg' => 4.94,
                'rating_count' => 49,
            ],

            // ==========================================
            // SEO & WEB TOOLS
            // ==========================================
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'serp-preview',
                'name' => 'Mô Phỏng Google SERP Snippet',
                'meta_title' => 'Google SERP Snippet Preview Tool — Đo Pixel & Ký Tự Chuẩn SEO | TechHub',
                'meta_description' => 'Mô phỏng hiển thị kết quả tìm kiếm Google (SERP Preview) trên Desktop và Mobile. Đo độ rộng Pixel title (< 600px) và ký tự description chuẩn SEO 2026.',
                'summary' => 'Xem trước giao diện kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ Google SERP Preview & Pixel Meter

Google không giới hạn tiêu đề theo số lượng ký tự cố định mà theo **độ rộng điểm ảnh (Pixel Width)**. Nếu tiêu đề bài viết vượt quá **600px**, Google sẽ tự động cắt ngắn và hiển thị dấu ba chấm `...`, làm giảm tỷ lệ nhấp chuột (CTR).

Công cụ **SERP Preview** của TechHub giúp bạn:
* Đo đạc chính xác độ rộng pixel của tiêu đề (chuẩn font Arial 20px của Google).
* Đếm số lượng ký tự của Meta Description (khuyến nghị 140 - 160 ký tự).
* Mô phỏng chân thực giao diện hiển thị trên cả máy tính (Desktop) và điện thoại (Mobile).',
                'icon' => 'search',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1950,
                'view_count' => 8400,
                'rating_avg' => 4.97,
                'rating_count' => 112,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'meta-tag-generator',
                'name' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO',
                'meta_title' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage — Meta Title, Description, Robots | TechHub',
                'meta_description' => 'Công cụ tạo bộ thẻ Meta Tags chuẩn SEO Onpage cho HTML5: Khai báo Title, Description, Canonical URL, Open Graph, Twitter Card và Robots meta tag.',
                'summary' => 'Tạo mã HTML5 thẻ Meta chuẩn SEO: Title, Description, Keywords, Canonical, Robots và phân tích SEO Onpage.',
                'description_markdown' => '## 📌 Giới Thiệu Bộ Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage

Thẻ Meta (Meta Tags) nằm trong phần `<head>` của trang HTML, cung cấp thông tin mô tả ngữ cảnh của trang web cho bot công cụ tìm kiếm (Google, Bing) và các mạng xã hội.

Công cụ hỗ trợ tạo trọn bộ:
* Thẻ SEO cơ bản: `title`, `meta description`, `meta keywords`, `canonical`.
* Thẻ chỉ thị thu thập dữ liệu: `robots (index, follow, noarchive...)`.
* Thẻ hiển thị mạng xã hội: Open Graph và Twitter Card.',
                'icon' => 'tags',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1640,
                'view_count' => 6900,
                'rating_avg' => 4.92,
                'rating_count' => 95,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'schema-generator',
                'name' => 'Tạo Schema Markup (JSON-LD)',
                'meta_title' => 'Tạo Schema Markup (JSON-LD) Chuẩn Google Rich Results | TechHub',
                'meta_description' => 'Trình tạo dữ liệu có cấu trúc Schema.org JSON-LD chuẩn Google: Article, LocalBusiness, FAQPage, Product, Breadcrumbs giúp kích hoạt Rich Snippets.',
                'summary' => 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.',
                'description_markdown' => '## 📌 Giới Thiệu Về Schema.org JSON-LD & Google Rich Results

**Schema Markup (Dữ liệu có cấu trúc)** giúp thuật toán Google hiểu sâu hơn về nội dung thực tế trên website của bạn (đây là bài viết tin tức, thông tin doanh nghiệp, câu hỏi FAQ hay sản phẩm bán hàng).

Website có cài đặt Schema hợp lệ sẽ có cơ hội nhận được **Google Rich Results (Kết quả giàu tính năng)** như: Đánh giá sao, bảng giá, câu hỏi thường gặp dạng accordion trên trang tìm kiếm, giúp tăng mạnh tỷ lệ click (CTR).',
                'icon' => 'code',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 2100,
                'view_count' => 9200,
                'rating_avg' => 4.98,
                'rating_count' => 134,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'open-graph-generator',
                'name' => 'Tạo Thẻ Open Graph & Twitter Cards',
                'meta_title' => 'Tạo Thẻ Open Graph & Twitter Cards Chuẩn Mạng Xã Hội | TechHub',
                'meta_description' => 'Tạo thẻ Open Graph (og:title, og:image, og:description) và Twitter Card giúp link chia sẻ trên Facebook, Zalo, Twitter, LinkedIn hiển thị đẹp mắt.',
                'summary' => 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.',
                'description_markdown' => '## 📌 Giới Thiệu Giao Thức Open Graph (Facebook & Twitter Cards)

Giao thức **Open Graph** do Facebook phát triển cho phép bất kỳ trang web nào trở thành một đối tượng phong phú trên mạng xã hội. Khi ai đó chia sẻ link web của bạn lên Facebook, Zalo hay Twitter, các thẻ `og:image`, `og:title`, `og:description` sẽ quyết định hình ảnh thumbnail và tiêu đề hiển thị.',
                'icon' => 'share-2',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1420,
                'view_count' => 5800,
                'rating_avg' => 4.91,
                'rating_count' => 84,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'robots-txt-generator',
                'name' => 'Tạo & Kiểm Tra File Robots.txt',
                'meta_title' => 'Tạo File Robots.txt Chuẩn SEO — Chặn AI Bot & Cấu Hình Sitemap | TechHub',
                'meta_description' => 'Công cụ tạo tệp robots.txt chuẩn xác cho website: Cấu hình quyền thu thập dữ liệu User-agent, Allow/Disallow, Crawl-delay và chặn các AI scraper.',
                'summary' => 'Tạo và kiểm tra cú pháp tệp robots.txt chuẩn SEO, hỗ trợ chặn AI Bot, thiết lập Crawl-delay và Sitemap.',
                'description_markdown' => '## 📌 Giới Thiệu Về Tệp Robots.txt Chuẩn SEO

Tệp **robots.txt** đặt tại thư mục gốc của website (`https://yourdomain.com/robots.txt`) là tệp chỉ thị đầu tiên mà các crawler (Googlebot, Bingbot, Yandex...) đọc để biết những thư mục hoặc URL nào được phép hoặc bị cấm thu thập dữ liệu.',
                'icon' => 'bot',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1180,
                'view_count' => 4900,
                'rating_avg' => 4.88,
                'rating_count' => 67,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'sitemap-generator',
                'name' => 'Tạo Sơ Đồ Trang Web XML Sitemap',
                'meta_title' => 'Tạo File Sitemap XML Miễn Phí — Công Cụ Quét Website Chuẩn Google & Bing | TechHub',
                'meta_description' => 'Tạo tệp XML Sitemap trực tuyến miễn phí chuẩn Sitemaps.org: Hỗ trợ live crawler quét URL tự động, gắn priority, changefreq, lastmod để gửi Google Search Console.',
                'summary' => 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).',
                'description_markdown' => '## 📌 Giới Thiệu Sơ Đồ Trang Web XML Sitemap

**XML Sitemap** là danh sách toàn bộ các đường dẫn (URLs) trên website của bạn được định dạng theo cấu trúc XML chuẩn của Sitemaps.org. Tệp Sitemap đóng vai trò như một tấm bản đồ chỉ đường cho Googlebot tìm thấy và lập chỉ mục nhanh chóng các bài viết mới.',
                'icon' => 'network',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1350,
                'view_count' => 5600,
                'rating_avg' => 4.93,
                'rating_count' => 79,
            ],
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'slug-generator',
                'name' => 'Tạo URL Slug Chuẩn SEO',
                'meta_title' => 'Tạo URL Slug Chuẩn SEO — Chuyển Tiêu Đề Có Dấu & Lọc Stop Words | TechHub',
                'meta_description' => 'Chuyển đổi tiêu đề tiếng Việt có dấu sang URL Slug chuẩn SEO: Tự động loại bỏ từ dừng (Stop words), chuẩn hóa kebab-case và tối ưu độ dài thân thiện Google.',
                'summary' => 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.',
                'description_markdown' => '## 📌 Giới Thiệu Về URL Slug Thân Thiện Chuẩn SEO

**URL Slug** là phần đuôi định danh của một trang web xuất hiện sau tên miền (ví dụ: `/tools/slug-generator`). Một URL Slug ngắn gọn, chứa từ khóa chính và không chứa các từ dừng dư thừa (Stop words: và, là, của, ở, cho...) sẽ giúp Google và người dùng hiểu rõ chủ đề của trang ngay từ cái nhìn đầu tiên.',
                'icon' => 'link',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 2800,
                'view_count' => 11200,
                'rating_avg' => 4.99,
                'rating_count' => 178,
            ],
        ];

        foreach ($tools as $toolData) {
            Tool::query()->updateOrCreate(
                ['slug' => $toolData['slug']],
                $toolData,
            );
        }
    }
}
