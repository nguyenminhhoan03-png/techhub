<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Illuminate\Support\Str;

class SqlToLaravelModelTool implements ToolContract
{
    public function slug(): string
    {
        return 'sql-to-laravel-model';
    }

    public function name(): string
    {
        return 'SQL to Laravel Eloquent Model Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate modern Laravel 11/12 Eloquent Models from SQL tables with fillable attributes, cast() methods, soft deletes, and automated relationships.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'sql' => ['required', 'string'],
            'model_name' => ['sometimes', 'string', 'max:100'],
            'namespace' => ['sometimes', 'string', 'max:100'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawSql = trim((string) ($input['sql'] ?? ''));

        if (empty($rawSql)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Please provide a valid SQL CREATE TABLE statement.', $executionTimeMs);
        }

        // 1. Extract table name
        $tableName = 'items';
        if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(?:`|")?([a-zA-Z0-9_]+)(?:`|")?/i', $rawSql, $m)) {
            $tableName = $m[1];
        }

        $modelName = trim((string) ($input['model_name'] ?? ''));
        if (empty($modelName)) {
            $modelName = Str::studly(Str::singular($tableName));
        } else {
            $modelName = Str::studly($modelName);
        }

        $namespace = trim((string) ($input['namespace'] ?? 'App\\Models'));
        if (empty($namespace)) {
            $namespace = 'App\\Models';
        }

        // 2. Extract column names & types
        $firstParen = mb_strpos($rawSql, '(');
        $lastParen = mb_strrpos($rawSql, ')');
        if (false === $firstParen || false === $lastParen || $lastParen <= $firstParen) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid SQL table definition structure.', $executionTimeMs);
        }

        $body = mb_substr($rawSql, $firstParen + 1, $lastParen - $firstParen - 1);
        $rawLines = explode(',', $body);

        $lines = [];
        $buffer = '';
        foreach ($rawLines as $part) {
            $buffer .= ('' === $buffer ? '' : ',') . $part;
            if (mb_substr_count($buffer, '(') === mb_substr_count($buffer, ')')) {
                $lines[] = trim($buffer);
                $buffer = '';
            }
        }
        if ( ! empty($buffer)) {
            $lines[] = trim($buffer);
        }

        $fillable = [];
        $casts = [];
        $relationships = [];
        $hasSoftDeletes = false;

        $excludedFillable = ['id', 'created_at', 'updated_at', 'deleted_at'];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || preg_match('/^(?:PRIMARY\s+KEY|KEY|INDEX|FULLTEXT|UNIQUE\s+KEY|CONSTRAINT|FOREIGN\s+KEY)/i', $line)) {
                continue;
            }

            if (preg_match('/^(?:`|")?([a-zA-Z0-9_]+)(?:`|")?\s+([a-zA-Z0-9_]+(?:\s*\([^)]+\))?)/i', $line, $colMatch)) {
                $col = $colMatch[1];
                $type = mb_strtolower($colMatch[2]);

                if ('deleted_at' === $col) {
                    $hasSoftDeletes = true;
                    continue;
                }

                if ( ! in_array($col, $excludedFillable, true)) {
                    $fillable[] = $col;
                }

                // Casts
                if (str_starts_with($type, 'tinyint(1)') || 'boolean' === $type || 'bool' === $type) {
                    $casts[$col] = "'boolean'";
                } elseif ('json' === $type || 'jsonb' === $type) {
                    $casts[$col] = "'array'";
                } elseif (str_starts_with($type, 'decimal') || str_starts_with($type, 'numeric')) {
                    if (preg_match('/\((\d+)\s*,\s*(\d+)\)/', $type, $dm)) {
                        $casts[$col] = "'decimal:{$dm[2]}'";
                    } else {
                        $casts[$col] = "'decimal:2'";
                    }
                } elseif ('date' === $type) {
                    $casts[$col] = "'date'";
                } elseif ('datetime' === $type || str_contains($type, 'timestamp')) {
                    $casts[$col] = "'datetime'";
                }

                // BelongsTo relationship
                if (str_ends_with($col, '_id')) {
                    $relationName = Str::camel(mb_substr($col, 0, -3));
                    $relatedModel = Str::studly(mb_substr($col, 0, -3));
                    $relationships[] = <<<PHP
    /**
     * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<\\{$namespace}\\{$relatedModel}, self>
     */
    public function {$relationName}(): \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
    {
        return \$this->belongsTo({$relatedModel}::class);
    }
PHP;
                }
            }
        }

        // Build Eloquent Model class
        $traits = ['use HasFactory;'];
        $useImports = ["use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;", "use Illuminate\\Database\\Eloquent\\Model;"];

        if ($hasSoftDeletes) {
            $traits[] = 'use SoftDeletes;';
            $useImports[] = "use Illuminate\\Database\\Eloquent\\SoftDeletes;";
        }

        $fillableExport = implode(",\n        ", array_map(fn($f) => "'{$f}'", $fillable));

        $castsCode = '';
        if ( ! empty($casts)) {
            $castsLines = [];
            foreach ($casts as $cKey => $cVal) {
                $castsLines[] = "            '{$cKey}' => {$cVal},";
            }
            $castsCode = "\n    /**\n     * @return array<string, string>\n     */\n    protected function casts(): array\n    {\n        return [\n" . implode("\n", $castsLines) . "\n        ];\n    }\n";
        }

        $relCode = empty($relationships) ? '' : "\n" . implode("\n\n", $relationships) . "\n";

        $modelCode = "<?php\n\ndeclare(strict_types=1);\n\n";
        $modelCode .= "namespace {$namespace};\n\n";
        $modelCode .= implode("\n", array_unique($useImports)) . "\n\n";
        $modelCode .= "class {$modelName} extends Model\n{\n";
        $modelCode .= "    " . implode("\n    ", $traits) . "\n\n";
        $modelCode .= "    protected \$table = '{$tableName}';\n\n";
        $modelCode .= "    /**\n     * @var array<int, string>\n     */\n";
        $modelCode .= "    protected \$fillable = [\n        {$fillableExport},\n    ];\n";
        $modelCode .= $castsCode;
        $modelCode .= $relCode;
        $modelCode .= "}\n";

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $modelCode,
            'model_name' => $modelName,
            'table_name' => $tableName,
            'fillable_count' => count($fillable),
            'casts_count' => count($casts),
            'relationships_count' => count($relationships),
        ], executionTimeMs: $executionTimeMs);
    }
}
