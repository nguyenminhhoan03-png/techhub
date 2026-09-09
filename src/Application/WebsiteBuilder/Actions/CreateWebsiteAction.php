<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Actions;

use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Entities\WebsiteSeo;
use Domain\WebsiteBuilder\Entities\WebsiteSetting;
use Domain\WebsiteBuilder\Enums\PageStatus;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateWebsiteAction
{
    /**
     * Khởi tạo một website mới kèm trang chủ và cấu hình mặc định.
     */
    public function execute(int $userId, string $name, ?string $subdomain = null): Website
    {
        return DB::transaction(function () use ($userId, $name, $subdomain): Website {
            $cleanSubdomain = $this->sanitizeSubdomain($subdomain ?: Str::slug($name));

            // Đảm bảo subdomain là duy nhất
            $uniqueSubdomain = $this->ensureUniqueSubdomain($cleanSubdomain);

            // 1. Tạo Website
            $website = Website::create([
                'user_id' => $userId,
                'name' => $name,
                'subdomain' => $uniqueSubdomain,
                'status' => WebsiteStatus::DRAFT,
            ]);

            // 2. Tạo Cấu hình mặc định
            WebsiteSetting::create([
                'website_id' => $website->id,
                'remove_branding' => false,
            ]);

            // 3. Tạo SEO mặc định
            WebsiteSeo::create([
                'website_id' => $website->id,
                'meta_title' => "{$name} - Website chính thức",
                'meta_description' => "Chào mừng bạn đến với trang web {$name}. Được thiết kế bởi muabanwebsite.io.vn.",
                'sitemap_enabled' => true,
            ]);

            // 4. Tạo Default Home Page với Template AST mở đầu
            $defaultAst = $this->getDefaultTemplateAst($name);

            Page::create([
                'website_id' => $website->id,
                'title' => 'Trang chủ',
                'slug' => 'home',
                'is_home' => true,
                'draft_content' => $defaultAst,
                'version_number' => 1,
                'status' => PageStatus::DRAFT,
            ]);

            return $website->load(['pages', 'settings', 'seo']);
        });
    }

    private function sanitizeSubdomain(string $subdomain): string
    {
        $clean = mb_strtolower(preg_replace('/[^a-z0-9\-]/', '', $subdomain) ?? '');
        return trim($clean, '-') ?: 'site-' . bin2hex(random_bytes(3));
    }

    private function ensureUniqueSubdomain(string $subdomain): string
    {
        $original = $subdomain;
        $counter = 1;

        while (Website::where('subdomain', $subdomain)->exists()) {
            $subdomain = "{$original}-{$counter}";
            $counter++;
        }

        return $subdomain;
    }

    private function getDefaultTemplateAst(string $siteName): array
    {
        return [
            'version' => 1,
            'components' => [
                [
                    'id' => 'sec-hero-' . bin2hex(random_bytes(3)),
                    'type' => 'section',
                    'props' => ['anchor_id' => 'hero'],
                    'styles' => [
                        'desktop' => [
                            'padding-top' => '100px',
                            'padding-bottom' => '100px',
                            'background-color' => '#0f172a',
                            'color' => '#ffffff',
                            'text-align' => 'center',
                        ],
                        'mobile' => [
                            'padding-top' => '60px',
                            'padding-bottom' => '60px',
                        ],
                    ],
                    'children' => [
                        [
                            'id' => 'cnt-hero-' . bin2hex(random_bytes(3)),
                            'type' => 'container',
                            'props' => ['fluid' => false],
                            'styles' => [
                                'desktop' => ['max-width' => '960px'],
                            ],
                            'children' => [
                                [
                                    'id' => 'heading-hero-' . bin2hex(random_bytes(3)),
                                    'type' => 'heading',
                                    'props' => [
                                        'tag' => 'h1',
                                        'content' => "Chào mừng bạn đến với {$siteName}",
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'font-size' => '52px',
                                            'font-weight' => '800',
                                            'margin-bottom' => '20px',
                                            'line-height' => '1.2',
                                        ],
                                        'mobile' => [
                                            'font-size' => '32px',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                                [
                                    'id' => 'txt-hero-' . bin2hex(random_bytes(3)),
                                    'type' => 'text',
                                    'props' => [
                                        'content' => 'Trang web được xây dựng với công nghệ siêu tốc độ, chuẩn SEO 100% và giao diện responsive thông minh.',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'font-size' => '20px',
                                            'color' => '#94a3b8',
                                            'margin-bottom' => '36px',
                                            'line-height' => '1.6',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                                [
                                    'id' => 'btn-hero-' . bin2hex(random_bytes(3)),
                                    'type' => 'button',
                                    'props' => [
                                        'text' => 'Bắt Đầu Khám Phá',
                                        'href' => '#features',
                                        'variant' => 'primary',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'background-color' => '#2563eb',
                                            'color' => '#ffffff',
                                            'padding' => '14px 32px',
                                            'border-radius' => '8px',
                                            'font-weight' => '600',
                                            'font-size' => '16px',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
