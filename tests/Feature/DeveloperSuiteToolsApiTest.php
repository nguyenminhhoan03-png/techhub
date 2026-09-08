<?php

declare(strict_types=1);

use Database\Seeders\DeveloperSuiteToolsSeeder;
use Database\Seeders\ToolSeeder;

beforeEach(function (): void {
    $this->seed(ToolSeeder::class);
    $this->seed(DeveloperSuiteToolsSeeder::class);
});

it('executes json-to-typescript via api endpoint', function (): void {
    $payload = [
        'input' => [
            'json' => '{"id": 1, "name": "Antigravity", "is_ai": true}',
            'root_name' => 'Agent',
        ],
    ];

    $response = $this->postJson('/api/tools/json-to-typescript/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'tool_slug' => 'json-to-typescript',
            ],
        ]);

    expect($response->json('data.result_data.result'))->toContain('interface Agent');
});

it('executes sql-formatter via api endpoint', function (): void {
    $payload = [
        'input' => [
            'sql' => 'select * from users where id = 10;',
            'action' => 'beautify',
        ],
    ];

    $response = $this->postJson('/api/tools/sql-formatter/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.result_data.result'))->toContain('SELECT');
});

it('executes uuid-generator via api endpoint', function (): void {
    $payload = [
        'input' => [
            'version' => 'v7',
            'count' => 3,
            'hyphens' => true,
        ],
    ];

    $response = $this->postJson('/api/tools/uuid-generator/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'result_data' => [
                    'count' => 3,
                    'version' => 'v7',
                ],
            ],
        ]);
});

it('executes timestamp-converter via api endpoint', function (): void {
    $payload = [
        'input' => [
            'mode' => 'epoch_to_date',
            'timestamp' => 1700000000,
            'timezone' => 'UTC',
        ],
    ];

    $response = $this->postJson('/api/tools/timestamp-converter/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'result_data' => [
                    'epoch_seconds' => 1700000000,
                ],
            ],
        ]);
});

it('executes password-generator via api endpoint', function (): void {
    $payload = [
        'input' => [
            'length' => 16,
            'count' => 2,
            'include_uppercase' => true,
            'include_lowercase' => true,
            'include_numbers' => true,
        ],
    ];

    $response = $this->postJson('/api/tools/password-generator/execute', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect($response->json('data.result_data.passwords'))->toHaveCount(2);
});

it('executes cron-generator via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/cron-generator/execute', [
        'input' => ['expression' => '0 * * * *'],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);
    expect($response->json('data.result_data.laravel_code'))->toContain('hourly()');
});

it('executes csv-to-json via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/csv-to-json/execute', [
        'input' => [
            'csv' => "name,role\nAlice,Admin",
            'delimiter' => 'comma',
            'has_headers' => true,
        ],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    expect($response->json('data.result_data.rows_count'))->toBe(1);
});

it('executes html-formatter via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/html-formatter/execute', [
        'input' => [
            'html' => '<div><span>Hi</span></div>',
            'action' => 'beautify',
        ],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes css-minifier via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/css-minifier/execute', [
        'input' => [
            'css' => 'body { color: red; }',
            'action' => 'minify',
        ],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    expect($response->json('data.result_data.result'))->toBe('body{color:red}');
});

it('executes xml-to-json via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/xml-to-json/execute', [
        'input' => ['xml' => '<root><title>Test</title></root>'],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes json-to-php via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/json-to-php/execute', [
        'input' => ['json' => '{"foo": "bar"}', 'mode' => 'array'],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes sql-to-laravel-migration via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/sql-to-laravel-migration/execute', [
        'input' => ['sql' => 'CREATE TABLE articles (id INT);'],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes sql-to-laravel-model via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/sql-to-laravel-model/execute', [
        'input' => ['sql' => 'CREATE TABLE articles (id INT);'],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes laravel-crud-generator via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/laravel-crud-generator/execute', [
        'input' => ['model_name' => 'Project', 'fields' => 'title:string'],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
});

it('executes pdf-to-excel via api endpoint', function (): void {
    $response = $this->postJson('/api/tools/pdf-to-excel/execute', [
        'input' => ['raw_text' => "ColA\tColB\nValA\tValB"],
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    expect($response->json('data.result_data.rows_count'))->toBe(2);
});
