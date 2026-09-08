<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Pdf;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class PdfToExcelTool implements ToolContract
{
    public function slug(): string
    {
        return 'pdf-to-excel';
    }

    public function name(): string
    {
        return 'PDF to Excel / CSV Spreadsheet Converter';
    }

    public function categorySlug(): string
    {
        return 'pdf';
    }

    public function summary(): string
    {
        return 'Extract tabular data and structured text from PDF documents into Excel-compatible CSV spreadsheets and interactive data tables.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'raw_text' => ['required', 'string'],
            'delimiter' => ['sometimes', 'string', 'in:auto,tab,comma,pipe,spaces'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawText = trim((string) ($input['raw_text'] ?? ''));
        $delimiterMode = (string) ($input['delimiter'] ?? 'auto');

        if (empty($rawText)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Please paste table text extracted from PDF or spreadsheet lines.', $executionTimeMs);
        }

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $rawText));
        $parsedRows = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $cells = $this->splitLineIntoCells($line, $delimiterMode);
            if ( ! empty($cells)) {
                $parsedRows[] = $cells;
            }
        }

        if (empty($parsedRows)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('No valid tabular rows could be extracted.', $executionTimeMs);
        }

        // Normalize column counts
        $maxCols = max(array_map('count', $parsedRows));

        // Generate CSV content
        $csvBuffer = fopen('php://temp', 'r+');
        if (false !== $csvBuffer) {
            foreach ($parsedRows as $row) {
                // Pad row if shorter than maxCols
                while (count($row) < $maxCols) {
                    $row[] = '';
                }
                fputcsv($csvBuffer, $row);
            }
            rewind($csvBuffer);
            $csvContent = stream_get_contents($csvBuffer);
            fclose($csvBuffer);
        } else {
            $csvContent = '';
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => (string) $csvContent,
            'csv_content' => (string) $csvContent,
            'rows_count' => count($parsedRows),
            'columns_count' => $maxCols,
            'preview_rows' => array_slice($parsedRows, 0, 30),
        ], executionTimeMs: $executionTimeMs);
    }

    /**
     * @return array<int, string>
     */
    private function splitLineIntoCells(string $line, string $mode): array
    {
        if ('tab' === $mode || ('auto' === $mode && str_contains($line, "\t"))) {
            return array_map('trim', explode("\t", $line));
        }

        if ('pipe' === $mode || ('auto' === $mode && str_contains($line, '|'))) {
            $cells = explode('|', trim($line, '|'));

            return array_values(array_filter(array_map('trim', $cells), fn($c) => '' !== $c));
        }

        if ('comma' === $mode || ('auto' === $mode && str_contains($line, ','))) {
            $csv = str_getcsv($line);

            return array_map('trim', $csv);
        }

        // Multiple spaces (2 or more spaces)
        $cells = preg_split('/\s{2,}/', $line);

        return false !== $cells ? array_map('trim', $cells) : [$line];
    }
}
