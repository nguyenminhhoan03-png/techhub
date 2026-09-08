<?php

declare(strict_types=1);

use Database\Seeders\AdminAndSettingsSeeder;
use Database\Seeders\DigitalDealSeeder;
use Domain\Deal\Entities\DigitalDeal;
use Domain\User\Entities\User;

beforeEach(function (): void {
    $this->seed(AdminAndSettingsSeeder::class);
    $this->seed(DigitalDealSeeder::class);
});

it('renders public deals store catalog page with deals', function (): void {
    $response = $this->get('/deals');

    $response->assertStatus(200)
        ->assertSee('Cửa Hàng Tài Khoản AI')
        ->assertSee('Gemini Pro + 5TB + Antigravity')
        ->assertSee('50.000');
});

it('renders single digital deal show page with variants and contact buttons', function (): void {
    $response = $this->get('/deals/gemini-pro-5tb-antigravity-1-nam');

    $response->assertStatus(200)
        ->assertSee('Gemini Pro + 5TB + Antigravity')
        ->assertSee('1 slot mail khách')
        ->assertSee('Cấp acc chính chủ fam')
        ->assertSee('0866655803')
        ->assertSee('https://t.me/hoannm')
        ->assertSee('Bảo vệ bởi Escrow');
});

it('allows admin to manage deals in admin panel', function (): void {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->get('/admin/deals');
    $response->assertStatus(200)
        ->assertSee('Quản Lý Tài Khoản AI & Deals')
        ->assertSee('gemini-pro-5tb-antigravity-1-nam');
});

it('allows admin to create a new deal', function (): void {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->post('/admin/deals', [
        'name'                => 'GitHub Copilot Pro 1 Năm',
        'category'            => 'Developer Tools',
        'price'               => 199000,
        'original_price'      => 1200000,
        'discount_percentage' => 83,
        'stock_status'        => 'in_stock',
        'zalo_contact'        => '0866655803',
        'telegram_contact'    => 'https://t.me/hoannm',
        'is_active'           => 1,
        'is_featured'         => 1,
        'variants'            => [
            ['name' => '1 Slot Cá Nhân 12 Tháng', 'price' => 199000],
        ],
    ]);

    $response->assertRedirect('/admin/deals');
    $this->assertDatabaseHas('digital_deals', [
        'slug' => 'github-copilot-pro-1-nam',
        'price' => 199000,
    ]);
});

it('allows admin to toggle deal active status', function (): void {
    $admin = User::where('role', 'admin')->first();
    $deal = DigitalDeal::first();

    $initialStatus = $deal->is_active;

    $response = $this->actingAs($admin)->post("/admin/deals/{$deal->id}/toggle");
    $response->assertRedirect();

    $this->assertDatabaseHas('digital_deals', [
        'id' => $deal->id,
        'is_active' => ! $initialStatus,
    ]);
});
