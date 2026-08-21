<?php

declare(strict_types=1);

use Database\Seeders\ToolSeeder;

beforeEach(function (): void {
    $this->seed(ToolSeeder::class);
});

it('lists all active tool categories', function (): void {
    $response = $this->getJson('/api/tools/categories');

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'slug', 'name', 'description', 'icon', 'sort_order', 'is_active'],
        ]);

    expect($response->json())->not->toBeEmpty();
});

it('lists all active tools', function (): void {
    $response = $this->getJson('/api/tools');

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'slug', 'name', 'summary', 'engine_type', 'is_active', 'execution_count'],
        ]);

    expect(count($response->json()))->toBeGreaterThanOrEqual(10);
});

it('filters tools by category slug', function (): void {
    $response = $this->getJson('/api/tools?category=developer');

    $response->assertStatus(200);
    $tools = $response->json();

    foreach ($tools as $tool) {
        expect($tool['category']['slug'])->toBe('developer');
    }
});

it('retrieves single tool details by slug', function (): void {
    $response = $this->getJson('/api/tools/json-formatter');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'slug' => 'json-formatter',
                'name' => 'Định Dạng & Kiểm Tra Cú Pháp JSON',
            ],
        ]);
});

it('executes JSON formatter tool successfully', function (): void {
    $payload = [
        'input' => [
            'json' => '{"name":"TechHub","status":"active"}',
            'action' => 'beautify',
            'indent_size' => 2,
        ],
    ];

    $response = $this->postJson('/api/tools/json-formatter/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'success' => true,
                'tool_slug' => 'json-formatter',
            ],
        ]);

    expect($response->json('data.result_data.result'))->toContain('"name": "TechHub"');
});

it('executes Base64 tool encode and decode', function (): void {
    $encodePayload = [
        'input' => [
            'text' => 'TechHub Platform 2026',
            'action' => 'encode',
        ],
    ];

    $response = $this->postJson('/api/tools/base64-encode-decode/execute', $encodePayload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'result_data' => [
                    'result' => base64_encode('TechHub Platform 2026'),
                ],
            ],
        ]);
});

it('executes Hash generator tool', function (): void {
    $payload = [
        'input' => [
            'text' => 'secret_password_123',
            'algorithm' => 'sha256',
        ],
    ];

    $response = $this->postJson('/api/tools/hash-generator/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.hashes.sha256'))->toBe(hash('sha256', 'secret_password_123'));
});

it('executes JWT debugger tool', function (): void {
    $header = base64_encode('{"alg":"HS256","typ":"JWT"}');
    $payload = base64_encode('{"sub":"1234567890","name":"Alex Johnson","iat":1516239022}');
    $dummyJwt = $header . '.' . $payload . '.dummy_signature';

    $response = $this->postJson('/api/tools/jwt-debugger/execute', [
        'input' => ['token' => $dummyJwt],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'result_data' => [
                    'algorithm' => 'HS256',
                    'payload' => [
                        'name' => 'Alex Johnson',
                    ],
                ],
            ],
        ]);
});

it('executes Loan Calculator tool', function (): void {
    $payload = [
        'input' => [
            'principal' => 100000,
            'annual_interest_rate' => 6.5,
            'term_months' => 360,
        ],
    ];

    $response = $this->postJson('/api/tools/loan-calculator/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.monthly_payment'))->toBe(632.07)
        ->and($response->json('data.result_data.term_years'))->toEqual(30);
});

it('executes Percentage Calculator tool', function (): void {
    $payload = [
        'input' => [
            'mode' => 'percent_of',
            'value_a' => 15,
            'value_b' => 200,
        ],
    ];

    $response = $this->postJson('/api/tools/percentage-calculator/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'result_data' => [
                    'result' => 30.0,
                ],
            ],
        ]);
});

it('executes BMI Calculator tool', function (): void {
    $payload = [
        'input' => [
            'unit_system' => 'metric',
            'height' => 175,
            'weight' => 70,
        ],
    ];

    $response = $this->postJson('/api/tools/bmi-calculator/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.bmi_score'))->toBe(22.9)
        ->and($response->json('data.result_data.category'))->toContain('Normal weight');
});

it('executes Regex Tester tool successfully', function (): void {
    $payload = [
        'input' => [
            'pattern' => '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}',
            'test_text' => 'Contact us at test@example.com or admin@domain.org',
            'flags' => 'gmi',
        ],
    ];

    $response = $this->postJson('/api/tools/regex-tester/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.is_match'))->toBeTrue()
        ->and($response->json('data.result_data.total_matches'))->toBe(2);
});

it('executes URL Encoder & Decoder tool successfully', function (): void {
    $payload = [
        'input' => [
            'url' => 'https://techhub.local/search?q=công cụ lập trình & ddd=true',
            'action' => 'encode',
            'standard' => 'rfc3986',
        ],
    ];

    $response = $this->postJson('/api/tools/url-encoder-decoder/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.result'))->toContain('%');
});

it('executes Image Metadata Inspector tool', function (): void {
    $im = imagecreatetruecolor(16, 16);
    $blue = imagecolorallocate($im, 0, 120, 255);
    imagefilledrectangle($im, 0, 0, 15, 15, $blue);
    ob_start();
    imagepng($im);
    $pngData = (string) ob_get_clean();
    imagedestroy($im);

    $payload = [
        'input' => [
            'image_base64' => 'data:image/png;base64,' . base64_encode($pngData),
        ],
    ];

    $response = $this->postJson('/api/tools/image-metadata-inspector/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.width_px'))->toBe(16)
        ->and($response->json('data.result_data.height_px'))->toBe(16)
        ->and($response->json('data.result_data.mime_type'))->toBe('image/png');
});

it('executes Image Color Palette Extractor tool and handles HSL safely', function (): void {
    $im = imagecreatetruecolor(20, 20);
    $red = imagecolorallocate($im, 255, 0, 0);
    imagefilledrectangle($im, 0, 0, 19, 19, $red);
    ob_start();
    imagepng($im);
    $pngData = (string) ob_get_clean();
    imagedestroy($im);

    $payload = [
        'input' => [
            'image_base64' => 'data:image/png;base64,' . base64_encode($pngData),
            'palette_size' => 5,
        ],
    ];

    $response = $this->postJson('/api/tools/image-color-extractor/execute', $payload);

    $response->assertStatus(200);
    expect($response->json('data.result_data.palette_count'))->toBeGreaterThan(0)
        ->and($response->json('data.result_data.palette.0.hex'))->toBeString();
});

it('returns 404 for non-existent tool', function (): void {
    $response = $this->getJson('/api/tools/non-existent-tool-slug');

    $response->assertStatus(404);
});
