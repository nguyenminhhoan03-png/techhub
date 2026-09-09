<?php

declare(strict_types=1);

namespace Tests\Unit;

use Application\WebsiteBuilder\Services\Compiler\StaticSiteCompilerService;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Entities\WebsiteSeo;
use Domain\WebsiteBuilder\Entities\WebsiteSetting;
use Domain\WebsiteBuilder\Enums\PageStatus;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use PHPUnit\Framework\TestCase;

class StaticSiteCompilerServiceTest extends TestCase
{
    public function test_it_compiles_ast_into_semantic_html_and_scoped_css(): void
    {
        $compiler = new StaticSiteCompilerService();

        $website = new Website();
        $website->id = 1;
        $website->name = 'Tech Brand Việt Nam';
        $website->subdomain = 'techbrand';
        $website->status = WebsiteStatus::PUBLISHED;

        $seo = new WebsiteSeo();
        $seo->meta_title = 'Tech Brand - Giải Pháp Phần Mềm';
        $seo->meta_description = 'Mô tả chuẩn SEO cho Tech Brand';
        $website->setRelation('seo', $seo);

        $settings = new WebsiteSetting();
        $settings->remove_branding = false;
        $website->setRelation('settings', $settings);

        $page = new Page();
        $page->id = 10;
        $page->title = 'Trang Chủ';
        $page->slug = 'home';
        $page->is_home = true;
        $page->status = PageStatus::PUBLISHED;

        $ast = [
            'version' => 1,
            'components' => [
                [
                    'id' => 'hero-sec',
                    'type' => 'section',
                    'props' => ['anchor_id' => 'hero'],
                    'styles' => [
                        'desktop' => ['padding-top' => '80px', 'background-color' => '#1e293b'],
                        'mobile' => ['padding-top' => '40px'],
                    ],
                    'children' => [
                        [
                            'id' => 'hero-heading',
                            'type' => 'heading',
                            'props' => ['tag' => 'h1', 'content' => 'Chào Mừng Đến Tech Brand'],
                            'styles' => [
                                'desktop' => ['font-size' => '48px'],
                                'mobile' => ['font-size' => '28px'],
                            ],
                            'children' => [],
                        ],
                        [
                            'id' => 'hero-img',
                            'type' => 'image',
                            'props' => [
                                'src' => 'https://muabanwebsite.io.vn/images/sample.jpg',
                                'alt' => 'Ảnh mẫu sản phẩm',
                                'width' => 800,
                                'height' => 600,
                            ],
                            'styles' => [],
                            'children' => [],
                        ],
                    ],
                ],
            ],
        ];

        $result = $compiler->compilePage($website, $page, $ast);

        $html = $result['html'];
        $css = $result['css'];

        // 1. Kiểm tra Semantic HTML
        $this->assertStringContainsString('<section id="hero" data-component-id="hero-sec" class="builder-section">', $html);
        $this->assertStringContainsString('<h1 id="hero-heading" class="builder-heading">Chào Mừng Đến Tech Brand</h1>', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
        $this->assertStringContainsString('decoding="async"', $html);
        $this->assertStringContainsString('alt="Ảnh mẫu sản phẩm"', $html);

        // 2. Kiểm tra SEO tags
        $this->assertStringContainsString('<title>Tech Brand - Giải Pháp Phần Mềm</title>', $html);
        $this->assertStringContainsString('<meta name="description" content="Mô tả chuẩn SEO cho Tech Brand">', $html);
        $this->assertStringContainsString('<link rel="canonical" href="https://techbrand.muabanwebsite.io.vn">', $html);
        $this->assertStringContainsString('property="og:title" content="Tech Brand - Giải Pháp Phần Mềm"', $html);
        $this->assertStringContainsString('property="twitter:card"', $html);
        $this->assertStringContainsString('application/ld+json', $html);

        // 3. Kiểm tra CSS Scoped & Responsive
        $this->assertStringContainsString('#hero-sec{', $css);
        $this->assertStringContainsString('@media (max-width:767px)', $css);
        $this->assertStringContainsString('padding-top:80px', $css);

        // 4. Kiểm tra Watermark
        $this->assertStringContainsString('muabanwebsite.io.vn', $html);
    }
}
