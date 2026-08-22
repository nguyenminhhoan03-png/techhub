<?php

declare(strict_types=1);

namespace Application\Seo\Commands;

use Application\Seo\Services\IndexNowService;
use Illuminate\Console\Command;

class SubmitIndexNowCommand extends Command
{
    protected $signature = 'seo:indexnow {--host=muabanwebsite.io.vn : Tên miền cần gửi index} {--key= : Key IndexNow tuỳ chọn}';

    protected $description = 'Gửi toàn bộ liên kết website sang IndexNow API để Bing, Copilot AI, Yandex và Naver lập chỉ mục siêu tốc';

    public function handle(IndexNowService $service): int
    {
        $host = (string) ($this->option('host') ?: 'muabanwebsite.io.vn');
        $key  = $this->option('key') ? (string) $this->option('key') : null;

        $this->info("🔍 Đang tổng hợp toàn bộ URLs chuẩn SEO cho tên miền: <fg=cyan>{$host}</>...");

        $urls = $service->collectAllUrls($host);
        $total = count($urls);

        $this->info("📦 Đã thu thập <fg=green>{$total}</> URLs (Trang chủ, 226+ Games, Tools, Articles, Hubs).");
        $this->comment("⏳ Đang kết nối tới IndexNow Protocol Gateway...");

        $result = $service->submitUrls($urls, $host, $key);

        if ($result['success']) {
            $this->newLine();
            $this->info("✨ " . $result['message']);
            $this->comment("⚡ Bing, Copilot AI và các công cụ tìm kiếm đối tác sẽ bắt đầu thu thập dữ liệu trong vài giây!");
            return self::SUCCESS;
        }

        $this->error("❌ Gửi IndexNow thất bại: " . $result['message']);
        return self::FAILURE;
    }
}
