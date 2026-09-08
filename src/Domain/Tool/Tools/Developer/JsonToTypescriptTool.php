<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use JsonException;

class JsonToTypescriptTool implements ToolContract
{
    public function slug(): string
    {
        return 'json-to-typescript';
    }

    public function name(): string
    {
        return 'JSON to TypeScript Converter';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Convert raw JSON objects into clean, type-safe TypeScript interfaces or type aliases with nested type extraction.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'json' => ['required', 'string'],
            'root_name' => ['sometimes', 'string', 'max:60'],
            'declaration_type' => ['sometimes', 'string', 'in:interface,type'],
            'indent_size' => ['sometimes', 'integer', 'in:2,4'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawJson = trim((string) ($input['json'] ?? ''));
        $rootName = preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($input['root_name'] ?? 'RootObject'));
        if (empty($rootName)) {
            $rootName = 'RootObject';
        }
        $rootName = ucfirst($rootName);

        $declarationType = (string) ($input['declaration_type'] ?? 'interface');
        $indentSize = (int) ($input['indent_size'] ?? 2);
        $indent = str_repeat(' ', $indentSize);

        try {
            /** @var mixed $decoded */
            $decoded = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);

            /** @var array<string, array<string, string>> $interfaces */
            $interfaces = [];
            $this->extractTypes($decoded, $rootName, $interfaces);

            $output = [];
            foreach ($interfaces as $name => $fields) {
                if ('type' === $declarationType) {
                    $output[] = "export type {$name} = {";
                } else {
                    $output[] = "export interface {$name} {";
                }

                foreach ($fields as $fieldName => $fieldType) {
                    $safeKey = preg_match('/^[a-zA-Z_$][a-zA-Z0-9_$]*$/', $fieldName)
                        ? $fieldName
                        : json_encode($fieldName, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $output[] = "{$indent}{$safeKey}: {$fieldType};";
                }

                $output[] = "}\n";
            }

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => trim(implode("\n", $output)),
                'interfaces_count' => count($interfaces),
                'root_name' => $rootName,
                'declaration_type' => $declarationType,
            ], executionTimeMs: $executionTimeMs);

        } catch (JsonException $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid JSON: ' . $e->getMessage(), $executionTimeMs);
        }
    }

    /**
     * @param mixed $data
     * @param array<string, array<string, string>> $interfaces
     */
    private function extractTypes(mixed $data, string $typeName, array &$interfaces): string
    {
        if (null === $data) {
            return 'null';
        }

        if (is_bool($data)) {
            return 'boolean';
        }

        if (is_int($data) || is_float($data)) {
            return 'number';
        }

        if (is_string($data)) {
            return 'string';
        }

        if (is_array($data)) {
            // Check if associative array (object) or sequential array
            $isAssoc = array_keys($data) !== range(0, count($data) - 1);

            if ($isAssoc) {
                $fields = [];
                foreach ($data as $key => $val) {
                    $childTypeName = $typeName . ucfirst((string) preg_replace('/[^a-zA-Z0-9_]/', '', (string) $key));
                    $fieldType = $this->extractTypes($val, $childTypeName, $interfaces);
                    $fields[(string) $key] = $fieldType;
                }

                $interfaces[$typeName] = $fields;

                return $typeName;
            }

            // Sequential array
            if (empty($data)) {
                return 'unknown[]';
            }

            $itemTypes = [];
            foreach ($data as $item) {
                $itemTypeName = $typeName . 'Item';
                $t = $this->extractTypes($item, $itemTypeName, $interfaces);
                if ( ! in_array($t, $itemTypes, true)) {
                    $itemTypes[] = $t;
                }
            }

            if (1 === count($itemTypes)) {
                return $itemTypes[0] . '[]';
            }

            return '(' . implode(' | ', $itemTypes) . ')[]';
        }

        return 'unknown';
    }
}
