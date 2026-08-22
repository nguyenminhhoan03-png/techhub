<?php

declare(strict_types=1);

namespace Application\Seo\Services;

use Domain\Article\Entities\Article;
use Domain\Game\Entities\Game;
use Domain\Tool\Entities\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    private const INDEXNOW_ENDPOINT = 'https://api.indexnow.org/indexnow';
    private const DEFAULT_KEY = '51fc886668034faeaef27f8d1e361511';

    /**
     * Collect all crawlable URLs across Tools, Games, Articles, and Hubs.
     *
     * @return array<int, string>
     */
    public function collectAllUrls(string $host = 'muabanwebsite.io.vn'): array
    {
        $baseUrl = "https://{$host}";
        $urls = [
            $baseUrl . '/',
            $baseUrl . '/tools',
            $baseUrl . '/articles',
            $baseUrl . '/games',
            $baseUrl . '/compare',
            $baseUrl . '/reviews',
        ];

        // Active Tools
        $toolSlugs = Tool::query()->where('is_active', true)->pluck('slug');
        foreach ($toolSlugs as $slug) {
            $urls[] = "{$baseUrl}/tools/{$slug}";
        }

        // Published Articles
        $articleSlugs = Article::query()->where('status', 'published')->pluck('slug');
        foreach ($articleSlugs as $slug) {
            $urls[] = "{$baseUrl}/articles/{$slug}";
        }

        // Active Web Games (226+ Games)
        $gameSlugs = Game::query()->where('is_active', true)->pluck('slug');
        foreach ($gameSlugs as $slug) {
            $urls[] = "{$baseUrl}/games/{$slug}";
        }

        return array_values(array_unique($urls));
    }

    /**
     * Submit URL batch to IndexNow Protocol (Bing, Yandex, Naver, Copilot AI).
     *
     * @param array<int, string> $urls
     * @return array{success: bool, status: int, count: int, message: string}
     */
    public function submitUrls(array $urls, string $host = 'muabanwebsite.io.vn', ?string $key = null): array
    {
        $activeKey = $key ?: (string) config('services.indexnow.key', self::DEFAULT_KEY);
        $keyLocation = "https://{$host}/{$activeKey}.txt";

        if (empty($urls)) {
            return [
                'success' => false,
                'status'  => 400,
                'count'   => 0,
                'message' => 'No URLs to submit.',
            ];
        }

        // Chunk into batches of 5000 (Protocol limit is 10,000)
        $batches = array_chunk($urls, 5000);
        $totalSubmitted = 0;
        $lastStatus = 200;
        $lastBody = '';

        foreach ($batches as $batch) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders(['Content-Type' => 'application/json; charset=utf-8'])
                    ->post(self::INDEXNOW_ENDPOINT, [
                        'host'        => $host,
                        'key'         => $activeKey,
                        'keyLocation' => $keyLocation,
                        'urlList'     => $batch,
                    ]);

                $lastStatus = $response->status();
                $lastBody = $response->body();

                if ($response->successful() || $lastStatus === 202) {
                    $totalSubmitted += count($batch);
                } else {
                    Log::warning('IndexNow partial submission issue', [
                        'status' => $lastStatus,
                        'body'   => $lastBody,
                        'host'   => $host,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('IndexNow HTTP connection failed: ' . $e->getMessage());
                return [
                    'success' => false,
                    'status'  => 500,
                    'count'   => $totalSubmitted,
                    'message' => $e->getMessage(),
                ];
            }
        }

        $isSuccess = $totalSubmitted > 0;

        return [
            'success' => $isSuccess,
            'status'  => $lastStatus,
            'count'   => $totalSubmitted,
            'message' => $isSuccess 
                ? "Đã gửi thành công {$totalSubmitted} URLs tới IndexNow API." 
                : "Thất bại (HTTP {$lastStatus}): {$lastBody}",
        ];
    }
}
