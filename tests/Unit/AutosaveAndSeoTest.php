<?php

declare(strict_types=1);

namespace Tests\Unit;

use Application\WebsiteBuilder\Exceptions\OptimisticLockConflictException;
use Application\WebsiteBuilder\Services\Compiler\StaticSiteCompilerService;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutosaveAndSeoTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_generates_valid_sitemap_and_robots_txt(): void
    {
        $compiler = new StaticSiteCompilerService();

        $website = new Website();
        $website->id = 1;
        $website->name = 'TechHub Store';
        $website->subdomain = 'techhub-store';

        $homePage = new Page();
        $homePage->id = 1;
        $homePage->title = 'Trang Chủ';
        $homePage->slug = 'home';
        $homePage->is_home = true;
        $homePage->updated_at = now();

        $aboutPage = new Page();
        $aboutPage->id = 2;
        $aboutPage->title = 'Về Chúng Tôi';
        $aboutPage->slug = 'about-us';
        $aboutPage->is_home = false;
        $aboutPage->updated_at = now();

        $website->setRelation('pages', collect([$homePage, $aboutPage]));

        // 1. Kiểm tra Sitemap XML
        $sitemap = $compiler->generateSitemapXml($website);
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $sitemap);
        $this->assertStringContainsString('<loc>https://techhub-store.muabanwebsite.io.vn</loc>', $sitemap);
        $this->assertStringContainsString('<loc>https://techhub-store.muabanwebsite.io.vn/about-us</loc>', $sitemap);
        $this->assertStringContainsString('<priority>1.0</priority>', $sitemap);
        $this->assertStringContainsString('<priority>0.8</priority>', $sitemap);

        // 2. Kiểm tra Robots.txt
        $robots = $compiler->generateRobotsTxt($website);
        $this->assertStringContainsString('User-agent: *', $robots);
        $this->assertStringContainsString('Allow: /', $robots);
        $this->assertStringContainsString('Sitemap: https://techhub-store.muabanwebsite.io.vn/sitemap.xml', $robots);
    }

    public function test_optimistic_lock_exception_stores_server_state(): void
    {
        $exception = new OptimisticLockConflictException(
            currentVersionNumber: 15,
            latestContent: ['version' => 1, 'components' => []],
            message: 'Conflict detected',
        );

        $this->assertSame(409, $exception->getCode());
        $this->assertSame(15, $exception->currentVersionNumber);
        $this->assertIsArray($exception->latestContent);
        $this->assertSame('Conflict detected', $exception->getMessage());
    }

    public function test_create_new_page_via_api_stores_draft(): void
    {
        $user = \Domain\User\Entities\User::create([
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'name' => 'Test User',
            'email' => 'test-' . time() . '@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'user',
            'status' => \Domain\User\Enums\UserStatus::Active,
        ]);

        $website = Website::create([
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'user_id' => $user->id,
            'name' => 'Demo Site',
            'subdomain' => 'demo-site-' . time(),
            'status' => \Domain\WebsiteBuilder\Enums\WebsiteStatus::DRAFT,
        ]);

        $response = $this->actingAs($user)->postJson('/api/builder/pages', [
            'website_id' => $website->id,
            'title' => 'Trang Dịch Vụ Mới',
            'slug' => 'dich-vu-moi',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.slug', 'dich-vu-moi');
        $response->assertJsonPath('data.version_number', 1);

        $this->assertDatabaseHas('pages', [
            'website_id' => $website->id,
            'slug' => 'dich-vu-moi',
        ]);
    }
}
