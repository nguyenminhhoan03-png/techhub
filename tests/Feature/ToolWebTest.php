<?php

declare(strict_types=1);

use Database\Seeders\ToolSeeder;

beforeEach(function (): void {
    $this->seed(ToolSeeder::class);
});

it('renders home page in Vietnamese default with SEO tags and tools', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('TECH')
        ->assertSee('HUB')
        ->assertSee('Định Dạng & Kiểm Tra Cú Pháp JSON')
        ->assertSee('Tính Lãi Suất Vay & Trả Góp Ngân Hàng');
});

it('switches locale to English successfully', function (): void {
    $response = $this->get('/lang/en');

    $response->assertRedirect();

    $response = $this->withSession(['locale' => 'en'])->get('/');
    $response->assertStatus(200)
        ->assertSee('Tools Hub');
});

it('renders tools catalog page', function (): void {
    $response = $this->get('/tools');

    $response->assertStatus(200)
        ->assertSee('Tất Cả Công Cụ')
        ->assertSee('Công cụ Lập trình');
});

it('renders interactive tool workspace for JSON formatter', function (): void {
    $response = $this->get('/tools/json-formatter');

    $response->assertStatus(200)
        ->assertSee('Định Dạng & Kiểm Tra Cú Pháp JSON')
        ->assertSee('Thực Thi Ngay')
        ->assertSee('Beautify (Làm đẹp)');
});

it('renders interactive tool workspace for Loan Calculator', function (): void {
    $response = $this->get('/tools/loan-calculator');

    $response->assertStatus(200)
        ->assertSee('Tính Lãi Suất Vay & Trả Góp Ngân Hàng')
        ->assertSee('Số tiền vay')
        ->assertSee('Tính Số Tiền Trả Hàng Tháng');
});

it('generates dynamic xml sitemap', function (): void {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/xml; charset=utf-8')
        ->assertSee('<urlset', false)
        ->assertSee('/tools/json-formatter', false)
        ->assertSee('/tools/loan-calculator', false);
});
