<?php

declare(strict_types=1);

namespace Database\Seeders;

use Application\WebsiteBuilder\Actions\PublishWebsiteAction;
use Domain\User\Entities\User;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Entities\WebsiteDomain;
use Domain\WebsiteBuilder\Entities\WebsiteSeo;
use Domain\WebsiteBuilder\Entities\WebsiteSetting;
use Domain\WebsiteBuilder\Enums\DomainStatus;
use Domain\WebsiteBuilder\Enums\PageStatus;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WebsiteBuilderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        // Tránh seed trùng lặp
        if (Website::where('subdomain', 'demo-builder')->exists()) {
            return;
        }

        // 1. Tạo Website mẫu
        $website = Website::create([
            'ulid' => (string) Str::ulid(),
            'user_id' => $user->id,
            'name' => 'Demo SaaS Platform - muabanwebsite.io.vn',
            'subdomain' => 'demo-builder',
            'status' => WebsiteStatus::DRAFT,
        ]);

        // 2. Cấu hình Settings
        WebsiteSetting::create([
            'website_id' => $website->id,
            'favicon_url' => 'https://muabanwebsite.io.vn/favicon.ico',
            'custom_css' => '.builder-btn:hover { transform: translateY(-2px); transition: all 0.2s ease; }',
            'remove_branding' => false,
        ]);

        // 3. Cấu hình SEO
        WebsiteSeo::create([
            'website_id' => $website->id,
            'meta_title' => 'Demo SaaS Website Builder - Nền Tảng Kéo Thả Đỉnh Cao',
            'meta_description' => 'Trang web mẫu được dựng hoàn toàn tự động bằng Website Builder của muabanwebsite.io.vn. Siêu nhẹ, siêu nhanh, chuẩn SEO 100%.',
            'meta_keywords' => 'website builder, no code, landing page, mua ban website',
            'og_image_url' => 'https://muabanwebsite.io.vn/images/og-preview.jpg',
            'sitemap_enabled' => true,
        ]);

        // 4. Tạo Custom Domain mẫu
        WebsiteDomain::create([
            'website_id' => $website->id,
            'domain' => 'demo.muabanwebsite.io.vn',
            'verification_token' => 'mbw_token_' . bin2hex(random_bytes(8)),
            'status' => DomainStatus::VERIFIED,
            'ssl_status' => 'issued',
            'verified_at' => now(),
        ]);

        // 5. Trang chủ (Home Page) với AST phong phú chuẩn SEO
        $homeAst = [
            'version' => 1,
            'components' => [
                [
                    'id' => 'sec-hero-main',
                    'type' => 'section',
                    'props' => ['anchor_id' => 'hero'],
                    'styles' => [
                        'desktop' => [
                            'padding-top' => '110px',
                            'padding-bottom' => '100px',
                            'background-color' => '#090d16',
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
                            'id' => 'cnt-hero-inner',
                            'type' => 'container',
                            'props' => ['fluid' => false],
                            'styles' => [
                                'desktop' => ['max-width' => '960px'],
                            ],
                            'children' => [
                                [
                                    'id' => 'heading-hero-title',
                                    'type' => 'heading',
                                    'props' => [
                                        'tag' => 'h1',
                                        'content' => 'Xây Dựng Website Đẳng Cấp Trong 5 Phút',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'font-size' => '54px',
                                            'font-weight' => '800',
                                            'line-height' => '1.15',
                                            'margin-bottom' => '24px',
                                            'color' => '#f8fafc',
                                        ],
                                        'mobile' => [
                                            'font-size' => '32px',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                                [
                                    'id' => 'text-hero-desc',
                                    'type' => 'text',
                                    'props' => [
                                        'content' => 'Nền tảng No-Code Website Builder dành riêng cho Developer & Doanh nghiệp Việt Nam. Xuất bản tĩnh tức thì lên AWS S3 và Cloudflare Edge CDN với tốc độ tải trang dưới 50ms.',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'font-size' => '20px',
                                            'color' => '#94a3b8',
                                            'line-height' => '1.6',
                                            'margin-bottom' => '40px',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                                [
                                    'id' => 'btn-hero-cta',
                                    'type' => 'button',
                                    'props' => [
                                        'text' => 'Bắt Đầu Tạo Website Miễn Phí',
                                        'href' => '#features',
                                        'target' => '_self',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'background-color' => '#2563eb',
                                            'color' => '#ffffff',
                                            'padding' => '16px 36px',
                                            'border-radius' => '10px',
                                            'font-size' => '17px',
                                            'font-weight' => '700',
                                        ],
                                    ],
                                    'children' => [],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'sec-features-grid',
                    'type' => 'section',
                    'props' => ['anchor_id' => 'features'],
                    'styles' => [
                        'desktop' => [
                            'padding-top' => '90px',
                            'padding-bottom' => '90px',
                            'background-color' => '#0f172a',
                            'color' => '#f8fafc',
                        ],
                    ],
                    'children' => [
                        [
                            'id' => 'cnt-features',
                            'type' => 'container',
                            'props' => ['fluid' => false],
                            'styles' => [
                                'desktop' => ['max-width' => '1200px'],
                            ],
                            'children' => [
                                [
                                    'id' => 'heading-features-title',
                                    'type' => 'heading',
                                    'props' => [
                                        'tag' => 'h2',
                                        'content' => 'Tại Sao Chọn muabanwebsite.io.vn?',
                                    ],
                                    'styles' => [
                                        'desktop' => [
                                            'font-size' => '36px',
                                            'text-align' => 'center',
                                            'margin-bottom' => '48px',
                                            'font-weight' => '700',
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

        $homePage = Page::create([
            'ulid' => (string) Str::ulid(),
            'website_id' => $website->id,
            'title' => 'Trang Chủ',
            'slug' => 'home',
            'is_home' => true,
            'draft_content' => $homeAst,
            'version_number' => 1,
            'status' => PageStatus::DRAFT,
        ]);

        // 6. Trang Giới thiệu (About Us)
        $aboutAst = [
            'version' => 1,
            'components' => [
                [
                    'id' => 'sec-about-header',
                    'type' => 'section',
                    'props' => ['anchor_id' => 'about'],
                    'styles' => [
                        'desktop' => [
                            'padding-top' => '80px',
                            'padding-bottom' => '80px',
                            'background-color' => '#0f172a',
                            'color' => '#ffffff',
                            'text-align' => 'center',
                        ],
                    ],
                    'children' => [
                        [
                            'id' => 'cnt-about',
                            'type' => 'container',
                            'props' => ['fluid' => false],
                            'styles' => ['desktop' => ['max-width' => '800px']],
                            'children' => [
                                [
                                    'id' => 'heading-about-title',
                                    'type' => 'heading',
                                    'props' => [
                                        'tag' => 'h1',
                                        'content' => 'Về Chúng Tôi',
                                    ],
                                    'styles' => ['desktop' => ['font-size' => '44px', 'margin-bottom' => '20px']],
                                    'children' => [],
                                ],
                                [
                                    'id' => 'txt-about-content',
                                    'type' => 'text',
                                    'props' => [
                                        'content' => 'muabanwebsite.io.vn là hệ sinh thái Marketplace kết hợp Website Builder và Developer Tools giúp doanh nghiệp Việt Nam số hóa thần tốc.',
                                    ],
                                    'styles' => ['desktop' => ['font-size' => '18px', 'color' => '#cbd5e1', 'line-height' => '1.7']],
                                    'children' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        Page::create([
            'ulid' => (string) Str::ulid(),
            'website_id' => $website->id,
            'title' => 'Giới Thiệu',
            'slug' => 'about',
            'is_home' => false,
            'draft_content' => $aboutAst,
            'version_number' => 1,
            'status' => PageStatus::DRAFT,
        ]);

        // 7. Tự động chạy Publish website để sinh file tĩnh SSG thực tế trên storage
        /** @var PublishWebsiteAction $publishAction */
        $publishAction = app(PublishWebsiteAction::class);
        $publishAction->execute($website, $user->id);
    }
}
