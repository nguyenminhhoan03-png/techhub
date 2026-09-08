<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class CssMinifierTool implements ToolContract
{
    public function slug(): string
    {
        return 'css-minifier';
    }

    public function name(): string
    {
        return 'CSS Minifier & Beautifier';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Minify and optimize stylesheets by stripping comments and redundant whitespace, or beautify compacted CSS code.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'css' => ['required', 'string'],
            'action' => ['sometimes', 'string', 'in:minify,beautify'],
            'indent_size' => ['sometimes', 'integer', 'in:2,4'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawCss = trim((string) ($input['css'] ?? ''));
        $action = (string) ($input['action'] ?? 'minify');
        $indentSize = (int) ($input['indent_size'] ?? 2);
        $indent = str_repeat(' ', $indentSize);

        if (empty($rawCss)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('CSS content cannot be empty.', $executionTimeMs);
        }

        $origLen = mb_strlen($rawCss);

        if ('beautify' === $action) {
            $beautified = $this->beautifyCss($rawCss, $indent);
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => $beautified,
                'action' => 'beautify',
                'original_size_bytes' => $origLen,
                'result_size_bytes' => mb_strlen($beautified),
            ], executionTimeMs: $executionTimeMs);
        }

        // Minify CSS
        $minified = $this->minifyCss($rawCss);
        $minLen = mb_strlen($minified);
        $savedPct = $origLen > 0 ? round((($origLen - $minLen) / $origLen) * 100, 2) : 0;

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $minified,
            'action' => 'minify',
            'original_size_bytes' => $origLen,
            'minified_size_bytes' => $minLen,
            'saved_percentage' => $savedPct,
        ], executionTimeMs: $executionTimeMs);
    }

    private function minifyCss(string $css): string
    {
        // 1. Remove comments
        $css = (string) preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

        // 2. Normalize whitespace
        $css = (string) preg_replace('/\s+/', ' ', $css);

        // 3. Remove space around delimiters: { } : ; ,
        $css = (string) preg_replace('/\s*([{}|:;,>~+])\s*/', '$1', $css);

        // 4. Remove unnecessary trailing semicolons
        $css = (string) preg_replace('/;}/', '}', $css);

        // 5. Zero values reduction: 0px -> 0
        $css = (string) preg_replace('/(?<=[\s:])0(?:px|em|rem|%|in|cm|mm|pc|pt)/i', '0', $css);

        // 6. Shorten 6-character hex colors: #ffffff -> #fff
        $css = (string) preg_replace('/#([a-f0-9])\1([a-f0-9])\2([a-f0-9])\3(?=[^\w]|$)/i', '#$1$2$3', $css);

        return trim($css);
    }

    private function beautifyCss(string $css, string $indent): string
    {
        // First compress slightly to normalize
        $css = $this->minifyCss($css);

        // Then expand blocks
        $css = str_replace('{', " {\n{$indent}", $css);
        $css = str_replace(';', ";\n{$indent}", $css);
        $css = str_replace('}', "\n}\n\n", $css);
        $css = (string) preg_replace("/\n\s*;\n/m", "\n", $css);
        $css = str_replace(':', ': ', $css);

        return trim($css);
    }
}
