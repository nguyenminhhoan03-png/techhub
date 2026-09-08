<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class HtmlFormatterTool implements ToolContract
{
    public function slug(): string
    {
        return 'html-formatter';
    }

    public function name(): string
    {
        return 'HTML Formatter & Minifier';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Format, beautify, and minify HTML markup with indentation, tag hierarchy analysis, and space optimization.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'html' => ['required', 'string'],
            'action' => ['sometimes', 'string', 'in:beautify,minify'],
            'indent_size' => ['sometimes', 'integer', 'in:2,4'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawHtml = trim((string) ($input['html'] ?? ''));
        $action = (string) ($input['action'] ?? 'beautify');
        $indentSize = (int) ($input['indent_size'] ?? 2);

        if (empty($rawHtml)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('HTML content cannot be empty.', $executionTimeMs);
        }

        if ('minify' === $action) {
            // Minify: Remove comments and collapse whitespace outside of pre/code/textarea
            $minified = preg_replace('/<!--(?!\[if).*?-->/s', '', $rawHtml);
            $minified = preg_replace('/>\s+</', '><', (string) $minified);
            $minified = preg_replace('/\s+/', ' ', (string) $minified);

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);
            $origLen = mb_strlen($rawHtml);
            $minLen = mb_strlen(trim((string) $minified));
            $savedPct = round((($origLen - $minLen) / $origLen) * 100, 2);

            return ToolResult::success([
                'result' => trim((string) $minified),
                'action' => 'minify',
                'original_size_bytes' => $origLen,
                'minified_size_bytes' => $minLen,
                'saved_percentage' => $savedPct,
            ], executionTimeMs: $executionTimeMs);
        }

        // Beautify HTML
        $beautified = $this->beautifyHtml($rawHtml, $indentSize);
        $tagCount = preg_match_all('/<[a-zA-Z0-9]+(?:\s+[^>]*)?>/', $beautified);
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $beautified,
            'action' => 'beautify',
            'tags_count' => (int) $tagCount,
            'original_size_bytes' => mb_strlen($rawHtml),
            'formatted_size_bytes' => mb_strlen($beautified),
        ], executionTimeMs: $executionTimeMs);
    }

    private function beautifyHtml(string $html, int $indentSize = 2): string
    {
        // Normalize whitespace and split tags into separate lines
        $html = (string) preg_replace('/>\s*</', ">\n<", trim($html));
        $lines = explode("\n", $html);
        $result = [];
        $indent = 0;
        $indentStr = str_repeat(' ', $indentSize);

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ('' === $trimmed) {
                continue;
            }

            // Closing tag
            $isClosing = (bool) preg_match('/^<\/[^>]+>/', $trimmed);
            // Self-closing or void elements
            $isVoid = (bool) preg_match('/^<(?:area|base|br|col|embed|hr|img|input|link|meta|param|source|track|wbr)[^>]*\/?>/i', $trimmed);
            // Single-line open and close: <p>Text</p>
            $isSingleLine = (bool) preg_match('/^<([a-zA-Z0-9]+)[^>]*>.*?<\/\1>$/', $trimmed);

            if ($isClosing && $indent > 0) {
                $indent--;
            }

            $result[] = str_repeat($indentStr, $indent) . $trimmed;

            if ( ! $isClosing && ! $isVoid && ! $isSingleLine && preg_match('/^<[a-zA-Z0-9]+[^>]*>/', $trimmed)) {
                $indent++;
            }
        }

        return implode("\n", $result);
    }
}
