<?php

declare(strict_types=1);

namespace Application\Game\Services;

use Domain\Game\Entities\Game;
use Domain\Game\Entities\GameCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GameFeedImportService
{
    private const GAMEMONETIZE_ENDPOINT = 'https://gamemonetize.com/rss.php';
    private const GAMEPIX_ENDPOINT      = 'https://games.gamepix.com/games';

    /**
     * Map external categories from feeds to TechHub internal category slugs.
     *
     * @var array<string, array{slug: string, name: string, icon: string, color: string}>
     */
    private const CATEGORY_MAP = [
        'action'       => ['slug' => 'action', 'name' => 'Hành Động & Phiêu Lưu', 'icon' => '🎯', 'color' => '#e11d48'],
        'adventure'    => ['slug' => 'action', 'name' => 'Hành Động & Phiêu Lưu', 'icon' => '🎯', 'color' => '#e11d48'],
        'shooting'     => ['slug' => 'action', 'name' => 'Hành Động & Phiêu Lưu', 'icon' => '🎯', 'color' => '#e11d48'],
        'puzzles'      => ['slug' => 'puzzle', 'name' => 'Câu Đố & Logic', 'icon' => '🧩', 'color' => '#7c3aed'],
        'puzzle'       => ['slug' => 'puzzle', 'name' => 'Câu Đố & Logic', 'icon' => '🧩', 'color' => '#7c3aed'],
        'junior'       => ['slug' => 'puzzle', 'name' => 'Câu Đố & Logic', 'icon' => '🧩', 'color' => '#7c3aed'],
        'arcade'       => ['slug' => 'arcade', 'name' => 'Arcade & Classic', 'icon' => '🏓', 'color' => '#0284c7'],
        'classic'      => ['slug' => 'arcade', 'name' => 'Arcade & Classic', 'icon' => '🏓', 'color' => '#0284c7'],
        'board'        => ['slug' => 'arcade', 'name' => 'Arcade & Classic', 'icon' => '🏓', 'color' => '#0284c7'],
        'card'         => ['slug' => 'arcade', 'name' => 'Arcade & Classic', 'icon' => '🏓', 'color' => '#0284c7'],
        'racing'       => ['slug' => 'runner', 'name' => 'Endless Runner & Đua Xe', 'icon' => '🏃', 'color' => '#d97706'],
        'driving'      => ['slug' => 'runner', 'name' => 'Endless Runner & Đua Xe', 'icon' => '🏃', 'color' => '#d97706'],
        'hypercasual'  => ['slug' => 'casual', 'name' => 'Giải Trí Nhanh', 'icon' => '🎲', 'color' => '#db2777'],
        'casual'       => ['slug' => 'casual', 'name' => 'Giải Trí Nhanh', 'icon' => '🎲', 'color' => '#db2777'],
        'girls'        => ['slug' => 'casual', 'name' => 'Giải Trí Nhanh', 'icon' => '🎲', 'color' => '#db2777'],
        'sports'       => ['slug' => 'arcade', 'name' => 'Arcade & Thể Thao', 'icon' => '⚽', 'color' => '#059669'],
        'strategy'     => ['slug' => 'puzzle', 'name' => 'Chiến Thuật & Trí Tuệ', 'icon' => '🧠', 'color' => '#059669'],
        'multiplayer'  => ['slug' => 'action', 'name' => 'Hành Động & Phiêu Lưu', 'icon' => '🎯', 'color' => '#e11d48'],
        '.io'          => ['slug' => 'action', 'name' => 'Hành Động & Phiêu Lưu', 'icon' => '🎯', 'color' => '#e11d48'],
        'clicker'      => ['slug' => 'casual', 'name' => 'Giải Trí Nhanh', 'icon' => '🎲', 'color' => '#db2777'],
    ];

    /**
     * Import a bulk batch of games from HTML5 Game Feed APIs (GamePix + GameMonetize).
     *
     * @return array{success: bool, imported_count: int, updated_count: int, message: string}
     */
    public function importGames(int $amount = 200, ?string $filterCategory = null): array
    {
        $targetAmount = max(5, $amount);
        $totalImported = 0;
        $totalUpdated  = 0;

        try {
            // ── Source 1: GameMonetize HTML5 Global Catalog (Fast & Direct) ────
            $gmResponse = Http::timeout(25)->get(self::GAMEMONETIZE_ENDPOINT, [
                'format' => 'json',
                'amount' => min(300, $targetAmount),
            ]);

            if ($gmResponse->successful()) {
                $gmData = $gmResponse->json();
                if (is_array($gmData)) {
                    foreach ($gmData as $item) {
                        if ($totalImported + $totalUpdated >= $targetAmount) {
                            break;
                        }

                        $title = trim((string) ($item['title'] ?? ''));
                        $url   = (string) ($item['url'] ?? '');
                        $thumb = (string) ($item['thumb'] ?? '');

                        if (empty($title) || empty($url)) {
                            continue;
                        }

                        $slug = Str::slug($title);
                        if (empty($slug)) {
                            continue;
                        }

                        $categoryEntity = $this->resolveCategory((string) ($item['category'] ?? 'Casual'));
                        $rawDesc = strip_tags((string) ($item['description'] ?? ''));
                        $summary = Str::limit($rawDesc, 280, '...');
                        $instructions = trim(strip_tags((string) ($item['instructions'] ?? '')));

                        $descMarkdown = "## Giới Thiệu Trò Chơi\n\n{$rawDesc}\n\n";
                        if (! empty($instructions)) {
                            $descMarkdown .= "## Hướng Dẫn & Phím Điều Khiển\n\n- {$instructions}\n\n";
                        }
                        $descMarkdown .= "## Tính Năng Nổi Bật\n\n- Chơi trực tiếp trên trình duyệt máy tính và điện thoại.\n- Đồ họa HTML5 mượt mà, không giật lag.";

                        $existing = Game::where('slug', $slug)->first();

                        $gameData = [
                            'category_id'          => $categoryEntity->id,
                            'name'                 => $title,
                            'summary'              => $summary,
                            'description_markdown' => $descMarkdown,
                            'thumbnail_url'        => ! empty($thumb) ? $thumb : null,
                            'engine_path'          => $url,
                            'difficulty'           => 'medium',
                            'controls_hint'        => ! empty($instructions) ? Str::limit($instructions, 120) : 'Chuột / WASD',
                            'is_active'            => true,
                            'meta_title'           => "{$title} — Chơi Game {$title} Online Miễn Phí | TechHub Games",
                            'meta_description'     => Str::limit("Chơi game {$title} online miễn phí trên trình duyệt. {$summary}", 160),
                        ];

                        if ($existing) {
                            $existing->update($gameData);
                            $totalUpdated++;
                        } else {
                            $gameData['slug']        = $slug;
                            $gameData['play_count']  = rand(350, 4800);
                            $gameData['is_featured'] = rand(1, 25) === 1;
                            Game::create($gameData);
                            $totalImported++;
                        }
                    }
                }
            }

            // ── Source 2: GamePix Global Catalog API (Supplement) ──────────────
            if ($totalImported + $totalUpdated < $targetAmount) {
                $gpParams = [
                    'sid'   => '1',
                    'limit' => min(500, $targetAmount - ($totalImported + $totalUpdated)),
                    'order' => 'q',
                ];

                $gpResponse = Http::timeout(25)
                    ->withHeaders(['User-Agent' => 'TechHub-Game-Sync/2.0'])
                    ->get(self::GAMEPIX_ENDPOINT, $gpParams);

                if ($gpResponse->successful()) {
                    $gpData = $gpResponse->json('data');
                    if (is_array($gpData)) {
                        foreach ($gpData as $item) {
                            if ($totalImported + $totalUpdated >= $targetAmount) {
                                break;
                            }

                            $title = trim((string) ($item['title'] ?? ''));
                            $url   = (string) ($item['url'] ?? '');
                            $thumb = (string) ($item['thumbnailUrl'] ?? '');

                            if (empty($title) || empty($url)) {
                                continue;
                            }

                            $slug = Str::slug($title);
                            if (empty($slug)) {
                                continue;
                            }

                            $categoryName = (string) ($item['category'] ?? 'Arcade');
                            $categoryEntity = $this->resolveCategory($categoryName);

                            $rawDesc = trim((string) ($item['description'] ?? $item['desc_en'] ?? ''));
                            $summary = Str::limit($rawDesc ?: "Trò chơi {$title} đồ họa HTML5 cực hay, mượt mà trên mọi thiết bị.", 280, '...');

                            $descMarkdown = "## Giới Thiệu Trò Chơi\n\n" . ($rawDesc ?: $summary) . "\n\n";
                            $descMarkdown .= "## Hướng Dẫn Điều Khiển\n\n- Sử dụng chuột hoặc chạm màn hình cảm ứng để điều khiển.\n- Hỗ trợ đầy đủ phím mũi tên / WASD trên máy tính.\n\n";
                            $descMarkdown .= "## Tính Năng Nổi Bật\n\n- Đồ họa HTML5 tối ưu hóa mượt mà 60 FPS.\n- Chơi tức thì trên trình duyệt máy tính, iPhone, Android.\n- Không cần cài đặt, không quảng cáo chen ngang.";

                            $difficulty = match (mb_strtolower($categoryName)) {
                                'strategy', 'puzzles', 'action' => 'medium',
                                'adventure', 'shooting'        => 'hard',
                                default                        => 'easy',
                            };

                            $existing = Game::where('slug', $slug)->first();

                            $gameData = [
                                'category_id'          => $categoryEntity->id,
                                'name'                 => $title,
                                'summary'              => $summary,
                                'description_markdown' => $descMarkdown,
                                'thumbnail_url'        => ! empty($thumb) ? $thumb : null,
                                'engine_path'          => $url,
                                'difficulty'           => $difficulty,
                                'controls_hint'        => 'Chuột / Cảm Ứng / Phím Mũi Tên',
                                'is_active'            => true,
                                'meta_title'           => "{$title} — Chơi Game {$title} Online Miễn Phí | TechHub Games",
                                'meta_description'     => Str::limit("Chơi game {$title} online miễn phí trên trình duyệt. {$summary}", 160),
                            ];

                            if ($existing) {
                                $existing->update($gameData);
                                $totalUpdated++;
                            } else {
                                $gameData['slug']        = $slug;
                                $gameData['play_count']  = rand(350, 5200);
                                $gameData['is_featured'] = rand(1, 20) === 1;
                                Game::create($gameData);
                                $totalImported++;
                            }
                        }
                    }
                }
            }

            return [
                'success'        => true,
                'imported_count' => $totalImported,
                'updated_count'  => $totalUpdated,
                'message'        => "Đã đồng bộ thành công! Thêm mới: {$totalImported} game, Cập nhật: {$totalUpdated} game.",
            ];
        } catch (\Throwable $e) {
            Log::error('GameFeedImportService error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return [
                'success'        => false,
                'imported_count' => $totalImported,
                'updated_count'  => $totalUpdated,
                'message'        => 'Lỗi xử lý import game: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Resolve existing GameCategory or create a new one matching the feed category.
     */
    private function resolveCategory(string $rawCategory): GameCategory
    {
        $key = mb_strtolower(trim($rawCategory));

        $mapped = self::CATEGORY_MAP[$key] ?? [
            'slug'  => Str::slug($rawCategory) ?: 'casual',
            'name'  => ucfirst($rawCategory),
            'icon'  => '🎮',
            'color' => '#6366f1',
        ];

        return GameCategory::firstOrCreate(
            ['slug' => $mapped['slug']],
            [
                'name'        => $mapped['name'],
                'description' => 'Tuyển tập trò chơi thể loại ' . $mapped['name'] . ' hay nhất',
                'icon'        => $mapped['icon'],
                'color'       => $mapped['color'],
                'sort_order'  => 10,
                'is_active'   => true,
            ]
        );
    }
}
