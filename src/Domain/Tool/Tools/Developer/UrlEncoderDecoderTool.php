<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class UrlEncoderDecoderTool implements ToolContract
{
    public function slug(): string
    {
        return 'url-encoder-decoder';
    }

    public function name(): string
    {
        return 'URL Encoder & Decoder';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Encode special characters in URLs (RFC 3986) or decode query parameters and URI components.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'url' => ['required', 'string'],
            'action' => ['required', 'string', 'in:encode,decode'],
            'standard' => ['sometimes', 'string', 'in:rfc3986,legacy'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $url = (string) ($input['url'] ?? '');
        $action = (string) ($input['action'] ?? 'encode');
        $standard = (string) ($input['standard'] ?? 'rfc3986');

        if ('encode' === $action) {
            $result = 'rfc3986' === $standard ? rawurlencode($url) : urlencode($url);
        } else {
            $result = 'rfc3986' === $standard ? rawurldecode($url) : urldecode($url);
        }

        $parsed = parse_url($result);

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $result,
            'original' => $url,
            'action' => $action,
            'parsed_url' => false !== $parsed ? $parsed : null,
        ], executionTimeMs: $executionTimeMs);
    }
}
