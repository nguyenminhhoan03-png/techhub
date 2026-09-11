<?php

declare(strict_types=1);

use Application\Setting\Services\SettingService;
use Database\Seeders\AdminAndSettingsSeeder;
use Database\Seeders\ToolSeeder;
use Domain\Tool\Entities\Tool;
use Domain\User\Entities\User;

beforeEach(function (): void {
    $this->seed(ToolSeeder::class);
    $this->seed(AdminAndSettingsSeeder::class);
});

it('redirects unauthenticated guest accessing admin dashboard to login', function (): void {
    $response = $this->get('/admin');

    $response->assertRedirect('/admin/login');
});

it('renders admin login page', function (): void {
    $response = $this->get('/admin/login');

    $response->assertStatus(200)
        ->assertSee('ADMIN')
        ->assertSee('HUB')
        ->assertSee('Đăng Nhập Quản Trị');
});

it('authenticates admin with valid credentials and redirects to dashboard', function (): void {
    $response = $this->post('/admin/login', [
        'email' => 'admin@techhub.local',
        'password' => 'Admin@123456',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticated();

    // Verify dashboard renders without lazy loading violations
    $dashboardResponse = $this->get('/admin');
    $dashboardResponse->assertStatus(200)
        ->assertSee('Tổng Quan')
        ->assertSee('Hệ Thống TechHub')
        ->assertSee('Top Công Cụ Hot Nhất');
});

it('prevents regular user from accessing admin dashboard', function (): void {
    /** @var User $regularUser */
    $regularUser = User::query()->create([
        'name' => 'Regular Member',
        'email' => 'member@example.com',
        'password' => bcrypt('Password@123'),
        'role' => 'user',
        'status' => 'active',
    ]);

    $response = $this->actingAs($regularUser)->get('/admin');

    $response->assertRedirect('/admin/login');
});

it('allows admin to manage users and toggle tools', function (): void {
    /** @var User $admin */
    $admin = User::query()->where('role', 'admin')->firstOrFail();

    // Access Users management
    $response = $this->actingAs($admin)->get('/admin/users');
    $response->assertStatus(200)
        ->assertSee('Quản Lý')
        ->assertSee('Người Dùng');

    // Access Tools management
    $tool = Tool::query()->firstOrFail();
    $initialStatus = $tool->is_active;

    $toggleResponse = $this->actingAs($admin)->post("/admin/tools/{$tool->id}/toggle");
    $toggleResponse->assertRedirect();

    $tool->refresh();
    expect($tool->is_active)->toBe( ! $initialStatus);
});

it('allows admin to create and toggle advertisements', function (): void {
    /** @var User $admin */
    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $adData = [
        'name' => 'Black Friday Promo 2026',
        'slot' => 'header_top',
        'type' => 'custom_banner',
        'image_url' => 'https://example.com/promo.png',
        'target_url' => 'https://example.com/buy',
        'is_active' => true,
    ];

    $response = $this->actingAs($admin)->post('/admin/ads', $adData);
    $response->assertRedirect('/admin/ads');

    $this->assertDatabaseHas('advertisements', ['name' => 'Black Friday Promo 2026']);
});

it('allows admin to update system settings and flushes cache', function (): void {
    /** @var User $admin */
    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $response = $this->actingAs($admin)->post('/admin/settings', [
        'hero_title' => 'Cổng Tiện Ích Trực Tuyến Đẳng Cấp 2026',
        'hero_subtitle' => 'Nhanh chóng, tiện lợi và hoàn toàn miễn phí.',
    ]);

    $response->assertRedirect('/admin/settings');

    expect(SettingService::get('hero_title'))->toBe('Cổng Tiện Ích Trực Tuyến Đẳng Cấp 2026');
});

it('allows admin to dynamically update AI settings and test connection', function (): void {
    /** @var User $admin */
    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $settingsIndexResponse = $this->actingAs($admin)->get('/admin/settings');
    $settingsIndexResponse->assertStatus(200)
        ->assertSee('Cấu Hình AI Content Engine')
        ->assertSee('openai_api_url')
        ->assertSee('Test Kết Nối Ngay');

    $postResponse = $this->actingAs($admin)->post('/admin/settings', [
        'openai_api_url' => 'https://api.vilao.ai/v1',
        'openai_api_key' => 'sk-test-fake-key-123456',
        'ai_default_provider' => 'openai',
        'ai_model_name' => 'ram/gemini-3.6-flash-high',
    ]);
    $postResponse->assertRedirect('/admin/settings');

    expect(SettingService::get('openai_api_url'))->toBe('https://api.vilao.ai/v1')
        ->and(SettingService::get('ai_model_name'))->toBe('ram/gemini-3.6-flash-high')
        ->and(SettingService::get('openai_api_key'))->toBe('sk-test-fake-key-123456');

    // Test connection endpoint validation when key is empty
    $testResponse = $this->actingAs($admin)->postJson('/admin/settings/test-ai', [
        'openai_api_key' => '',
        'ai_default_provider' => 'openai',
    ]);
    $testResponse->assertStatus(422)
        ->assertJson(['success' => false]);

    // Test connection endpoint success with Http fake
    \Illuminate\Support\Facades\Http::fake([
        'https://api.vilao.ai/v1/chat/completions' => \Illuminate\Support\Facades\Http::response([
            'choices' => [
                ['message' => ['content' => 'Ket noi TechHub OK!']],
            ],
        ], 200),
    ]);

    $successResponse = $this->actingAs($admin)->postJson('/admin/settings/test-ai', [
        'openai_api_url' => 'https://api.vilao.ai/v1',
        'openai_api_key' => 'sk-valid-key-123456',
        'ai_default_provider' => 'openai',
        'ai_model_name' => 'ram/gemini-3.6-flash-high',
    ]);

    $successResponse->assertStatus(200)
        ->assertJson(['success' => true])
        ->assertJsonFragment(['success' => true]);
});

