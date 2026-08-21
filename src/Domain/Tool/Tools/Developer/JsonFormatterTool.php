<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use JsonException;

class JsonFormatterTool implements ToolContract
{
    public function slug(): string
    {
        return 'json-formatter';
    }

    public function name(): string
    {
        return 'JSON Formatter & Validator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Format, beautify, minify, and validate raw JSON with customizable indentation.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'json' => ['required', 'string'],
            'action' => ['sometimes', 'string', 'in:beautify,minify,validate'],
            'indent_size' => ['sometimes', 'integer', 'min:2', 'max:8'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawJson = (string) ($input['json'] ?? '');
        $action = (string) ($input['action'] ?? 'beautify');
        $indentSize = (int) ($input['indent_size'] ?? 2);

        try {
            /** @var mixed $decoded */
            $decoded = json_decode($rawJson, false, 512, JSON_THROW_ON_ERROR);

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            if ('validate' === $action) {
                return ToolResult::success([
                    'is_valid' => true,
                    'type' => is_array($decoded) ? 'array' : (is_object($decoded) ? 'object' : gettype($decoded)),
                    'size_bytes' => mb_strlen($rawJson),
                ], executionTimeMs: $executionTimeMs);
            }

            if ('minify' === $action) {
                $minified = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                return ToolResult::success([
                    'result' => $minified,
                    'original_size_bytes' => mb_strlen($rawJson),
                    'minified_size_bytes' => mb_strlen((string) $minified),
                    'saved_percentage' => mb_strlen($rawJson) > 0
                        ? round(((mb_strlen($rawJson) - mb_strlen((string) $minified)) / mb_strlen($rawJson)) * 100, 2)
                        : 0,
                ], executionTimeMs: $executionTimeMs);
            }

            // Beautify with custom indentation
            $pretty = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            if (false !== $pretty && 4 !== $indentSize) {
                // Adjust indentation if different from standard 4 spaces
                $indent = str_repeat(' ', $indentSize);
                $pretty = (string) preg_replace_callback('/^( {4})+/m', function ($matches) use ($indent) {
                    $level = mb_strlen($matches[0]) / 4;

                    return str_repeat($indent, (int) $level);
                }, $pretty);
            }

            return ToolResult::success([
                'result' => $pretty,
                'is_valid' => true,
                'item_count' => is_countable($decoded) ? count((array) $decoded) : 1,
            ], executionTimeMs: $executionTimeMs);

        } catch (JsonException $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid JSON: ' . $e->getMessage(), $executionTimeMs);
        }
    }
}
