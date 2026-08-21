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
            // Developer Tools
            [
                'category_id' => $categoryMap['developer'],
                'slug' => 'json-formatter',
                'name' => 'Định Dạng & Kiểm Tra Cú Pháp JSON',
                'summary' => 'Làm đẹp (Beautify), nén gọn (Minify) và kiểm tra lỗi cú pháp JSON tức thì với tùy chọn thụt đầu dòng linh hoạt.',
                'description_markdown' => '## Định Dạng & Kiểm Tra JSON Trực Tuyến
Sử dụng công cụ này để định dạng chuỗi JSON thô thành cấu trúc rõ ràng, dễ đọc, tự động làm nổi bật lỗi cú pháp và hỗ trợ nén minified dưới 5ms.',
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
                'summary' => 'Chuyển đổi văn bản hoặc tệp nhị phân sang chuỗi Base64 và ngược lại, hỗ trợ chuẩn mã hóa URL-safe.',
                'description_markdown' => '## Công Cụ Base64 Chuẩn RFC 4648
Mã hóa chuỗi ký tự hoặc dữ liệu nhị phân sang định dạng Base64 và giải mã ngược lại nhanh chóng, không lưu trữ dữ liệu người dùng.',
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
                'summary' => 'Tạo mã băm bảo mật MD5, SHA-1, SHA-256, SHA-512, Bcrypt với tùy chọn khóa bí mật HMAC an toàn.',
                'description_markdown' => '## Trình Tạo Mã Băm Mật Mã Học
Tính toán nhanh mã Checksum và giá trị băm cryptographic với độ chính xác tuyệt đối, hỗ trợ chữ hoa/thường và HMAC secret key.',
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
                'summary' => 'Giải mã trực quan cấu trúc Header, Payload claims, thuật toán mã hóa và thời hạn sống của JSON Web Token.',
                'description_markdown' => '## Trình Phân Tích & Giải Mã Token JWT
Dán chuỗi JWT để kiểm tra ngay cấu trúc JOSE header, thông tin payload claims, thuật toán chữ ký và trạng thái hết hạn token.',
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
                'name' => 'Kiểm Tra & Bóc Tách Biểu Thức Regex',
                'summary' => 'Kiểm tra biểu thức chính quy (PCRE) thời gian thực, bóc tách capture group và giải thích lỗi chi tiết.',
                'description_markdown' => '## Kiểm Tra Regular Expression (Regex)
Thử nghiệm các mẫu Regex với các cờ (flags) g, i, m, s, u. Trích xuất chính xác các kết quả khớp và nhóm vị trí index.',
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
                'name' => 'Mã Hóa & Giải Mã Đường Dẫn URL',
                'summary' => 'Mã hóa ký tự đặc biệt theo chuẩn RFC 3986 và giải mã các tham số truy vấn URL an toàn.',
                'description_markdown' => '## Mã Hóa & Giải Mã URL Chuẩn RFC 3986
Chuyển đổi các ký tự đặc biệt, dấu tiếng Việt thành chuỗi phần trăm an toàn cho trình duyệt và API.',
                'icon' => 'link-2',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 430,
                'view_count' => 1800,
                'rating_avg' => 4.85,
                'rating_count' => 20,
            ],

            // Calculator Tools
            [
                'category_id' => $categoryMap['calculators'],
                'slug' => 'loan-calculator',
                'name' => 'Tính Lãi Suất Vay & Trả Góp Ngân Hàng',
                'summary' => 'Tính số tiền trả hàng tháng (EMI), tổng tiền lãi, chi phí khoản vay và bảng lịch biểu trả nợ chi tiết.',
                'description_markdown' => '## Máy Tính Khoản Vay & Lãi Suất Trả Góp
Lập kế hoạch tài chính chính xác với công thức tính dư nợ giảm dần, số tiền gốc và lãi từng tháng, tổng chi phí sau khi tất toán.',
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
                'name' => 'Máy Tính Phần Trăm Trực Tuyến',
                'summary' => 'Tính phần trăm giá trị, tỷ lệ tăng giảm %, tính mức chiết khấu giảm giá và tỷ lệ nhanh chóng.',
                'description_markdown' => '## Máy Tính Phần Trăm Nhanh Chóng
Giải quyết các bài toán phần trăm thông dụng: X% của Y là bao nhiêu, tỷ lệ phần trăm tăng/giảm giữa hai số và tính giá sau giảm giá.',
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
                'summary' => 'Tính chỉ số thể trọng BMI, phân loại tình trạng sức khỏe theo WHO và xác định cân nặng lý tưởng.',
                'description_markdown' => '## Máy Tính Chỉ Số Sức Khỏe BMI
Đánh giá tình trạng thể trạng gầy, chuẩn hoặc thừa cân theo tiêu chuẩn quốc tế của Tổ chức Y tế Thế giới (WHO).',
                'icon' => 'activity',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 1500,
                'view_count' => 6200,
                'rating_avg' => 4.89,
                'rating_count' => 72,
            ],

            // Image Tools
            [
                'category_id' => $categoryMap['image'],
                'slug' => 'image-metadata-inspector',
                'name' => 'Kiểm Tra Thông Số Ảnh & EXIF',
                'summary' => 'Phân tích kích thước pixel, tỷ lệ khung hình, độ sâu màu, định dạng MIME và siêu dữ liệu camera EXIF.',
                'description_markdown' => '## Kiểm Tra Dữ Liệu Ảnh & Thông Số EXIF
Đọc chi tiết thông số ảnh số: Kích thước chiều rộng x cao, độ sâu bit màu, loại MIME type và thông số camera chụp ảnh.',
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
                'name' => 'Trích Xuất Bảng Màu Chủ Đạo Của Ảnh',
                'summary' => 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.',
                'description_markdown' => '## Trích Xuất Bảng Mã Màu Ảnh Trực Tuyến
Tải lên hình ảnh hoặc thiết kế để hệ thống tự động quét và tạo bảng màu (Color Palette) gồm các mã HEX/RGB chủ đạo phục vụ UI/UX.',
                'icon' => 'pipette',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 780,
                'view_count' => 3100,
                'rating_avg' => 4.94,
                'rating_count' => 49,
            ],

            // SEO Tools
            [
                'category_id' => $categoryMap['seo'],
                'slug' => 'serp-preview',
                'name' => 'Mô Phỏng Hiển Thị Google SERP Snippet',
                'summary' => 'Xem trước giao diện kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.',
                'description_markdown' => '## Google SERP Snippet Preview & Pixel Meter
Mô phỏng chính xác kết quả hiển thị trên Google Tìm Kiếm (máy tính và di động). Hỗ trợ đo độ rộng pixel và ký tự tiêu chuẩn để tránh bị Google cắt ngắn.',
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
                'name' => 'Tạo Thẻ Meta HTML5 Chuẩn SEO Onpage',
                'summary' => 'Tạo mã HTML5 thẻ Meta chuẩn SEO: Title, Description, Keywords, Canonical, Robots và phân tích SEO Onpage.',
                'description_markdown' => '## Trình Tạo Bộ Thẻ Meta HTML5 Chuẩn SEO
Tạo nhanh chóng toàn bộ mã thẻ meta cần thiết cho thẻ `<head>` của trang web bao gồm thẻ cơ bản, chỉ thị Robots và thẻ Canonical URL.',
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
                'name' => 'Tạo Schema Markup (JSON-LD) Chuẩn Google',
                'summary' => 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.',
                'description_markdown' => '## Trình Tạo Dữ Liệu Cấu Trúc Schema.org JSON-LD
Khai báo dữ liệu cấu trúc chuẩn Google để kích hoạt tính năng Rich Results (đánh giá sao, câu hỏi thường gặp FAQ, thông tin giá sản phẩm) trên kết quả tìm kiếm.',
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
                'summary' => 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.',
                'description_markdown' => '## Trình Tạo Thẻ Open Graph & Twitter Cards
Tối ưu hóa hình ảnh thumbnail, tiêu đề và mô tả khi liên kết bài viết được chia sẻ trên Facebook, Twitter/X, Zalo, LinkedIn và Telegram.',
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
                'name' => 'Tạo & Kiểm Tra Tệp Robots.txt',
                'summary' => 'Tạo và kiểm tra cú pháp tệp robots.txt chuẩn SEO, hỗ trợ chặn AI Bot, thiết lập Crawl-delay và Sitemap.',
                'description_markdown' => '## Trình Tạo & Phân Tích Cú Pháp Robots.txt
Thiết lập quyền thu thập dữ liệu cho bot công cụ tìm kiếm, chặn các AI scraper (GPTBot, CCBot), hỗ trợ định dạng cho WordPress, Laravel hoặc Custom.',
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
                'summary' => 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).',
                'description_markdown' => '## Tạo Sơ Đồ Trang Web XML Sitemap Trực Tuyến
Tạo tệp `sitemap.xml` hợp lệ để gửi lên Google Search Console và Bing Webmaster Tools, hỗ trợ gắn thuộc tính ưu tiên và tần suất cập nhật.',
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
                'name' => 'Tạo URL Slug Chuẩn SEO (Lọc Stop Words)',
                'summary' => 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.',
                'description_markdown' => '## Bộ Chuyển Đổi URL Slug Thân Thiện Chuẩn SEO
Chuyển tiêu đề tiếng Việt có dấu thành chuỗi URL không dấu, tự động loại bỏ các từ dừng thừa thãi (Stop words) để URL ngắn gọn, tập trung từ khóa SEO.',
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
