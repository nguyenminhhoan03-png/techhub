<?php

declare(strict_types=1);

return [
    'common' => [
        'online_tool' => 'công cụ online',
        'free_online_tools' => 'công cụ trực tuyến miễn phí, tiện ích lập trình, TechHub',
        'home_meta_keywords' => 'TechHub, công cụ lập trình online, json formatter, jwt debugger, regex tester, base64, SEO tools, công cụ trực tuyến miễn phí, developer utilities, game online miễn phí',
        'tools_index_meta_title' => 'Tất Cả Công Cụ Online Miễn Phí — Tiện Ích Lập Trình, Máy Tính & SEO | TechHub',
        'tools_index_meta_desc' => 'Hơn :count công cụ lập trình và tiện ích trực tuyến miễn phí: JSON Formatter, JWT Debugger, Base64, Regex Tester, SEO Tools, Calculator và nhiều hơn nữa. Không cần đăng ký.',
        'tools_index_meta_keywords' => 'công cụ lập trình online, json formatter, jwt debugger, base64 encode decode, regex tester, developer tools, SEO tools, công cụ trực tuyến miễn phí, TechHub',
    ],
    'json-formatter' => [
        'name' => 'Định Dạng & Kiểm Tra Cú Pháp JSON',
        'summary' => 'Làm đẹp (Beautify), nén gọn (Minify) và kiểm tra lỗi cú pháp JSON tức thì với tùy chọn thụt đầu dòng linh hoạt.',
        'description_markdown' => '## 📌 Giới Thiệu Công Cụ JSON Formatter & Validator Trực Tuyến

**JSON (JavaScript Object Notation)** là định dạng trao đổi dữ liệu tiêu chuẩn phổ biến nhất hiện nay trong phát triển web, ứng dụng di động và hệ thống RESTful API. Dữ liệu JSON thô khi truyền tải qua mạng thường ở dạng nén (minified - gom thành một dòng) hoặc chứa các lỗi cú pháp khó quan sát bằng mắt thường.

Công cụ **JSON Formatter** của TechHub giúp lập trình viên:
* **Định dạng cấu trúc (Beautify)**: Tự động thụt lề (2 spaces, 4 spaces), xuống dòng và phân cấp rõ ràng các object/array lồng nhau.
* **Kiểm tra lỗi cú pháp (Validate)**: Xác định chính xác vị trí dòng và ký tự bị sai cú pháp.
* **Nén gọn dữ liệu (Minify)**: Loại bỏ toàn bộ khoảng trắng thừa để tối ưu băng thông API.
* **Tốc độ siêu tốc & Bảo mật tuyệt đối**: Xử lý với độ trễ dưới 5ms, áp dụng chính sách **Zero Data Retention** (dữ liệu chỉ chạy trong RAM và không bao giờ lưu trữ trên server).

---

## 🛠️ Hướng Dẫn Sử Dụng JSON Formatter Từng Bước

1. **Bước 1**: Dán đoạn mã JSON cần xử lý vào khung nhập dữ liệu (hoặc nhấn **Nạp JSON Mẫu** để thử nghiệm).
2. **Bước 2**: Lựa chọn chế độ xử lý mong muốn:
   - **Định dạng đẹp (Format / Beautify)**: Căn chỉnh thụt đầu dòng trực quan.
   - **Nén gọn (Minify)**: Gom toàn bộ JSON thành 1 dòng duy nhất.
3. **Bước 3**: Nhấn nút **Thực Thi Ngay** để nhận kết quả phân tích tức thì.
4. **Bước 4**: Nhấn nút **Sao Chép Kết Quả** để lưu vào clipboard.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao mã JSON của tôi báo lỗi "Syntax Error"?
Các lỗi cú pháp JSON phổ biến nhất bao gồm dùng dấu nháy đơn (\') thay vì dấu nháy kép ("), có dấu phẩy thừa ở phần tử cuối cùng (Trailing Comma), hoặc thiếu dấu đóng ngoặc {} hay [].

### 2. Dữ liệu JSON nhạy cảm dán vào đây có an toàn không?
TechHub cam kết an toàn tuyệt đối. Quá trình định dạng được thực hiện tức thì trên RAM và bị hủy ngay sau khi trả kết quả, không lưu bất kỳ log nào vào cơ sở dữ liệu.

### 3. Công cụ có hỗ trợ JSON dung lượng lớn không?
Có. Hệ thống được tối ưu hóa bằng thuật toán stream phân tích hiệu năng cao, hỗ trợ xử lý mượt mà các file JSON lớn mà không gây treo trình duyệt.',
        'meta_title' => 'Định Dạng & Kiểm Tra Cú Pháp JSON Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Làm đẹp (Beautify), nén gọn (Minify) và kiểm tra lỗi cú pháp JSON tức thì với tùy chọn thụt đầu dòng linh hoạt.',
        'ui' => [
            'input_label' => 'Chuỗi JSON cần xử lý',
            'btn_load_sample' => 'Nạp JSON Mẫu',
            'action_label' => 'Hành động',
            'action_beautify' => 'Beautify (Làm đẹp)',
            'action_minify' => 'Nén gọn (Minify)',
            'action_validate' => 'Chỉ kiểm tra lỗi (Validate)',
            'indent_label' => 'Thụt dòng (Indent)',
            'indent_2_spaces' => '2 Spaces',
            'indent_4_spaces' => '4 Spaces',
        ],
    ],
    'base64-encode-decode' => [
        'name' => 'Mã Hóa & Giải Mã Base64',
        'summary' => 'Chuyển đổi văn bản hoặc tệp nhị phân sang chuỗi Base64 và ngược lại, hỗ trợ chuẩn mã hóa URL-safe.',
        'description_markdown' => '## 📌 Giới Thiệu Về Mã Hóa Base64 Chuẩn RFC 4648

**Base64** là một nhóm các thuật toán mã hóa nhị phân thành chuỗi ký tự ASCII, cho phép biểu diễn dữ liệu nhị phân (như hình ảnh, tệp tin) dưới dạng văn bản an toàn để truyền tải qua các giao thức chỉ hỗ trợ text như HTTP, SMTP hoặc nhúng trực tiếp vào mã nguồn HTML/CSS (Data URI).

Công cụ **Base64 Tool** của TechHub hỗ trợ:
* **Mã Hóa (Encode)**: Chuyển đổi văn bản thuần (kể cả tiếng Việt Unicode có dấu) sang chuỗi Base64.
* **Giải Mã (Decode)**: Khôi phục chuỗi Base64 về định dạng văn bản gốc.
* **Hỗ trợ chuẩn URL-Safe**: Tự động thay thế các ký tự `+` thành `-` và `/` thành `_` để an toàn khi truyền qua URL Query Parameters.

---

## 🛠️ Hướng Dẫn Sử Dụng Base64 Encoder / Decoder

1. **Bước 1**: Nhập hoặc dán chuỗi văn bản cần xử lý vào ô dữ liệu.
2. **Bước 2**: Chọn chế độ **Mã hóa sang Base64** hoặc **Giải mã Base64**.
3. **Bước 3**: (Tùy chọn) Bật chế độ URL-Safe nếu bạn dùng chuỗi trong đường dẫn URL.
4. **Bước 4**: Nhấn nút **Thực Thi Ngay** và sao chép kết quả.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Base64 có phải là một phương thức mã hóa mật khẩu bảo mật không?
Không. Base64 chỉ là một chuẩn chuyển đổi định dạng dữ liệu (Encoding), không phải thuật toán mã hóa bảo mật (Encryption). Bất kỳ ai cũng có thể giải mã chuỗi Base64 về dữ liệu ban đầu.

### 2. Ký tự `=` ở cuối chuỗi Base64 có ý nghĩa gì?
Dấu `=` được gọi là ký tự đệm (Padding) để đảm bảo độ dài chuỗi luôn là bội số của 4.',
        'meta_title' => 'Mã Hóa & Giải Mã Base64 Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Chuyển đổi văn bản hoặc tệp nhị phân sang chuỗi Base64 và ngược lại, hỗ trợ chuẩn mã hóa URL-safe.',
        'ui' => [
            'input_label' => 'Chuỗi văn bản hoặc Base64 cần xử lý',
            'btn_load_sample' => 'Nạp Văn Bản Mẫu',
            'input_placeholder' => 'Nhập văn bản tiếng Việt hoặc chuỗi Base64...',
            'action_encode' => 'Mã hóa sang Base64 (Encode)',
            'action_decode' => 'Giải mã Base64 (Decode)',
            'opt_url_safe' => 'URL-Safe (RFC 4648)',
        ],
    ],
    'hash-generator' => [
        'name' => 'Tạo Mã Băm Hash & Checksum',
        'summary' => 'Tạo mã băm bảo mật MD5, SHA-1, SHA-256, SHA-512, Bcrypt với tùy chọn khóa bí mật HMAC an toàn.',
        'description_markdown' => '## 📌 Giới Thiệu Công Cụ Tạo Mã Băm Mật Mã Học (Hash Generator)

**Hàm băm mật mã học (Cryptographic Hash Function)** là thuật toán toán học một chiều biến đổi một khối dữ liệu có độ dài bất kỳ thành một chuỗi ký tự có độ dài cố định.

Công cụ của TechHub hỗ trợ đầy đủ các thuật toán mã băm hàng đầu:
* **MD5 & SHA-1**: Thuật toán phổ biến dùng để kiểm tra tính toàn vẹn file (Checksum).
* **SHA-256 & SHA-512**: Tiêu chuẩn bảo mật cao cấp trong SSL/TLS, Blockchain và xác thực dữ liệu.
* **Bcrypt**: Thuật toán băm mật khẩu chuyên dụng có tích hợp Salt chống tấn công Brute-force.
* **HMAC**: Ký mã băm với khóa bí mật để xác thực danh tính API Request.

---

## 🛠️ Hướng Dẫn Sử Dụng Từng Bước

1. **Bước 1**: Nhập chuỗi văn bản hoặc mật khẩu cần băm.
2. **Bước 2**: Lựa chọn thuật toán hoặc chọn "Tất cả thuật toán".
3. **Bước 3**: (Tùy chọn) Nhập khóa bí mật HMAC nếu cần ký xác thực.
4. **Bước 4**: Nhấn nút **Tạo Mã Băm Ngay** và sao chép kết quả.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Có thể giải mã ngược chuỗi SHA-256 về văn bản ban đầu được không?
Không thể. Hàm băm là hàm một chiều không thể đảo ngược.',
        'meta_title' => 'Tạo Mã Băm Hash & Checksum Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo mã băm bảo mật MD5, SHA-1, SHA-256, SHA-512, Bcrypt với tùy chọn khóa bí mật HMAC an toàn.',
        'ui' => [
            'input_label' => 'Chuỗi văn bản cần băm (Input string)',
            'btn_load_sample' => 'Nạp Mẫu',
            'input_placeholder' => 'Nhập chuỗi văn bản hoặc mật khẩu cần băm...',
            'algorithm_label' => 'Thuật toán băm (Algorithm)',
            'alg_all' => 'Tất cả thuật toán (MD5, SHA1, SHA256, SHA512, Bcrypt)',
            'alg_sha256' => 'SHA-256 (Khuyên dùng cho bảo mật)',
            'alg_md5' => 'MD5 Checksum',
            'alg_sha1' => 'SHA-1',
            'alg_sha512' => 'SHA-512',
            'alg_bcrypt' => 'Bcrypt Password Hash',
            'secret_label' => 'Khóa bí mật HMAC (Tùy chọn)',
            'secret_placeholder' => 'Để trống nếu không dùng HMAC...',
            'btn_submit' => 'Tạo Mã Băm Ngay',
        ],
    ],
    'jwt-debugger' => [
        'name' => 'Giải Mã & Kiểm Tra Token JWT',
        'summary' => 'Giải mã trực quan cấu trúc Header, Payload claims, thuật toán mã hóa và thời hạn sống của JSON Web Token.',
        'description_markdown' => '## 📌 Trình Phân Tích & Giải Mã Token JWT

Dán bất kỳ chuỗi JWT nào vào công cụ của TechHub để lập tức phân tích:
* **JOSE Header**: Thuật toán ký (`alg`) và loại token (`typ`).
* **Payload Claims**: ID người dùng (`sub`), nhà phát hành (`iss`), thời điểm hết hạn (`exp`).
* **Trạng thái hiệu lực**: Đồng hồ đếm ngược và nhãn trực quan báo token còn hạn hay đã hết hạn.',
        'meta_title' => 'Giải Mã & Kiểm Tra Token JWT Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Giải mã trực quan cấu trúc Header, Payload claims, thuật toán mã hóa và thời hạn sống của JSON Web Token.',
        'ui' => [
            'input_label' => 'Mã JSON Web Token (JWT)',
            'btn_load_sample' => 'Tải Token JWT Mẫu',
            'btn_submit' => 'Giải Mã & Kiểm Tra Token',
        ],
    ],
    'regex-tester' => [
        'name' => 'Kiểm Tra & Bóc Tách Biểu Thức Regex',
        'summary' => 'Kiểm tra biểu thức chính quy (PCRE) thời gian thực, bóc tách capture group và giải thích lỗi chi tiết.',
        'description_markdown' => '## 📌 Kiểm Tra Regular Expression (Regex)

Thử nghiệm các mẫu Regex với các cờ (flags) `g`, `i`, `m`, `s`. Trích xuất chính xác các kết quả khớp và nhóm vị trí index trong thời gian thực.',
        'meta_title' => 'Kiểm Tra & Bóc Tách Biểu Thức Regex Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Kiểm tra biểu thức chính quy (PCRE) thời gian thực, bóc tách capture group và giải thích lỗi chi tiết.',
        'ui' => [
            'pattern_label' => 'Biểu thức chính quy (Regular Expression Pattern)',
            'btn_load_sample' => 'Nạp Regex Email Mẫu',
            'flags_help' => 'Cờ thông dụng: g (global), i (case-insensitive), m (multiline), s (dotAll)',
            'test_text_label' => 'Đoạn văn bản cần kiểm tra khớp (Test String)',
            'test_text_placeholder' => 'Nhập văn bản cần tìm kiếm khớp regex...',
            'sample_test_text' => 'Liên hệ chúng tôi tại contact@techhub.vn hoặc admin@techhub.local để được hỗ trợ.',
            'btn_submit' => 'Kiểm Tra Khớp Regex',
        ],
    ],
    'url-encoder-decoder' => [
        'name' => 'Mã Hóa & Giải Mã Đường Dẫn URL',
        'summary' => 'Mã hóa ký tự đặc biệt theo chuẩn RFC 3986 và giải mã các tham số truy vấn URL an toàn.',
        'description_markdown' => '## 📌 Mã Hóa & Giải Mã URL Chuẩn RFC 3986

Chuyển đổi các ký tự đặc biệt, dấu tiếng Việt thành chuỗi phần trăm an toàn cho trình duyệt và API, hoặc giải mã chuỗi phần trăm về văn bản gốc.',
        'meta_title' => 'Mã Hóa & Giải Mã Đường Dẫn URL Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Mã hóa ký tự đặc biệt theo chuẩn RFC 3986 và giải mã các tham số truy vấn URL an toàn.',
        'ui' => [
            'input_label' => 'Đường dẫn URL hoặc chuỗi tham số',
            'btn_load_sample' => 'Nạp URL Mẫu',
            'action_encode' => 'Mã hóa URL (Encode)',
            'action_decode' => 'Giải mã URL (Decode)',
            'standard_label' => 'Chuẩn:',
        ],
    ],
    'loan-calculator' => [
        'name' => 'Tính Lãi Suất Vay & Trả Góp Ngân Hàng',
        'summary' => 'Tính số tiền trả hàng tháng (EMI), tổng tiền lãi, chi phí khoản vay và bảng lịch biểu trả nợ chi tiết.',
        'description_markdown' => '## 📌 Máy Tính Khoản Vay & Lãi Suất Trả Góp

Lập kế hoạch tài chính chính xác với công thức tính dư nợ giảm dần, số tiền gốc và lãi từng tháng, tổng chi phí sau khi tất toán theo chuẩn ngân hàng thương mại.',
        'meta_title' => 'Tính Lãi Suất Vay & Trả Góp Ngân Hàng Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tính số tiền trả hàng tháng (EMI), tổng tiền lãi, chi phí khoản vay và bảng lịch biểu trả nợ chi tiết.',
        'ui' => [
            'principal_label' => 'Số tiền vay (VNĐ)',
            'principal_hint' => 'Ví dụ: 500,000,000 đ',
            'rate_label' => 'Lãi suất năm (%/năm)',
            'rate_hint' => 'Ví dụ: 8.5%',
            'term_label' => 'Thời hạn vay (Số tháng)',
            'term_hint' => '(60 tháng = 5 năm)',
            'btn_submit' => 'Tính Số Tiền Trả Hàng Tháng (EMI)',
        ],
    ],
    'percentage-calculator' => [
        'name' => 'Máy Tính Phần Trăm Trực Tuyến',
        'summary' => 'Tính phần trăm giá trị, tỷ lệ tăng giảm %, tính mức chiết khấu giảm giá và tỷ lệ nhanh chóng.',
        'description_markdown' => '## 📌 Máy Tính Phần Trăm Nhanh Chóng

Giải quyết các bài toán phần trăm thông dụng: X% của Y là bao nhiêu, tỷ lệ phần trăm tăng/giảm giữa hai số và tính giá sau giảm giá.',
        'meta_title' => 'Máy Tính Phần Trăm Trực Tuyến Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tính phần trăm giá trị, tỷ lệ tăng giảm %, tính mức chiết khấu giảm giá và tỷ lệ nhanh chóng.',
        'ui' => [
            'mode_label' => 'Chế độ tính toán phần trăm',
            'mode_percent_of' => '1. Tính X% của Y là bao nhiêu? (Ví dụ: 20% của 500,000đ)',
            'mode_is_what_percent' => '2. X là bao nhiêu % của Y? (Ví dụ: 25 là mấy % của 200)',
            'mode_increase_decrease' => '3. Tỷ lệ % tăng hoặc giảm từ A sang B',
            'val_a_label' => 'Giá trị A (X hoặc Giá gốc)',
            'val_b_label' => 'Giá trị B (Y hoặc Giá mới)',
            'btn_submit' => 'Tính Toán Ngay',
        ],
    ],
    'bmi-calculator' => [
        'name' => 'Tính Chỉ Số Khối Cơ Thể (BMI)',
        'summary' => 'Tính chỉ số thể trọng BMI, phân loại tình trạng sức khỏe theo WHO và xác định cân nặng lý tưởng.',
        'description_markdown' => '## 📌 Máy Tính Chỉ Số Sức Khỏe BMI

Đánh giá tình trạng thể trạng gầy, chuẩn hoặc thừa cân theo tiêu chuẩn quốc tế của Tổ chức Y tế Thế giới (WHO).',
        'meta_title' => 'Tính Chỉ Số Khối Cơ Thể (BMI) Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tính chỉ số thể trọng BMI, phân loại tình trạng sức khỏe theo WHO và xác định cân nặng lý tưởng.',
        'ui' => [
            'unit_label' => 'Hệ đo lường',
            'unit_metric' => 'Chuẩn Quốc Tế (cm, kg)',
            'unit_imperial' => 'Imperial (inches, lbs)',
            'height_label' => 'Chiều cao',
            'weight_label' => 'Cân nặng',
            'btn_submit' => 'Đánh Giá Chỉ Số BMI',
        ],
    ],
    'image-metadata-inspector' => [
        'name' => 'Kiểm Tra Thông Số Ảnh & EXIF',
        'summary' => 'Phân tích kích thước pixel, tỷ lệ khung hình, độ sâu màu, định dạng MIME và siêu dữ liệu camera EXIF.',
        'description_markdown' => '## 📌 Kiểm Tra Dữ Liệu Ảnh & Thông Số EXIF

Đọc chi tiết thông số ảnh số: Kích thước chiều rộng x cao, độ sâu bit màu, loại MIME type và thông số camera chụp ảnh trực tiếp trên trình duyệt.',
        'meta_title' => 'Kiểm Tra Thông Số Ảnh & EXIF Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Phân tích kích thước pixel, tỷ lệ khung hình, độ sâu màu, định dạng MIME và siêu dữ liệu camera EXIF.',
        'ui' => [
            'upload_label' => 'Tải lên hình ảnh cần phân tích thông số',
            'dropzone_title' => 'Nhấn để chọn file ảnh',
            'dropzone_desc' => 'hoặc kéo thả hình ảnh trực tiếp vào khung này (JPG, PNG, WEBP, GIF)',
            'btn_submit' => 'Phân Tích Thông Số Ảnh Ngay',
        ],
    ],
    'image-color-extractor' => [
        'name' => 'Trích Xuất Bảng Màu Chủ Đạo Của Ảnh',
        'summary' => 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.',
        'description_markdown' => '## 📌 Trích Xuất Bảng Mã Màu Ảnh Trực Tuyến

Tải lên hình ảnh hoặc thiết kế để hệ thống tự động quét và tạo bảng màu (Color Palette) gồm các mã HEX/RGB chủ đạo phục vụ UI/UX.',
        'meta_title' => 'Trích Xuất Bảng Màu Chủ Đạo Của Ảnh Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.',
        'ui' => [
            'upload_label' => 'Tải lên bức ảnh cần trích xuất bảng mã màu',
            'dropzone_title' => 'Nhấn để chọn ảnh thiết kế',
            'dropzone_desc' => 'hoặc kéo thả hình ảnh vào đây (Hệ thống xử lý an toàn 100% không lưu trữ)',
            'size_label' => 'Số lượng màu trích xuất',
            'opt_5' => '5 màu chủ đạo',
            'opt_3' => '3 màu chính',
            'opt_8' => '8 dải màu',
            'opt_10' => '10 dải màu',
            'btn_submit' => 'Trích Xuất Bảng Màu',
        ],
    ],
    'serp-preview' => [
        'name' => 'Mô Phỏng Hiển Thị Google SERP Snippet',
        'summary' => 'Xem trước giao diện kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.',
        'description_markdown' => '## 📌 Google SERP Snippet Preview & Pixel Meter

Mô phỏng chính xác kết quả hiển thị trên Google Tìm Kiếm (máy tính và di động). Hỗ trợ đo độ rộng pixel và ký tự tiêu chuẩn để tránh bị Google cắt ngắn.',
        'meta_title' => 'Mô Phỏng Hiển Thị Google SERP Snippet Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Xem trước giao diện kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.',
        'ui' => [
            'title_label' => 'Tiêu đề trang (SEO Page Title)',
            'btn_load_sample' => 'Nạp Mẫu SERP',
            'title_placeholder' => 'Ví dụ: Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub',
            'title_sample' => 'Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub',
            'title_hint' => 'Khuyên dùng: 50 - 60 ký tự (~600 pixel)',
            'chars_unit' => 'ký tự',
            'desc_label' => 'Thẻ mô tả (Meta Description)',
            'desc_placeholder' => 'Nhập đoạn mô tả hấp dẫn chứa từ khóa chính giúp tăng tỷ lệ nhấp chuột CTR...',
            'desc_sample' => 'Khám phá trọn bộ kỹ thuật tối ưu SEO Onpage chuẩn Google: Tối ưu thẻ Meta, cấu trúc Schema JSON-LD, Sitemap XML và tối ưu tốc độ tải trang vượt trội.',
            'desc_hint' => 'Khuyên dùng: 120 - 160 ký tự (~960 pixel)',
            'url_label' => 'Đường dẫn trang web (URL)',
            'site_name_label' => 'Tên website (Tùy chọn)',
            'site_name_sample' => 'TechHub Việt Nam',
            'device_label' => 'Thiết bị giả lập',
            'device_desktop' => '💻 Desktop (Máy tính)',
            'device_mobile' => '📱 Mobile (Điện thoại)',
            'date_label' => 'Ngày xuất bản',
            'rating_val_label' => 'Đánh giá sao (Rich Snippet)',
            'rating_cnt_label' => 'Số lượt đánh giá',
            'btn_submit' => 'Mô Phỏng Hiển Thị SERP Ngay',
        ],
    ],
    'meta-tag-generator' => [
        'name' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage',
        'summary' => 'Tạo mã HTML5 thẻ Meta chuẩn SEO: Title, Description, Keywords, Canonical, Robots và phân tích SEO Onpage.',
        'description_markdown' => '## 📌 Trình Tạo Bộ Thẻ Meta HTML5 Chuẩn SEO

Tạo nhanh chóng toàn bộ mã thẻ meta cần thiết cho thẻ `<head>` của trang web bao gồm thẻ cơ bản, chỉ thị Robots và thẻ Canonical URL.',
        'meta_title' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo mã HTML5 thẻ Meta chuẩn SEO: Title, Description, Keywords, Canonical, Robots và phân tích SEO Onpage.',
        'ui' => [
            'title_label' => 'Tiêu đề trang (Title Tag)',
            'btn_load_sample' => 'Nạp Mẫu Meta',
            'title_placeholder' => 'Tiêu đề trang web...',
            'title_sample' => 'TechHub - Nền Tảng Công Cụ Lập Trình & Tiện Ích Trực Tuyến',
            'desc_label' => 'Mô tả trang (Meta Description)',
            'desc_placeholder' => 'Đoạn mô tả ngắn gọn về nội dung trang...',
            'desc_sample' => 'TechHub cung cấp hơn 20+ công cụ trực tuyến miễn phí dành cho lập trình viên và chuyên gia SEO: Định dạng JSON, Regex, Base64, Schema Generator, SERP Preview.',
            'keywords_label' => 'Từ khóa (Meta Keywords)',
            'keywords_sample' => 'công cụ lập trình, seo tools, json formatter, schema generator, techhub',
            'canonical_label' => 'Canonical URL',
            'author_label' => 'Tác giả / Tổ chức (Author)',
            'author_sample' => 'TechHub Engineering Team',
            'language_label' => 'Ngôn ngữ trang (Language)',
            'robots_label' => 'Chỉ thị Robots (Robots Meta Directives)',
            'opt_index' => 'Index (Cho phép lập chỉ mục)',
            'opt_noindex' => 'Noindex (Chặn lập chỉ mục)',
            'opt_follow' => 'Follow (Thu thập liên kết)',
            'opt_nofollow' => 'Nofollow (Không thu thập)',
            'opt_noarchive' => 'Noarchive (Không lưu bản sao bộ nhớ đệm)',
            'opt_nosnippet' => 'Nosnippet (Không hiển thị trích đoạn)',
            'btn_submit' => 'Tạo Bộ Thẻ Meta HTML5 Ngay',
        ],
    ],
    'schema-generator' => [
        'name' => 'Tạo Schema Markup (JSON-LD) Chuẩn Google',
        'summary' => 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.',
        'description_markdown' => '## 📌 Trình Tạo Dữ Liệu Cấu Trúc Schema.org JSON-LD

Khai báo dữ liệu cấu trúc chuẩn Google để kích hoạt tính năng Rich Results (đánh giá sao, câu hỏi thường gặp FAQ, thông tin giá sản phẩm) trên kết quả tìm kiếm.',
        'meta_title' => 'Tạo Schema Markup (JSON-LD) Chuẩn Google Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.',
        'ui' => [
            'type_label' => 'Loại Schema Dữ Liệu Cấu Trúc',
            'btn_load_sample' => 'Nạp Schema Mẫu',
            'type_article' => '📰 Article / BlogPosting (Bài viết tin tức, blog)',
            'type_faq' => '❓ FAQPage (Trang câu hỏi thường gặp)',
            'type_product' => '🛍️ Product (Sản phẩm & Báo giá, Review)',
            'type_local' => '🏢 LocalBusiness (Doanh nghiệp địa phương)',
            'type_breadcrumb' => '🧭 BreadcrumbList (Thanh điều hướng phân cấp)',
            'type_software' => '💻 SoftwareApplication (Ứng dụng phần mềm)',
            'type_org' => '🌐 Organization (Tổ chức / Công ty)',
            'headline_label' => 'Tiêu đề bài viết / Tên thực thể (Headline / Name)',
            'headline_sample' => '10 Cách Tối Ưu Tốc Độ Website Với Clean Architecture',
            'desc_label' => 'Mô tả tóm tắt (Description)',
            'desc_sample' => 'Hướng dẫn từng bước cách refactor mã nguồn và áp dụng bộ nhớ đệm giúp giảm độ trễ dưới 5ms.',
            'url_label' => 'URL bài viết / Trang',
            'image_url_label' => 'Đường dẫn ảnh đại diện (Image URL)',
            'author_label' => 'Tên tác giả (Author)',
            'publisher_label' => 'Tên tổ chức xuất bản (Publisher)',
            'faq_label' => 'Nội dung câu hỏi FAQ (Dành riêng cho FAQPage: Mỗi dòng Hỏi: / Đáp:)',
            'faq_placeholder' => "Hỏi: Câu hỏi 1?\nĐáp: Trả lời câu hỏi 1...\nHỏi: Câu hỏi 2?\nĐáp: Trả lời câu hỏi 2...",
            'faq_sample' => "Hỏi: Schema JSON-LD có tác dụng gì trong SEO?\nĐáp: Giúp Google hiểu rõ nội dung và hiển thị Rich Snippets nổi bật trên kết quả tìm kiếm.\nHỏi: TechHub có hỗ trợ tạo Schema miễn phí không?\nĐáp: Toàn bộ công cụ tạo dữ liệu cấu trúc trên TechHub đều hoàn toàn miễn phí 100%.",
            'btn_submit' => 'Tạo Schema JSON-LD Ngay',
        ],
    ],
    'open-graph-generator' => [
        'name' => 'Tạo Thẻ Open Graph & Twitter Cards',
        'summary' => 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.',
        'description_markdown' => '## 📌 Trình Tạo Thẻ Open Graph & Twitter Cards

Tối ưu hóa hình ảnh thumbnail, tiêu đề và mô tả khi liên kết bài viết được chia sẻ trên Facebook, Twitter/X, Zalo, LinkedIn và Telegram.',
        'meta_title' => 'Tạo Thẻ Open Graph & Twitter Cards Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.',
        'ui' => [
            'title_label' => 'Tiêu đề chia sẻ (OG Title)',
            'btn_load_sample' => 'Nạp Mẫu OG',
            'title_placeholder' => 'Tiêu đề hiển thị khi chia sẻ link...',
            'title_sample' => 'TechHub — Nền Tảng Công Cụ Lập Trình & SEO Trực Tuyến Số 1',
            'desc_label' => 'Đoạn mô tả ngắn (OG Description)',
            'desc_placeholder' => 'Đoạn trích dẫn bài viết khi hiển thị trên newsfeed...',
            'desc_sample' => 'Trải nghiệm hơn 20+ tiện ích lập trình, máy tính và công cụ tối ưu SEO Onpage tốc độ cực nhanh, bảo mật tuyệt đối không lưu dữ liệu.',
            'image_label' => 'Đường dẫn ảnh Thumbnail (Khuyên dùng 1200x630px)',
            'url_label' => 'Đường dẫn đích (Canonical URL)',
            'site_name_label' => 'Tên website (Site Name)',
            'type_label' => 'Loại Open Graph (OG Type)',
            'type_website' => 'website (Trang chủ / Danh mục)',
            'type_article' => 'article (Bài viết blog / Tin tức)',
            'type_product' => 'product (Sản phẩm thương mại)',
            'twitter_card_label' => 'Kiểu thẻ Twitter / X',
            'card_large' => 'summary_large_image (Ảnh lớn)',
            'card_summary' => 'summary (Ảnh vuông nhỏ)',
            'twitter_site_label' => 'Twitter / X Account',
            'btn_submit' => 'Tạo Thẻ Chia Sẻ & Xem Trước Social Card',
        ],
    ],
    'robots-txt-generator' => [
        'name' => 'Tạo & Kiểm Tra Tệp Robots.txt',
        'summary' => 'Tạo và kiểm tra cú pháp tệp robots.txt chuẩn SEO, hỗ trợ chặn AI Bot, thiết lập Crawl-delay và Sitemap.',
        'description_markdown' => '## 📌 Trình Tạo & Phân Tích Cú Pháp Robots.txt

Thiết lập quyền thu thập dữ liệu cho bot công cụ tìm kiếm, chặn các AI scraper (GPTBot, CCBot), hỗ trợ định dạng cho WordPress, Laravel hoặc tùy biến riêng.',
        'meta_title' => 'Tạo & Kiểm Tra Tệp Robots.txt Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo và kiểm tra cú pháp tệp robots.txt chuẩn SEO, hỗ trợ chặn AI Bot, thiết lập Crawl-delay và Sitemap.',
        'ui' => [
            'preset_label' => 'Mẫu cấu hình sẵn (Presets)',
            'btn_load_sample' => 'Nạp Mẫu Tiêu Chuẩn',
            'preset_default' => '⚡ Cấu hình chuẩn SEO (Mặc định)',
            'preset_allow_all' => '🟢 Cho phép tất cả Bot thu thập (Allow All)',
            'preset_block_all' => '🔴 Chặn toàn bộ Bot (Disallow All - Dành cho web đang phát triển)',
            'preset_block_ai' => '🛡️ Chặn toàn bộ AI Crawlers (OpenAI, Anthropic, CCBot)',
            'preset_wordpress' => '🌐 Tối ưu riêng cho WordPress',
            'preset_laravel' => '🚀 Tối ưu riêng cho Laravel Web App',
            'disallow_label' => 'Đường dẫn cần CHẶN (Mỗi dòng một mục Disallow)',
            'allow_label' => 'Đường dẫn CHO PHÉP (Mỗi dòng một mục Allow)',
            'sitemap_label' => 'Đường dẫn tệp Sitemap XML',
            'delay_label' => 'Crawl-delay (Giây - Tùy chọn)',
            'delay_placeholder' => 'Để trống nếu không cần',
            'block_ai_label' => '🛡️ Tự động bổ sung quy tắc chặn AI Bot & Web Scraper (GPTBot, CCBot, Claude-Web, PerplexityBot)',
            'btn_submit' => 'Tạo Tệp Robots.txt Ngay',
        ],
    ],
    'sitemap-generator' => [
        'name' => 'Tạo Sơ Đồ Trang Web XML Sitemap',
        'summary' => 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).',
        'description_markdown' => '## 📌 Tạo Sơ Đồ Trang Web XML Sitemap Trực Tuyến

Tạo tệp `sitemap.xml` hợp lệ để gửi lên Google Search Console và Bing Webmaster Tools, hỗ trợ gắn thuộc tính ưu tiên và tần suất cập nhật.',
        'meta_title' => 'Tạo Sơ Đồ Trang Web XML Sitemap Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).',
        'ui' => [
            'hero_title' => 'Better Indexing Starts Here',
            'hero_desc' => 'Tự động quét (crawl) website và tạo tệp XML Sitemap chuẩn Google, Bing & Sitemaps.org. Nhanh chóng, miễn phí 100% và không cần đăng ký.',
            'domain_placeholder' => 'Nhập tên miền website (VD: https://muabanwebsite.io.vn)...',
            'btn_generate' => 'Tạo Sitemap',
            'btn_advanced' => '⚙️ Cài Đặt Nâng Cao (Settings ▾)',
            'freq_label' => 'Tần suất cập nhật (Changefreq)',
            'freq_daily' => 'daily (Hàng ngày)',
            'freq_weekly' => 'weekly (Hàng tuần)',
            'freq_monthly' => 'monthly (Hàng tháng)',
            'freq_always' => 'always (Liên tục)',
            'freq_hourly' => 'hourly (Mỗi giờ)',
            'priority_label' => 'Độ ưu tiên mặc định (Priority)',
            'priority_10' => '1.0 (Trang chủ / Cao nhất)',
            'priority_08' => '0.8 (Trang bài viết / Chi tiết)',
            'priority_06' => '0.6 (Trang phụ / Tiện ích)',
            'priority_05' => '0.5 (Trang liên hệ / Giới thiệu)',
            'max_urls_label' => 'Số trang quét tối đa (Max URLs)',
            'opt_50' => '50 trang',
            'opt_100' => '100 trang',
            'opt_250' => '250 trang',
            'opt_500' => '500 trang',
            'lastmod_label' => 'Tự động đính kèm ngày sửa đổi (Lastmod)',
            'btn_manual_mode' => '✍️ Chế độ nhập URL thủ công',
            'manual_label' => 'Danh sách URL tuỳ chỉnh (Nếu không muốn tự động quét)',
            'badge_free' => '⚡ 100% Miễn Phí',
            'badge_crawler' => '🕷️ Live Web Crawler',
            'badge_standards' => '🔍 Chuẩn Google & Bing',
            'badge_export' => '📄 Xuất Tệp sitemap.xml Ngay',
        ],
    ],
    'slug-generator' => [
        'name' => 'Tạo URL Slug Chuẩn SEO (Lọc Stop Words)',
        'summary' => 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.',
        'description_markdown' => '## 📌 Bộ Chuyển Đổi URL Slug Thân Thiện Chuẩn SEO

Chuyển tiêu đề tiếng Việt có dấu thành chuỗi URL không dấu, tự động loại bỏ các từ dừng thừa thãi (Stop words) để URL ngắn gọn, tập trung từ khóa SEO.',
        'meta_title' => 'Tạo URL Slug Chuẩn SEO (Lọc Stop Words) Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.',
        'ui' => [
            'input_label' => 'Tiêu đề bài viết / Đoạn văn bản cần tạo Slug',
            'btn_load_sample' => 'Nạp Tiêu Đề Mẫu',
            'input_placeholder' => 'Nhập tiêu đề bài viết tiếng Việt có dấu hoặc ký tự đặc biệt...',
            'input_sample' => 'Hướng Dẫn Toàn Diện Về Cách Tối Ưu Hóa SEO Onpage Cho Website Năm 2026!',
            'separator_label' => 'Ký tự phân cách (Separator)',
            'sep_hyphen' => '- Dấu gạch ngang (Chuẩn Google)',
            'sep_underscore' => '_ Dấu gạch dưới (Snake case)',
            'case_label' => 'Định dạng chữ (Case Format)',
            'max_len_label' => 'Độ dài tối đa (Ký tự)',
            'stop_words_label' => '⚡ Tự động loại bỏ từ dừng (Stop Words: và, là, của, cho, ở, in, on, the...) giúp URL ngắn gọn và chuẩn SEO',
            'btn_submit' => 'Tạo URL Slug Chuẩn SEO',
        ],
    ],
    'proxy-checker' => [
        'name' => 'Kiểm Tra Proxy (HTTP, HTTPS, SOCKS4, SOCKS5)',
        'summary' => 'Kiểm tra trạng thái sống/chết (Live/Dead), tốc độ phản hồi (Latency), địa chỉ IP đầu ra, quốc gia và mức độ ẩn danh của Proxy đơn lẻ hoặc danh sách hàng loạt.',
        'description_markdown' => '## 📌 Kiểm Tra Proxy Trực Tuyến Đa Giao Thức

Kiểm tra tính khả dụng của Proxy HTTP/HTTPS/SOCKS4/SOCKS5 trong thời gian thực. Đo độ trễ ping ms, kiểm tra IP thoát và mức độ ẩn danh bảo mật.',
        'meta_title' => 'Kiểm Tra Proxy (HTTP, HTTPS, SOCKS4, SOCKS5) Online — Miễn Phí 100% | TechHub',
        'meta_description' => 'Kiểm tra trạng thái sống/chết (Live/Dead), tốc độ phản hồi (Latency), địa chỉ IP đầu ra, quốc gia và mức độ ẩn danh của Proxy đơn lẻ hoặc danh sách hàng loạt.',
        'ui' => [
            'input_label' => 'Danh sách Proxy cần kiểm tra (Mỗi dòng 1 Proxy, tối đa 20 proxy/lần)',
            'btn_load_sample' => 'Nạp Proxy Mẫu',
            'btn_clear' => 'Xóa trắng',
            'supported_formats' => 'Định dạng hỗ trợ:',
            'protocol_label' => 'Giao thức kiểm tra (Protocol)',
            'protocol_auto' => '⚡ Tự động nhận diện (Auto Detect - Khuyên dùng)',
            'protocol_http' => '🌐 HTTP / HTTPS Proxy',
            'protocol_socks5' => '🧦 SOCKS5 Proxy',
            'protocol_socks4' => '🔌 SOCKS4 Proxy',
            'timeout_label' => 'Thời gian chờ phản hồi tối đa (Timeout)',
            'timeout_3s' => '⚡ 3 giây (Quét nhanh)',
            'timeout_5s' => '⏱️ 5 giây (Tiêu chuẩn)',
            'timeout_10s' => '⏳ 10 giây (Proxy chậm / xa)',
            'help_security' => '🛡️ Kiểm tra trực tiếp IP thoát, vị trí GeoIP, ISP và tính ẩn danh.',
            'btn_submit' => 'Kiểm Tra Danh Sách Proxy',
        ],
    ],
];
