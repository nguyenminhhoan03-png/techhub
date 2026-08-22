<?php

declare(strict_types=1);

use Application\Game\Commands\ImportGamesCommand;
use Domain\Article\Entities\Article;
use Domain\Game\Entities\Game;
use Domain\Tool\Entities\Tool;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::registerCommand(app(ImportGamesCommand::class));

Artisan::command('seo:indexnow {--host=muabanwebsite.io.vn}', function (): void {
    $host = (string) $this->option('host');
    $key = '51fc886668034faeaef27f8d1e361511';
    $keyLocation = "https://{$host}/{$key}.txt";

    $this->info("🚀 Đang tổng hợp toàn bộ liên kết từ Database cho {$host}...");

    $baseUrl = "https://{$host}";
    $urls = [
        $baseUrl . '/',
        $baseUrl . '/tools',
        $baseUrl . '/articles',
        $baseUrl . '/games',
        $baseUrl . '/compare',
        $baseUrl . '/reviews',
    ];

    // Add active tools
    $tools = Tool::query()->where('is_active', true)->pluck('slug');
    foreach ($tools as $slug) {
        $urls[] = "{$baseUrl}/tools/{$slug}";
    }

    // Add published articles
    $articles = Article::query()->where('status', 'published')->pluck('slug');
    foreach ($articles as $slug) {
        $urls[] = "{$baseUrl}/articles/{$slug}";
    }

    // Add active games
    $games = Game::query()->where('is_active', true)->pluck('slug');
    foreach ($games as $slug) {
        $urls[] = "{$baseUrl}/games/{$slug}";
    }

    $urls = array_values(array_unique($urls));
    $this->info("📦 Đã thu thập " . count($urls) . " URLs chuẩn bị gửi tới IndexNow API (Bing / Yandex / Naver)...");

    // IndexNow API accepts batches of up to 10,000 URLs
    try {
        $response = Http::timeout(15)
            ->withHeaders(['Content-Type' => 'application/json; charset=utf-8'])
            ->post('https://api.indexnow.org/indexnow', [
                'host' => $host,
                'key' => $key,
                'keyLocation' => $keyLocation,
                'urlList' => $urls,
            ]);

        if ($response->successful() || $response->status() === 202) {
            $this->info("✅ THÀNH CÔNG! Đã gửi toàn bộ " . count($urls) . " URLs sang IndexNow API.");
            $this->comment("Bot tìm kiếm Bing và các đối tác sẽ bắt đầu thu thập dữ liệu trong vài giây!");
        } else {
            $this->warn("Phản hồi từ IndexNow (HTTP " . $response->status() . "): " . $response->body());
        }
    } catch (\Throwable $e) {
        $this->error("Lỗi kết nối tới IndexNow API: " . $e->getMessage());
    }
})->purpose('Gửi toàn bộ liên kết website sang IndexNow API để Bing và đối tác index siêu tốc');
