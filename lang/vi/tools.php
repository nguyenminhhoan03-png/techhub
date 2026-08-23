<?php

declare(strict_types=1);

return [
    'json-formatter' => [
        'name' => 'Định Dạng & Kiểm Tra Cú Pháp JSON',
        'summary' => 'Làm đẹp (Beautify), nén gọn (Minify) và kiểm tra lỗi cú pháp JSON tức thì với tùy chọn thụt đầu dòng linh hoạt.',
        'description_markdown' => '## Định Dạng & Kiểm Tra JSON Trực Tuyến
Sử dụng công cụ này để định dạng chuỗi JSON thô thành cấu trúc rõ ràng, dễ đọc, tự động làm nổi bật lỗi cú pháp và hỗ trợ nén minified dưới 5ms.',
    ],
    'base64-encode-decode' => [
        'name' => 'Mã Hóa & Giải Mã Base64',
        'summary' => 'Chuyển đổi văn bản hoặc tệp nhị phân sang chuỗi Base64 và ngược lại, hỗ trợ chuẩn mã hóa URL-safe.',
        'description_markdown' => '## Công Cụ Base64 Chuẩn RFC 4648
Mã hóa chuỗi ký tự hoặc dữ liệu nhị phân sang định dạng Base64 và giải mã ngược lại nhanh chóng, không lưu trữ dữ liệu người dùng.',
    ],
    'hash-generator' => [
        'name' => 'Tạo Mã Băm Hash & Checksum',
        'summary' => 'Tạo mã băm bảo mật MD5, SHA-1, SHA-256, SHA-512, Bcrypt với tùy chọn khóa bí mật HMAC an toàn.',
        'description_markdown' => '## Trình Tạo Mã Băm Mật Mã Học
Tính toán nhanh mã Checksum và giá trị băm cryptographic với độ chính xác tuyệt đối, hỗ trợ chữ hoa/thường và HMAC secret key.',
    ],
    'jwt-debugger' => [
        'name' => 'Giải Mã & Kiểm Tra Token JWT',
        'summary' => 'Giải mã trực quan cấu trúc Header, Payload claims, thuật toán mã hóa và thời hạn sống của JSON Web Token.',
        'description_markdown' => '## Trình Phân Tích & Giải Mã Token JWT
Dán chuỗi JWT để kiểm tra ngay cấu trúc JOSE header, thông tin payload claims, thuật toán chữ ký và trạng thái hết hạn token.',
    ],
    'regex-tester' => [
        'name' => 'Kiểm Tra & Bóc Tách Biểu Thức Regex',
        'summary' => 'Kiểm tra biểu thức chính quy (PCRE) thời gian thực, bóc tách capture group và giải thích lỗi chi tiết.',
        'description_markdown' => '## Kiểm Tra Regular Expression (Regex)
Thử nghiệm các mẫu Regex với các cờ (flags) g, i, m, s, u. Trích xuất chính xác các kết quả khớp và nhóm vị trí index.',
    ],
    'url-encoder-decoder' => [
        'name' => 'Mã Hóa & Giải Mã Đường Dẫn URL',
        'summary' => 'Mã hóa ký tự đặc biệt theo chuẩn RFC 3986 và giải mã các tham số truy vấn URL an toàn.',
        'description_markdown' => '## Mã Hóa & Giải Mã URL Chuẩn RFC 3986
Chuyển đổi các ký tự đặc biệt, dấu tiếng Việt thành chuỗi phần trăm an toàn cho trình duyệt và API.',
    ],
    'loan-calculator' => [
        'name' => 'Tính Lãi Suất Vay & Trả Góp Ngân Hàng',
        'summary' => 'Tính số tiền trả hàng tháng (EMI), tổng tiền lãi, chi phí khoản vay và bảng lịch biểu trả nợ chi tiết.',
        'description_markdown' => '## Máy Tính Khoản Vay & Lãi Suất Trả Góp
Lập kế hoạch tài chính chính xác với công thức tính dư nợ giảm dần, số tiền gốc và lãi từng tháng, tổng chi phí sau khi tất toán.',
    ],
    'percentage-calculator' => [
        'name' => 'Máy Tính Phần Trăm Trực Tuyến',
        'summary' => 'Tính phần trăm giá trị, tỷ lệ tăng giảm %, tính mức chiết khấu giảm giá và tỷ lệ nhanh chóng.',
        'description_markdown' => '## Máy Tính Phần Trăm Nhanh Chóng
Giải quyết các bài toán phần trăm thông dụng: X% của Y là bao nhiêu, tỷ lệ phần trăm tăng/giảm giữa hai số và tính giá sau giảm giá.',
    ],
    'bmi-calculator' => [
        'name' => 'Tính Chỉ Số Khối Cơ Thể (BMI)',
        'summary' => 'Tính chỉ số thể trọng BMI, phân loại tình trạng sức khỏe theo WHO và xác định cân nặng lý tưởng.',
        'description_markdown' => '## Máy Tính Chỉ Số Sức Khỏe BMI
Đánh giá tình trạng thể trạng gầy, chuẩn hoặc thừa cân theo tiêu chuẩn quốc tế của Tổ chức Y tế Thế giới (WHO).',
    ],
    'image-metadata-inspector' => [
        'name' => 'Kiểm Tra Thông Số Ảnh & EXIF',
        'summary' => 'Phân tích kích thước pixel, tỷ lệ khung hình, độ sâu màu, định dạng MIME và siêu dữ liệu camera EXIF.',
        'description_markdown' => '## Kiểm Tra Dữ Liệu Ảnh & Thông Số EXIF
Đọc chi tiết thông số ảnh số: Kích thước chiều rộng x cao, độ sâu bit màu, loại MIME type và thông số camera chụp ảnh.',
    ],
    'image-color-extractor' => [
        'name' => 'Trích Xuất Bảng Màu Chủ Đạo Của Ảnh',
        'summary' => 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.',
        'description_markdown' => '## Trích Xuất Bảng Mã Màu Ảnh Trực Tuyến
Tải lên hình ảnh hoặc thiết kế để hệ thống tự động quét và tạo bảng màu (Color Palette) gồm các mã HEX/RGB chủ đạo phục vụ UI/UX.',
    ],
    'serp-preview' => [
        'name' => 'Mô Phỏng Hiển Thị Google SERP Snippet',
        'summary' => 'Xem trước giao diện kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.',
        'description_markdown' => '## Google SERP Snippet Preview & Pixel Meter
Mô phỏng chính xác kết quả hiển thị trên Google Tìm Kiếm (máy tính và di động). Hỗ trợ đo độ rộng pixel và ký tự tiêu chuẩn để tránh bị Google cắt ngắn.',
    ],
    'meta-tag-generator' => [
        'name' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage',
        'summary' => 'Tạo mã HTML5 thẻ Meta chuẩn SEO: Title, Description, Keywords, Canonical, Robots và phân tích SEO Onpage.',
        'description_markdown' => '## Trình Tạo Bộ Thẻ Meta HTML5 Chuẩn SEO
Tạo nhanh chóng toàn bộ mã thẻ meta cần thiết cho thẻ `<head>` của trang web bao gồm thẻ cơ bản, chỉ thị Robots và thẻ Canonical URL.',
    ],
    'schema-generator' => [
        'name' => 'Tạo Schema Markup (JSON-LD) Chuẩn Google',
        'summary' => 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.',
        'description_markdown' => '## Trình Tạo Dữ Liệu Cấu Trúc Schema.org JSON-LD
Khai báo dữ liệu cấu trúc chuẩn Google để kích hoạt tính năng Rich Results (đánh giá sao, câu hỏi thường gặp FAQ, thông tin giá sản phẩm) trên kết quả tìm kiếm.',
    ],
    'open-graph-generator' => [
        'name' => 'Tạo Thẻ Open Graph & Twitter Cards',
        'summary' => 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.',
        'description_markdown' => '## Trình Tạo Thẻ Open Graph & Twitter Cards
Tối ưu hóa hình ảnh thumbnail, tiêu đề và mô tả khi liên kết bài viết được chia sẻ trên Facebook, Twitter/X, Zalo, LinkedIn và Telegram.',
    ],
    'robots-txt-generator' => [
        'name' => 'Tạo & Kiểm Tra Tệp Robots.txt',
        'summary' => 'Tạo và kiểm tra cú pháp tệp robots.txt chuẩn SEO, hỗ trợ chặn AI Bot, thiết lập Crawl-delay và Sitemap.',
        'description_markdown' => '## Trình Tạo & Phân Tích Cú Pháp Robots.txt
Thiết lập quyền thu thập dữ liệu cho bot công cụ tìm kiếm, chặn các AI scraper (GPTBot, CCBot), hỗ trợ định dạng cho WordPress, Laravel hoặc Custom.',
    ],
    'sitemap-generator' => [
        'name' => 'Tạo Sơ Đồ Trang Web XML Sitemap',
        'summary' => 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).',
        'description_markdown' => '## Tạo Sơ Đồ Trang Web XML Sitemap Trực Tuyến
Tạo tệp `sitemap.xml` hợp lệ để gửi lên Google Search Console và Bing Webmaster Tools, hỗ trợ gắn thuộc tính ưu tiên và tần suất cập nhật.',
    ],
    'slug-generator' => [
        'name' => 'Tạo URL Slug Chuẩn SEO (Lọc Stop Words)',
        'summary' => 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.',
        'description_markdown' => '## Bộ Chuyển Đổi URL Slug Thân Thiện Chuẩn SEO
Chuyển tiêu đề tiếng Việt có dấu thành chuỗi URL không dấu, tự động loại bỏ các từ dừng thừa thãi (Stop words) để URL ngắn gọn, tập trung từ khóa SEO.',
    ],
];
