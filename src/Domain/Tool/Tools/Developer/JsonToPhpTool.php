<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use JsonException;

class JsonToPhpTool implements ToolContract
{
    public function slug(): string
    {
        return 'json-to-php';
    }

    public function name(): string
    {
        return 'JSON to PHP Array & DTO Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Convert raw JSON to modern PHP array syntax or PHP 8.2+ typed readonly DTO classes with constructor property promotion.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'json' => ['required', 'string'],
            'mode' => ['sometimes', 'string', 'in:array,dto'],
            'class_name' => ['sometimes', 'string', 'max:60'],
            'indent_size' => ['sometimes', 'integer', 'in:2,4'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawJson = trim((string) ($input['json'] ?? ''));
        $mode = (string) ($input['mode'] ?? 'array');
        $className = preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($input['class_name'] ?? 'DataDto'));
        if (empty($className)) {
            $className = 'DataDto';
        }
        $className = ucfirst($className);

        $indentSize = (int) ($input['indent_size'] ?? 4);
        $indent = str_repeat(' ', $indentSize);

        try {
            /** @var mixed $decoded */
            $decoded = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            if ('dto' === $mode) {
                if ( ! is_array($decoded) || array_keys($decoded) === range(0, count($decoded) - 1)) {
                    return ToolResult::failure('Root JSON must be an object (key-value dictionary) to generate a PHP DTO class.', $executionTimeMs);
                }

                $dtoCode = $this->generateDto($decoded, $className, $indent);

                return ToolResult::success([
                    'result' => $dtoCode,
                    'mode' => 'dto',
                    'class_name' => $className,
                    'fields_count' => count($decoded),
                ], executionTimeMs: $executionTimeMs);
            }

            // Default: Modern PHP array syntax
            $phpArrayCode = "<?php\n\nreturn " . $this->formatPhpArray($decoded, $indent, 0) . ";\n";

            return ToolResult::success([
                'result' => $phpArrayCode,
                'mode' => 'array',
                'items_count' => is_countable($decoded) ? count($decoded) : 1,
            ], executionTimeMs: $executionTimeMs);

        } catch (JsonException $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid JSON: ' . $e->getMessage(), $executionTimeMs);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function generateDto(array $data, string $className, string $indent): string
    {
        $props = [];
        $fromArrayAssignments = [];

        foreach ($data as $key => $value) {
            $safeVar = (string) preg_replace('/[^a-zA-Z0-9_]/', '', (string) $key);
            if (empty($safeVar) || is_numeric($safeVar[0])) {
                $safeVar = 'prop_' . $safeVar;
            }

            $type = match (gettype($value)) {
                'integer' => 'int',
                'double' => 'float',
                'boolean' => 'bool',
                'string' => 'string',
                'array' => 'array',
                'NULL' => '?string',
                default => 'mixed',
            };

            $isNullable = (null === $value);
            $typeStr = $isNullable ? '?string' : $type;
            $defaultVal = $isNullable ? ' = null' : '';

            $props[] = "{$indent}{$indent}public readonly {$typeStr} \${$safeVar}{$defaultVal},";

            // fromArray mapping
            $cast = match ($type) {
                'int' => "(int) (\$data['{$key}'] ?? 0)",
                'float' => "(float) (\$data['{$key}'] ?? 0.0)",
                'bool' => "(bool) (\$data['{$key}'] ?? false)",
                'string' => "(string) (\$data['{$key}'] ?? '')",
                'array' => "(array) (\$data['{$key}'] ?? [])",
                default => "\$data['{$key}'] ?? null",
            };

            $fromArrayAssignments[] = "{$indent}{$indent}{$indent}{$safeVar}: {$cast},";
        }

        $code = "<?php\n\ndeclare(strict_types=1);\n\n";
        $code .= "final readonly class {$className}\n{\n";
        $code .= "{$indent}public function __construct(\n" . implode("\n", $props) . "\n{$indent}) {}\n\n";
        $code .= "{$indent}/**\n{$indent} * @param array<string, mixed> \$data\n{$indent} */\n";
        $code .= "{$indent}public static function fromArray(array \$data): self\n{$indent}{\n";
        $code .= "{$indent}{$indent}return new self(\n" . implode("\n", $fromArrayAssignments) . "\n{$indent}{$indent});\n";
        $code .= "{$indent}}\n}\n";

        return $code;
    }

    private function formatPhpArray(mixed $data, string $indent, int $depth): string
    {
        if (null === $data) {
            return 'null';
        }
        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }
        if (is_int($data) || is_float($data)) {
            return (string) $data;
        }
        if (is_string($data)) {
            return var_export($data, true);
        }

        if (is_array($data)) {
            if (empty($data)) {
                return '[]';
            }

            $currentIndent = str_repeat($indent, $depth);
            $nextIndent = str_repeat($indent, $depth + 1);

            $isAssoc = array_keys($data) !== range(0, count($data) - 1);
            $elements = [];

            foreach ($data as $key => $value) {
                $formattedValue = $this->formatPhpArray($value, $indent, $depth + 1);
                if ($isAssoc) {
                    $keyExport = var_export($key, true);
                    $elements[] = "{$nextIndent}{$keyExport} => {$formattedValue},";
                } else {
                    $elements[] = "{$nextIndent}{$formattedValue},";
                }
            }

            return "[\n" . implode("\n", $elements) . "\n{$currentIndent}]";
        }

        return 'null';
    }
}
