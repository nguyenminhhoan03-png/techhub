<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SqlToLaravelMigrationTool implements ToolContract
{
    public function slug(): string
    {
        return 'sql-to-laravel-migration';
    }

    public function name(): string
    {
        return 'SQL to Laravel Migration Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Convert SQL CREATE TABLE statements directly into Laravel migration schema files with column types, modifiers, indexes, and foreign keys.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'sql' => ['required', 'string'],
            'table_name' => ['sometimes', 'string', 'max:100'],
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
        $tableName = trim((string) ($input['table_name'] ?? ''));
        if (empty($tableName)) {
            if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(?:`|")?([a-zA-Z0-9_]+)(?:`|")?/i', $rawSql, $m)) {
                $tableName = $m[1];
            } else {
                $tableName = 'my_table';
            }
        }

        // 2. Extract column definitions inside parentheses
        $firstParen = mb_strpos($rawSql, '(');
        $lastParen = mb_strrpos($rawSql, ')');

        if (false === $firstParen || false === $lastParen || $lastParen <= $firstParen) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Could not detect column definitions inside parentheses in the provided SQL.', $executionTimeMs);
        }

        $body = mb_substr($rawSql, $firstParen + 1, $lastParen - $firstParen - 1);
        $rawLines = explode(',', $body);

        // Group broken lines if comma was inside parentheses (e.g. DECIMAL(10, 2))
        $lines = [];
        $buffer = '';
        foreach ($rawLines as $part) {
            $buffer .= ('' === $buffer ? '' : ',') . $part;
            $openCount = mb_substr_count($buffer, '(');
            $closeCount = mb_substr_count($buffer, ')');
            if ($openCount === $closeCount) {
                $lines[] = trim($buffer);
                $buffer = '';
            }
        }
        if ( ! empty($buffer)) {
            $lines[] = trim($buffer);
        }

        $migrationLines = [];
        $hasTimestamps = false;
        $hasSoftDeletes = false;
        $columnsCount = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Skip table-level primary key / foreign key / index lines for now or parse them
            if (preg_match('/^(?:PRIMARY\s+KEY|KEY|INDEX|FULLTEXT|UNIQUE\s+KEY|CONSTRAINT|FOREIGN\s+KEY)/i', $line)) {
                if (preg_match('/FOREIGN\s+KEY\s*\((?:`|")?([a-zA-Z0-9_]+)(?:`|")?\)\s*REFERENCES\s*(?:`|")?([a-zA-Z0-9_]+)(?:`|")?\s*\((?:`|")?([a-zA-Z0-9_]+)(?:`|")?\)/i', $line, $fkMatch)) {
                    $fkCol = $fkMatch[1];
                    $refTable = $fkMatch[2];
                    $migrationLines[] = "            \$table->foreign('{$fkCol}')->references('id')->on('{$refTable}')->cascadeOnDelete();";
                }
                continue;
            }

            // Parse column: name, type, modifiers
            if ( ! preg_match('/^(?:`|")?([a-zA-Z0-9_]+)(?:`|")?\s+([a-zA-Z0-9_]+(?:\s*\([^)]+\))?)(.*)$/i', $line, $colMatch)) {
                continue;
            }

            $colName = $colMatch[1];
            $colTypeRaw = mb_strtolower(trim($colMatch[2]));
            $modifiers = mb_strtoupper(trim($colMatch[3]));

            if (in_array($colName, ['created_at', 'updated_at'], true)) {
                $hasTimestamps = true;
                continue;
            }

            if ('deleted_at' === $colName) {
                $hasSoftDeletes = true;
                continue;
            }

            $columnsCount++;

            // Detect primary key id
            if (('id' === $colName && str_contains($modifiers, 'AUTO_INCREMENT')) || str_contains($modifiers, 'PRIMARY KEY')) {
                if ('id' === $colName) {
                    $migrationLines[] = '            $table->id();';
                    continue;
                }
            }

            // Map type
            $schemaMethod = $this->mapColumnType($colName, $colTypeRaw);

            // Modifiers
            $extra = '';
            if (str_contains($modifiers, 'NOT NULL')) {
                // Default is not null in Laravel
            } elseif (str_contains($modifiers, 'NULL')) {
                $extra .= '->nullable()';
            }

            if (preg_match('/DEFAULT\s+([^,\s]+)/i', $modifiers, $defMatch)) {
                $defVal = trim($defMatch[1], "'\"");
                if ('NULL' === mb_strtoupper($defVal)) {
                    $extra .= '->nullable()';
                } elseif (is_numeric($defVal)) {
                    $extra .= "->default({$defVal})";
                } elseif (in_array(mb_strtoupper($defVal), ['TRUE', 'FALSE'], true)) {
                    $extra .= '->default(' . mb_strtolower($defVal) . ')';
                } else {
                    $extra .= "->default('{$defVal}')";
                }
            }

            if (str_contains($modifiers, 'UNIQUE')) {
                $extra .= '->unique()';
            }

            $migrationLines[] = "            {$schemaMethod}{$extra};";
        }

        if ($hasTimestamps) {
            $migrationLines[] = '            $table->timestamps();';
        }

        if ($hasSoftDeletes) {
            $migrationLines[] = '            $table->softDeletes();';
        }

        $code = "<?php\n\ndeclare(strict_types=1);\n\n";
        $code .= "use Illuminate\\Database\\Migrations\\Migration;\n";
        $code .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
        $code .= "use Illuminate\\Support\\Facades\\Schema;\n\n";
        $code .= "return new class extends Migration\n{\n";
        $code .= "    public function up(): void\n    {\n";
        $code .= "        Schema::create('{$tableName}', function (Blueprint \$table): void {\n";
        $code .= implode("\n", $migrationLines) . "\n";
        $code .= "        });\n    }\n\n";
        $code .= "    public function down(): void\n    {\n";
        $code .= "        Schema::dropIfExists('{$tableName}');\n    }\n};\n";

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $code,
            'table_name' => $tableName,
            'columns_count' => $columnsCount,
        ], executionTimeMs: $executionTimeMs);
    }

    private function mapColumnType(string $colName, string $typeRaw): string
    {
        if (str_ends_with($colName, '_id')) {
            return "\$table->foreignId('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'varchar') || str_starts_with($typeRaw, 'nvarchar')) {
            if (preg_match('/\((\d+)\)/', $typeRaw, $m)) {
                return "\$table->string('{$colName}', {$m[1]})";
            }

            return "\$table->string('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'char')) {
            if (preg_match('/\((\d+)\)/', $typeRaw, $m)) {
                return "\$table->char('{$colName}', {$m[1]})";
            }

            return "\$table->char('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'tinyint(1)') || 'boolean' === $typeRaw || 'bool' === $typeRaw) {
            return "\$table->boolean('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'tinyint')) {
            return "\$table->tinyInteger('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'smallint')) {
            return "\$table->smallInteger('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'bigint')) {
            return "\$table->bigInteger('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'int')) {
            return "\$table->integer('{$colName}')";
        }

        if (str_starts_with($typeRaw, 'decimal') || str_starts_with($typeRaw, 'numeric')) {
            if (preg_match('/\((\d+)\s*,\s*(\d+)\)/', $typeRaw, $m)) {
                return "\$table->decimal('{$colName}', {$m[1]}, {$m[2]})";
            }

            return "\$table->decimal('{$colName}', 10, 2)";
        }

        if ('longtext' === $typeRaw) {
            return "\$table->longText('{$colName}')";
        }

        if ('mediumtext' === $typeRaw) {
            return "\$table->mediumText('{$colName}')";
        }

        if (str_contains($typeRaw, 'text')) {
            return "\$table->text('{$colName}')";
        }

        if ('json' === $typeRaw || 'jsonb' === $typeRaw) {
            return "\$table->json('{$colName}')";
        }

        if ('date' === $typeRaw) {
            return "\$table->date('{$colName}')";
        }

        if ('datetime' === $typeRaw) {
            return "\$table->dateTime('{$colName}')";
        }

        if (str_contains($typeRaw, 'timestamp')) {
            return "\$table->timestamp('{$colName}')";
        }

        return "\$table->string('{$colName}')";
    }
}
