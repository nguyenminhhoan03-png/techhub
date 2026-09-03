<?php

declare(strict_types=1);

use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\Tools\Developer\ProxyCheckerTool;

test('proxy checker tool has valid contract metadata', function (): void {
    $tool = new ProxyCheckerTool();

    expect($tool->slug())->toBe('proxy-checker')
        ->and($tool->categorySlug())->toBe('developer')
        ->and($tool->engineType())->toBe(ToolEngineType::ServerSync)
        ->and($tool->validationRules())->toHaveKey('proxies');
});

test('proxy checker fails gracefully with empty input', function (): void {
    $tool = new ProxyCheckerTool();
    $result = $tool->execute(['proxies' => '   ']);

    expect($result->isSuccess)->toBeFalse()
        ->and($result->errorMessage)->not->toBeEmpty();
});

test('proxy checker parses and marks invalid proxy format as dead', function (): void {
    $tool = new ProxyCheckerTool();
    $result = $tool->execute([
        'proxies' => "invalid-format\n127.0.0.1:invalidport",
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['total'])->toBe(2)
        ->and($result->data['dead_count'])->toBe(2)
        ->and($result->data['live_count'])->toBe(0);
});
