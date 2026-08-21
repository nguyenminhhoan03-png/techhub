<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class Base64Tool implements ToolContract
{
    public function slug(): string
    {
        return 'base64-encode-decode';
    }

    public function name(): string
    {
        return 'Base64 Encoder & Decoder';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Encode and decode plain text or binary strings to and from Base64 with URL-safe option.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'text' => ['required', 'string'],
            'action' => ['required', 'string', 'in:encode,decode'],
            'url_safe' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $text = (string) ($input['text'] ?? '');
        $action = (string) ($input['action'] ?? 'encode');
        $urlSafe = (bool) ($input['url_safe'] ?? false);

        if ('encode' === $action) {
            $encoded = base64_encode($text);

            if ($urlSafe) {
                $encoded = str_replace(['+', '/', '='], ['-', '_', ''], $encoded);
            }

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => $encoded,
                'input_length' => mb_strlen($text),
                'output_length' => mb_strlen($encoded),
            ], executionTimeMs: $executionTimeMs);
        }

        // Decode action
        $toDecode = $text;
        if ($urlSafe || str_contains($text, '-') || str_contains($text, '_')) {
            $toDecode = str_replace(['-', '_'], ['+', '/'], $text);
            $mod4 = mb_strlen($toDecode) % 4;
            if ($mod4) {
                $toDecode .= mb_substr('====', $mod4);
            }
        }

        $decoded = base64_decode($toDecode, true);
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        if (false === $decoded) {
            return ToolResult::failure('Invalid Base64 string provided.', $executionTimeMs);
        }

        return ToolResult::success([
            'result' => $decoded,
            'input_length' => mb_strlen($text),
            'output_length' => mb_strlen($decoded),
            'is_utf8' => mb_check_encoding($decoded, 'UTF-8'),
        ], executionTimeMs: $executionTimeMs);
    }
}
