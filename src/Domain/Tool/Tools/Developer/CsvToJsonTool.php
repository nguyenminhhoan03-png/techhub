<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class CsvToJsonTool implements ToolContract
{
    public function slug(): string
    {
        return 'csv-to-json';
    }

    public function name(): string
    {
        return 'CSV to JSON Converter';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Convert comma-separated values (CSV) into structured JSON arrays or key-value objects with auto data-type detection.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'csv' => ['required', 'string'],
            'delimiter' => ['sometimes', 'string', 'in:auto,comma,semicolon,tab,pipe'],
            'has_headers' => ['sometimes', 'boolean'],
            'parse_numbers' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawCsv = trim((string) ($input['csv'] ?? ''));
        $delimiterMode = (string) ($input['delimiter'] ?? 'auto');
        $hasHeaders = (bool) ($input['has_headers'] ?? true);
        $parseNumbers = (bool) ($input['parse_numbers'] ?? true);

        if (empty($rawCsv)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('CSV content cannot be empty.', $executionTimeMs);
        }

        $delimiter = match ($delimiterMode) {
            'semicolon' => ';',
            'tab' => "\t",
            'pipe' => '|',
            'comma' => ',',
            default => $this->detectDelimiter($rawCsv),
        };

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $rawCsv));
        $rows = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ('' === $line) {
                continue;
            }
            $row = str_getcsv($line, $delimiter);
            $rows[] = $row;
        }

        if (empty($rows)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Failed to parse any CSV rows.', $executionTimeMs);
        }

        $jsonResult = [];

        if ($hasHeaders && count($rows) > 1) {
            $headers = array_shift($rows);
            // Clean headers
            $headers = array_map(fn($h) => trim((string) $h), (array) $headers);

            foreach ($rows as $row) {
                $obj = [];
                foreach ($headers as $idx => $header) {
                    $val = $row[$idx] ?? null;
                    $obj[$header] = $parseNumbers ? $this->castValue($val) : $val;
                }
                $jsonResult[] = $obj;
            }
        } else {
            foreach ($rows as $row) {
                $jsonResult[] = $parseNumbers
                    ? array_map(fn($val) => $this->castValue($val), $row)
                    : $row;
            }
        }

        $prettyJson = json_encode($jsonResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => (string) $prettyJson,
            'rows_count' => count($jsonResult),
            'delimiter_used' => $delimiter,
            'has_headers' => $hasHeaders,
            'size_bytes' => mb_strlen((string) $prettyJson),
        ], executionTimeMs: $executionTimeMs);
    }

    private function detectDelimiter(string $csv): string
    {
        $firstLine = strtok($csv, "\r\n");
        if (false === $firstLine) {
            return ',';
        }

        $delimiters = [',' => 0, ';' => 0, "\t" => 0, '|' => 0];
        foreach ($delimiters as $d => $count) {
            $delimiters[$d] = mb_substr_count($firstLine, $d);
        }

        arsort($delimiters);

        return (string) key($delimiters);
    }

    private function castValue(mixed $val): mixed
    {
        if (null === $val) {
            return null;
        }

        $trimmed = trim((string) $val);

        if ('null' === mb_strtolower($trimmed)) {
            return null;
        }
        if ('true' === mb_strtolower($trimmed)) {
            return true;
        }
        if ('false' === mb_strtolower($trimmed)) {
            return false;
        }
        if (is_numeric($trimmed)) {
            return str_contains($trimmed, '.') ? (float) $trimmed : (int) $trimmed;
        }

        return $val;
    }
}
