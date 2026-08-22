<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Ad\Entities\Advertisement;
use Domain\Setting\Entities\Setting;
use Domain\User\Entities\User;
use Domain\User\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAndSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or Update Senior Admin User
        User::query()->updateOrCreate(
            ['email' => 'admin@techhub.local'],
            [
                'ulid' => (string) Str::ulid(),
                'name' => 'Senior Admin',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'status' => UserStatus::Active->value,
                'email_verified_at' => now(),
            ],
        );

        // 2. Initial System Settings
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'TechHub',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Tên Website',
                'description' => 'Tên hiển thị chính của nền tảng',
            ],
            [
                'key' => 'hero_title',
                'value' => 'Bộ Tiện Ích Lập Trình & Máy Tính Trực Tuyến',
                'group' => 'hero',
                'type' => 'text',
                'label' => 'Tiêu Đề Hero Banner',
                'description' => 'Tiêu đề lớn xuất hiện đầu trang chủ',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Định dạng JSON, giải mã JWT, kiểm tra Regex, tính lãi suất vay ngân hàng và trích xuất bảng màu ảnh tức thì với độ trễ dưới 5ms.',
                'group' => 'hero',
                'type' => 'textarea',
                'label' => 'Mô Tả Phụ Hero',
                'description' => 'Đoạn văn ngắn giới thiệu dưới tiêu đề Hero',
            ],
            [
                'key' => 'announcement_text',
                'value' => 'Ra mắt bộ 11+ công cụ lập trình & tài chính chuẩn xác 2026!',
                'group' => 'announcement',
                'type' => 'text',
                'label' => 'Nội Dung Thanh Thông Báo',
                'description' => 'Thông báo nổi bật trên cùng đầu trang',
            ],
            [
                'key' => 'announcement_link',
                'value' => '/tools',
                'group' => 'announcement',
                'type' => 'text',
                'label' => 'Đường Dẫn Thông Báo',
                'description' => 'Link khi nhấp vào thanh thông báo',
            ],
            [
                'key' => 'is_announcement_active',
                'value' => '1',
                'group' => 'announcement',
                'type' => 'boolean',
                'label' => 'Bật Thanh Thông Báo',
                'description' => 'Bật hoặc tắt thanh thông báo trên cùng',
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@techhub.local',
                'group' => 'contact',
                'type' => 'text',
                'label' => 'Email Liên Hệ',
                'description' => 'Email nhận phản hồi và hỗ trợ',
            ],
            // SEO & Tracking Settings
            [
                'key' => 'google_analytics_id',
                'value' => env('GOOGLE_ANALYTICS_ID', 'G-7TJK356QR4'),
                'group' => 'seo',
                'type' => 'text',
                'label' => 'Google Analytics 4 (GA4) Measurement ID',
                'description' => 'Mã đo lường Google Analytics (ví dụ: G-XXXXXXXXXX) để xem traffic Realtime',
            ],
            // AI Content & LLM Settings
            [
                'key' => 'gemini_api_key',
                'value' => env('GEMINI_API_KEY', ''),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'Google Gemini API Key',
                'description' => 'Khóa API Google Gemini để sinh bài viết và phân tích linh kiện thời gian thực (Miễn phí 15 RPM)',
            ],
            [
                'key' => 'openai_api_key',
                'value' => env('OPENAI_API_KEY', ''),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'OpenAI API Key (Tùy chọn)',
                'description' => 'Khóa API OpenAI để sử dụng GPT-4o / GPT-4o-mini khi cần',
            ],
            [
                'key' => 'ai_default_provider',
                'value' => 'gemini',
                'group' => 'ai',
                'type' => 'text',
                'label' => 'Nhà Cung Cấp AI Mặc Định',
                'description' => 'Chọn "gemini" hoặc "openai"',
            ],
            [
                'key' => 'ai_model_name',
                'value' => 'gemini-1.5-flash',
                'group' => 'ai',
                'type' => 'text',
                'label' => 'Tên Model AI',
                'description' => 'gemini-1.5-flash, gemini-1.5-pro, gpt-4o-mini, gpt-4o',
            ],
            [
                'key' => 'ai_auto_publish',
                'value' => '1',
                'group' => 'ai',
                'type' => 'boolean',
                'label' => 'Tự Động Xuất Bản Bài Viết Sau Khi Sinh',
                'description' => 'Bật để bài viết xuất bản (Published) ngay lập tức, tắt để lưu ở dạng Bản nháp (Draft)',
            ],
        ];

        foreach ($settings as $settingData) {
            Setting::query()->updateOrCreate(
                ['key' => $settingData['key']],
                $settingData,
            );
        }

        // 3. Sample Advertisements
        $ads = [
            [
                'name' => 'Header Top Sponsor Banner',
                'slot' => 'header_top',
                'type' => 'custom_banner',
                'image_url' => 'https://placehold.co/728x90/111827/38bdf8?text=TechHub+High+Performance+Developer+APIs',
                'target_url' => 'https://techhub.local/tools',
                'is_active' => true,
            ],
            [
                'name' => 'Tool Workspace Bottom Banner',
                'slot' => 'tool_workspace_bottom',
                'type' => 'custom_banner',
                'image_url' => 'https://placehold.co/728x90/0f172a/818cf8?text=Deploy+Faster+With+TechHub+Clean+Architecture',
                'target_url' => 'https://techhub.local/tools',
                'is_active' => true,
            ],
        ];

        foreach ($ads as $adData) {
            Advertisement::query()->updateOrCreate(
                ['name' => $adData['name']],
                $adData,
            );
        }
    }
}
