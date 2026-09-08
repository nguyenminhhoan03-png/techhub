<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Deal\Entities\DigitalDeal;
use Illuminate\Database\Seeder;

class DigitalDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = [
            [
                'slug'                => 'gemini-pro-5tb-antigravity-1-nam',
                'name'                => 'Gemini Pro + 5TB + Antigravity - Hạn 1 năm | Nâng chính chủ riêng tư, bảo hành full',
                'category'            => 'Google AI',
                'badge_text'          => 'KHÔNG TRÙNG',
                'sub_badge'           => 'GOOGLE',
                'tags'                => ['google one pro', 'antigravity pro', 'gemini pro'],
                'price'               => 50000,
                'original_price'      => 500000,
                'discount_percentage' => 90,
                'rating'              => 5.0,
                'rating_count'        => 346,
                'sold_count'          => 7700,
                'stock_status'        => 'in_stock',
                'thumbnail_url'       => '/images/deals/gemini-pro-5tb.png',
                'variants'            => [
                    [
                        'name'       => '1 slot mail khách - add family 12 tháng',
                        'price'      => 50000,
                        'is_default' => true,
                    ],
                    [
                        'name'       => 'Cấp acc chính chủ fam 12 tháng - thêm được 5 slot',
                        'price'      => 139000,
                        'is_default' => false,
                    ],
                ],
                'commitments' => [
                    [
                        'icon'  => '🛡️',
                        'title' => 'Bảo vệ bởi Escrow',
                        'desc'  => 'Giao dịch an toàn & uy tín tuyệt đối',
                    ],
                    [
                        'icon'  => '⏱️',
                        'title' => 'Giữ tiền 72h',
                        'desc'  => 'Bảo hành 1-đổi-1 hoặc hoàn tiền nếu có lỗi',
                    ],
                    [
                        'icon'  => '⚡',
                        'title' => 'Giao hàng tức thì',
                        'desc'  => 'Kích hoạt ngay trong 5 - 15 phút',
                    ],
                ],
                'summary' => 'Nâng cấp tài khoản Google AI Pro (Gemini Advanced + 5TB Google Drive + Antigravity) hạn 1 năm. Kích hoạt trực tiếp trên email chính chủ, riêng tư bảo mật dữ liệu tuyệt đối.',
                'description_markdown' => "## 🌟 Giới Thiệu Gói Google AI Pro & Gemini Advanced 5TB\n\nTrải nghiệm sức mạnh trí tuệ nhân tạo đỉnh cao nhất của Google với mức giá siêu ưu đãi dành riêng cho cộng đồng Lập trình viên và Sáng tạo nội dung tại TechHub.\n\n### 🚀 Quyền Lợi Bản Quyền Đi Kèm:\n- **Gemini Advanced:** Sử dụng mô hình AI thông minh nhất thế giới của Google với khả năng phân tích ngữ cảnh cực lớn (1M+ context window), code thông minh, hỗ trợ đa ngôn ngữ.\n- **Dung lượng 5TB Google Drive:** Lưu trữ thoải mái dữ liệu, source code, backup hình ảnh chất lượng gốc trên Google Photos & Gmail.\n- **Hỗ trợ Antigravity / Google AI Studio:** Sử dụng API và các tính năng developer nâng cao không giới hạn.\n- **Nâng chính chủ riêng tư 100%:** Kích hoạt thẳng vào tài khoản Gmail của bạn, không ai có quyền xem dữ liệu của bạn.\n\n---\n\n## 🛠️ Phân Loại Gói Đăng Ký\n\n1. **Gói 1: 1 Slot Mail Khách - Add Family 12 Tháng (50.000đ)**\n   - Admin mời email Gmail của bạn tham gia nhóm Family có sẵn gói 5TB + Gemini Pro.\n   - Dữ liệu hoàn toàn riêng tư, độc lập, không ai xem được file của nhau.\n\n2. **Gói 2: Cấp Acc Chính Chủ Fam 12 Tháng - Thêm Được 5 Slot (139.000đ)**\n   - Cấp quyền Master Family cho bạn, bạn có thể tự add thêm 5 thành viên gia đình hoặc bạn bè để cùng dùng chung 5TB dung lượng!\n\n---\n\n## 📋 Quy Trình Nhận Hàng & Bảo Hành\n- **Thời gian xử lý:** Sau khi gửi email Gmail của bạn qua Zalo hoặc Telegram, hệ thống sẽ kích hoạt chỉ trong **5 - 15 phút**.\n- **Chế độ bảo hành:** Bảo hành trọn đời thời hạn 12 tháng. Nếu có bất kỳ sự cố gián đoạn nào sẽ được hỗ trợ xử lý 1-đổi-1 hoặc hoàn tiền tương ứng thời gian còn lại.",
                'zalo_contact'        => '0866655803',
                'telegram_contact'    => 'https://t.me/hoannm',
                'is_featured'         => true,
                'is_active'           => true,
                'sort_order'          => 1,
                'meta_title'          => 'Mua Tài Khoản Google AI Pro (Gemini Advanced 5TB) Giá Rẻ | TechHub Deals',
                'meta_description'    => 'Nâng cấp tài khoản Google AI Pro, Gemini Advanced + 5TB Cloud Storage giá rẻ chỉ từ 50.000đ/năm. Chính chủ, bảo mật 100%, bảo hành full 12 tháng.',
            ],
            [
                'slug'                => 'chatgpt-plus-team-chinh-chu',
                'name'                => 'Tài Khoản ChatGPT Plus / Team GPT-4o & Canvas — Hạn 1 Tháng / 1 Năm Chính Chủ',
                'category'            => 'ChatGPT & OpenAI',
                'badge_text'          => 'HOT DEAL',
                'sub_badge'           => 'OPENAI',
                'tags'                => ['chatgpt plus', 'gpt-4o', 'dall-e 3', 'canvas'],
                'price'               => 99000,
                'original_price'      => 490000,
                'discount_percentage' => 80,
                'rating'              => 4.9,
                'rating_count'        => 215,
                'sold_count'          => 4300,
                'stock_status'        => 'in_stock',
                'thumbnail_url'       => '/images/deals/chatgpt-plus.png',
                'variants'            => [
                    [
                        'name'       => 'ChatGPT Plus Slot Riêng Tư 1 Tháng',
                        'price'      => 99000,
                        'is_default' => true,
                    ],
                    [
                        'name'       => 'ChatGPT Team Chính Chủ Email Khách 1 Năm',
                        'price'      => 690000,
                        'is_default' => false,
                    ],
                ],
                'commitments' => [
                    [
                        'icon'  => '🛡️',
                        'title' => 'Bảo vệ bởi Escrow',
                        'desc'  => 'An tâm trải nghiệm, bảo hành suốt thời hạn',
                    ],
                    [
                        'icon'  => '⚡',
                        'title' => 'Giao hàng 5 phút',
                        'desc'  => 'Đăng nhập dùng ngay không cần chờ đợi',
                    ],
                ],
                'summary' => 'Tài khoản ChatGPT Plus trải nghiệm GPT-4o không giới hạn, tạo ảnh DALL-E 3, viết code Canvas và Voice Mode tiên tiến.',
                'description_markdown' => "## 🔥 Trải Nghiệm ChatGPT Plus Đỉnh Cao\n\n- Tốc độ phản hồi GPT-4o siêu tốc, không bị nghẽn giờ cao điểm.\n- Tạo ảnh nghệ thuật bằng DALL-E 3 sắc nét.\n- Công cụ Canvas chỉnh sửa code và văn bản trực tiếp.",
                'zalo_contact'        => '0866655803',
                'telegram_contact'    => 'https://t.me/hoannm',
                'is_featured'         => true,
                'is_active'           => true,
                'sort_order'          => 2,
                'meta_title'          => 'Mua Tài Khoản ChatGPT Plus Giá Rẻ | TechHub Deals',
                'meta_description'    => 'Tài khoản ChatGPT Plus GPT-4o chính chủ giá rẻ chỉ từ 99k. Bảo hành 1-đổi-1, kích hoạt ngay lập tức.',
            ],
            [
                'slug'                => 'claude-pro-sonnet-chinh-chu',
                'name'                => 'Tài Khoản Claude Pro (Sonnet 3.5 & Opus) — Hạn 1 Tháng / 6 Tháng Viết Code Đỉnh Cao',
                'category'            => 'Claude AI',
                'badge_text'          => 'DEV CHOICE',
                'sub_badge'           => 'ANTHROPIC',
                'tags'                => ['claude pro', 'sonnet 3.5', 'artifacts', 'coder ai'],
                'price'               => 119000,
                'original_price'      => 550000,
                'discount_percentage' => 78,
                'rating'              => 5.0,
                'rating_count'        => 189,
                'sold_count'          => 3100,
                'stock_status'        => 'in_stock',
                'thumbnail_url'       => '/images/deals/claude-pro.png',
                'variants'            => [
                    [
                        'name'       => 'Claude Pro 1 Tháng — Dùng Riêng Tư',
                        'price'      => 119000,
                        'is_default' => true,
                    ],
                    [
                        'name'       => 'Claude Pro 6 Tháng — Tiết Kiệm Tối Đa',
                        'price'      => 590000,
                        'is_default' => false,
                    ],
                ],
                'commitments' => [
                    [
                        'icon'  => '🛡️',
                        'title' => 'Bảo vệ bởi Escrow',
                        'desc'  => 'Cam kết tài khoản sạch, không bị khóa giữa chừng',
                    ],
                    [
                        'icon'  => '⚡',
                        'title' => 'Kích hoạt tức thì',
                        'desc'  => 'Hỗ trợ kỹ thuật 24/7 qua Zalo & Telegram',
                    ],
                ],
                'summary' => 'Claude Pro hỗ trợ Sonnet 3.5 đỉnh cao lập trình, tính năng Artifacts trực quan, tư duy phân tích sâu sắc.',
                'description_markdown' => "## 🧠 Claude 3.5 Sonnet — Vũ Khí Cho Lập Trình Viên\n\nClaude 3.5 Sonnet hiện là mô hình AI lập trình mạnh nhất, viết code sạch, chuẩn kiến trúc và giải quyết các bài toán phức tạp mượt mà hơn bất kỳ mô hình nào khác.",
                'zalo_contact'        => '0866655803',
                'telegram_contact'    => 'https://t.me/hoannm',
                'is_featured'         => true,
                'is_active'           => true,
                'sort_order'          => 3,
                'meta_title'          => 'Mua Tài Khoản Claude Pro Sonnet 3.5 Giá Rẻ | TechHub Deals',
                'meta_description'    => 'Tài khoản Claude Pro chính chủ giá rẻ cho Developer. Viết code đỉnh cao với Claude 3.5 Sonnet & Artifacts.',
            ],
        ];

        foreach ($deals as $dealData) {
            DigitalDeal::updateOrCreate(
                ['slug' => $dealData['slug']],
                $dealData,
            );
        }
    }
}
