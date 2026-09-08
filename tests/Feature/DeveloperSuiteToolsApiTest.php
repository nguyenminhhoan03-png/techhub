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
