<?php

declare(strict_types=1);

namespace Application\Ai\Services;

use Domain\Article\Entities\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LlmJsonSanitizer
{
    /**
     * Senior Resilient JSON Extractor for AI-generated articles.
     * Safely parses JSON even when LLMs output unescaped newlines, trailing commas, or markdown fences.
     * Guaranteed never to return '```json' or code blocks as title.
     *
     * @return array{
     *     title: string,
     *     excerpt: string,
     *     content_markdown: string,
     *     faqs: list<array{question: string, answer: string}>
     * }
     */
    public static function parseArticleJson(string $raw, string $fallbackTitle = ''): array
    {
        $raw = trim($raw);

        // 1. Extract JSON candidate between outermost braces { ... }
        $candidate = $raw;
        if (preg_match('/\{[\s\S]*\}/', $raw, $matches)) {
            $candidate = $matches[0];
        }

        $parsed = null;

        // Attempt 1: Direct JSON decode
        $data = json_decode($candidate, true);
        if (is_array($data) && (isset($data['title']) || isset($data['content_markdown']))) {
            $parsed = $data;
        }

        // Attempt 2: Fix unescaped control characters in JSON string values
        if (! is_array($parsed)) {
            $sanitized = self::fixControlCharacters($candidate);
            $data = json_decode($sanitized, true);
            if (is_array($data) && (isset($data['title']) || isset($data['content_markdown']))) {
                $parsed = $data;
            }

            // Attempt 3: Fix trailing commas
            if (! is_array($parsed)) {
                $sanitizedNoCommas = self::fixTrailingCommas($sanitized);
                $data = json_decode($sanitizedNoCommas, true);
                if (is_array($data) && (isset($data['title']) || isset($data['content_markdown']))) {
                    $parsed = $data;
                }
            }
        }

        // Attempt 4: Resilient regex field extraction (when json_decode fails completely)
        if (! is_array($parsed)) {
            $extractedTitle = null;
            $extractedExcerpt = null;
            $extractedContent = null;
            $extractedFaqs = [];

            if (preg_match('/"title"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $raw, $m)) {
                $extractedTitle = stripcslashes($m[1]);
            }
            if (preg_match('/"excerpt"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $raw, $m)) {
                $extractedExcerpt = stripcslashes($m[1]);
            }

            if (preg_match('/"content_markdown"\s*:\s*"(.*?)"\s*,\s*"faqs"/s', $raw, $m)) {
                $extractedContent = stripcslashes($m[1]);
            } elseif (preg_match('/"content_markdown"\s*:\s*"(.*?)"\s*(?:,\s*"[a-zA-Z0-9_]+"\s*:|\}\s*```?$)/s', $raw, $m)) {
                $extractedContent = stripcslashes($m[1]);
            }

            if (preg_match('/"faqs"\s*:\s*\[([\s\S]*?)\]/s', $raw, $m)) {
                if (preg_match_all('/\{\s*"question"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"\s*,\s*"answer"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"\s*\}/s', $m[1], $faqMatches, PREG_SET_ORDER)) {
                    foreach ($faqMatches as $fm) {
                        $extractedFaqs[] = [
                            'question' => stripcslashes($fm[1]),
                            'answer' => stripcslashes($fm[2]),
                        ];
                    }
                }
            }

            if ($extractedTitle || $extractedContent) {
                $parsed = [
                    'title' => $extractedTitle,
                    'excerpt' => $extractedExcerpt,
                    'content_markdown' => $extractedContent,
                    'faqs' => $extractedFaqs,
                ];
            }
        }

        // Attempt 5: Non-JSON raw markdown response (LLM refused JSON instructions)
        if (! is_array($parsed)) {
            $cleanText = preg_replace('/^```(?:markdown|json|md)?\s*/i', '', $raw);
            $cleanText = preg_replace('/\s*```$/', '', (string) $cleanText);

            $lines = explode("\n", (string) $cleanText);
            $extractedTitle = null;
            $contentLines = [];
            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                if (empty($trimmedLine) || str_starts_with($trimmedLine, '```') || str_starts_with($trimmedLine, '{')) {
                    continue;
                }
                if ($extractedTitle === null) {
                    $extractedTitle = trim(ltrim($trimmedLine, "# \t\r"));
                } else {
                    $contentLines[] = $line;
                }
            }

            $parsed = [
                'title' => $extractedTitle,
                'excerpt' => null,
                'content_markdown' => implode("\n", $contentLines) ?: $cleanText,
                'faqs' => [],
            ];
        }

        // STRICT TITLE SANITIZATION & GUARD
        $title = trim((string) ($parsed['title'] ?? ''));
        if (empty($title)
            || str_starts_with($title, '```')
            || str_starts_with($title, '{')
            || str_starts_with($title, '[')
            || str_starts_with($title, '"title"')
            || strtolower($title) === 'json'
            || strtolower($title) === 'markdown'
            || strlen($title) < 5
        ) {
            $title = $fallbackTitle ?: 'Phân Tích Xu Hướng Công Nghệ Mới Nhất';
        }
        $title = trim(ltrim($title, "#` \t\r\n"));

        // STRICT CONTENT SANITIZATION & GUARD
        $content = trim((string) ($parsed['content_markdown'] ?? ''));
        if (str_starts_with($content, '```json') || (str_starts_with($content, '{') && str_contains($content, '"content_markdown"'))) {
            if (preg_match('/"content_markdown"\s*:\s*"(.*?)"(?:\s*,\s*"faqs"|\s*\})/s', $content, $cm)) {
                $content = stripcslashes($cm[1]);
            } else {
                $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
                $content = preg_replace('/\s*```$/', '', (string) $content);
            }
        }

        $excerpt = trim((string) ($parsed['excerpt'] ?? ''));
        if (empty($excerpt) || str_starts_with($excerpt, '```') || str_starts_with($excerpt, '{')) {
            $cleanSnippet = strip_tags($content);
            $excerpt = mb_substr($cleanSnippet, 0, 160) . '...';
        }

        return [
            'title' => $title,
            'excerpt' => $excerpt,
            'content_markdown' => $content,
            'faqs' => array_values((array) ($parsed['faqs'] ?? [])),
        ];
    }

    /**
     * Resilient JSON parser for hardware comparisons.
     */
    public static function parseComparisonJson(string $raw, string $nameA = '', string $nameB = ''): ?array
    {
        $raw = trim($raw);

        $candidate = $raw;
        if (preg_match('/\{[\s\S]*\}/', $raw, $matches)) {
            $candidate = $matches[0];
        }

        $parsed = json_decode($candidate, true);

        if (! is_array($parsed)) {
            $sanitized = self::fixControlCharacters($candidate);
            $parsed = json_decode($sanitized, true);

            if (! is_array($parsed)) {
                $sanitizedNoCommas = self::fixTrailingCommas($sanitized);
                $parsed = json_decode($sanitizedNoCommas, true);
            }
        }

        if (is_array($parsed) && isset($parsed['title'], $parsed['content_markdown'])) {
            $title = trim((string) $parsed['title']);
            if (str_starts_with($title, '```') || strtolower($title) === 'json') {
                $title = "So Sánh {$nameA} vs {$nameB}: Đâu Là Lựa Chọn Tối Ưu?";
            }
            $parsed['title'] = $title;
            return $parsed;
        }

        return null;
    }

    /**
     * Auto-heals corrupted articles where LLM raw JSON was mistakenly saved into title or content.
     */
    public static function cleanCorruptedArticle(Article $article): bool
    {
        $title = trim((string) $article->title);
        $content = trim((string) $article->content_markdown);
        $slug = trim((string) $article->slug);

        $isCorrupted = false;

        if (str_starts_with($title, '```')
            || str_starts_with($title, '{')
            || strtolower($title) === 'json'
            || strtolower($slug) === 'json'
            || str_starts_with($slug, 'json-')
            || str_contains($slug, '`')
        ) {
            $isCorrupted = true;
        }

        if (str_starts_with($content, '```json')
            || (str_starts_with($content, '{') && str_contains($content, '"title"') && str_contains($content, '"content_markdown"'))
        ) {
            $isCorrupted = true;
        }

        if (! $isCorrupted) {
            return false;
        }

        Log::warning("Auto-healing corrupted article ID {$article->id} ('{$article->title}')");

        $parsed = self::parseArticleJson($content, 'Bài Viết Phân Tích Công Nghệ TechHub');

        if (empty($parsed['content_markdown'])) {
            return false;
        }

        $newTitle = $parsed['title'];
        if (empty($newTitle) || str_starts_with($newTitle, '```') || strtolower($newTitle) === 'json') {
            $newTitle = 'Bài Viết Phân Tích Công Nghệ';
        }

        $newSlug = Str::slug($newTitle);
        $existing = Article::query()->where('slug', $newSlug)->where('id', '!=', $article->id)->count();
        if ($existing > 0) {
            $newSlug .= '-' . ($existing + 1);
        }

        $article->title = $newTitle;
        $article->slug = $newSlug;
        $article->excerpt = $parsed['excerpt'];
        $article->content_markdown = $parsed['content_markdown'];
        $article->content_html = nl2br(htmlspecialchars($parsed['content_markdown'], ENT_QUOTES, 'UTF-8'));
        $article->meta_title = $newTitle . ' — TechHub';
        $article->meta_description = $parsed['excerpt'];

        if (! empty($parsed['faqs'])) {
            $article->schema_markup = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($f): array => [
                    '@type' => 'Question',
                    'name' => $f['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $f['answer'],
                    ],
                ], (array) $parsed['faqs']),
            ];
        }

        $article->save();

        return true;
    }

    /**
     * Replaces unescaped control characters inside JSON double-quoted string literals.
     */
    protected static function fixControlCharacters(string $json): string
    {
        return (string) preg_replace_callback('/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', function ($m) {
            return str_replace(
                ["\r\n", "\r", "\n", "\t"],
                ['\n', '\n', '\n', '\t'],
                $m[0]
            );
        }, $json);
    }

    /**
     * Removes trailing commas before closing braces/brackets.
     */
    protected static function fixTrailingCommas(string $json): string
    {
        return (string) preg_replace('/,\s*([\}\]])/', '$1', $json);
    }
}
