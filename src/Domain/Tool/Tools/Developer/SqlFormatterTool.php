<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SqlFormatterTool implements ToolContract
{
    private const KEYWORDS = [
        'SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'OR', 'NOT', 'IN', 'BETWEEN', 'LIKE', 'IS NULL', 'IS NOT NULL',
        'GROUP BY', 'HAVING', 'ORDER BY', 'ASC', 'DESC', 'LIMIT', 'OFFSET',
        'JOIN', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'CROSS JOIN', 'FULL OUTER JOIN', 'ON',
        'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE FROM', 'CREATE TABLE', 'ALTER TABLE', 'DROP TABLE',
        'PRIMARY KEY', 'FOREIGN KEY', 'REFERENCES', 'INDEX', 'UNIQUE', 'AUTO_INCREMENT', 'DEFAULT', 'CASCADE',
        'UNION', 'UNION ALL', 'EXISTS', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'AS', 'COUNT', 'SUM', 'AVG', 'MIN', 'MAX',
    ];

    public function slug(): string
    {
        return 'sql-formatter';
    }

    public function name(): string
    {
        return 'SQL Formatter & Beautifier';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Format, beautify, indent, and uppercase standard SQL queries (MySQL, PostgreSQL, SQLite, SQL Server) with customizable indentation.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'sql' => ['required', 'string'],
            'action' => ['sometimes', 'string', 'in:beautify,minify'],
            'indent_size' => ['sometimes', 'integer', 'in:2,4'],
            'uppercase' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawSql = trim((string) ($input['sql'] ?? ''));
        $action = (string) ($input['action'] ?? 'beautify');
        $indentSize = (int) ($input['indent_size'] ?? 2);
        $indent = str_repeat(' ', $indentSize);
        $shouldUppercase = (bool) ($input['uppercase'] ?? true);

        if (empty($rawSql)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('SQL query cannot be empty.', $executionTimeMs);
        }

        if ('minify' === $action) {
            $minified = preg_replace('/\s+/', ' ', $rawSql);
            $minified = preg_replace('/\s*([,()=<>+\-*\/])\s*/', '$1', (string) $minified);
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => trim((string) $minified),
                'action' => 'minify',
                'original_length' => mb_strlen($rawSql),
                'minified_length' => mb_strlen((string) $minified),
            ], executionTimeMs: $executionTimeMs);
        }

        // Beautify SQL
        $formatted = $this->beautifySql($rawSql, $indent, $shouldUppercase);
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $formatted,
            'action' => 'beautify',
            'lines_count' => mb_substr_count($formatted, "\n") + 1,
            'original_length' => mb_strlen($rawSql),
            'formatted_length' => mb_strlen($formatted),
        ], executionTimeMs: $executionTimeMs);
    }

    private function beautifySql(string $sql, string $indent, bool $uppercase): string
    {
        // 1. Normalize line endings & collapse multiple spaces
        $sql = (string) preg_replace('/\s+/', ' ', $sql);

        // 2. Uppercase major keywords if enabled
        if ($uppercase) {
            foreach (self::KEYWORDS as $kw) {
                $pattern = '/\b' . preg_quote($kw, '/') . '\b/i';
                $sql = (string) preg_replace($pattern, $kw, $sql);
            }
        }

        // 3. Insert newlines before major clauses
        $majorClauses = [
            'SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT', 'OFFSET',
            'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'CROSS JOIN', 'FULL OUTER JOIN', 'JOIN',
            'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE FROM', 'UNION ALL', 'UNION',
        ];

        foreach ($majorClauses as $clause) {
            $pattern = '/\s+\b(' . preg_quote($clause, '/') . ')\b/i';
            $replacement = "\n$1";
            $sql = (string) preg_replace($pattern, $replacement, $sql);
        }

        // 4. Minor clause indentations
        $minorClauses = ['AND', 'OR', 'ON'];
        foreach ($minorClauses as $minor) {
            $pattern = '/\s+\b(' . preg_quote($minor, '/') . ')\b/i';
            $replacement = "\n{$indent}$1";
            $sql = (string) preg_replace($pattern, $replacement, $sql);
        }

        // 5. Clean trailing whitespace per line
        $lines = explode("\n", $sql);
        $trimmedLines = array_map('rtrim', $lines);

        return trim(implode("\n", $trimmedLines));
    }
}
