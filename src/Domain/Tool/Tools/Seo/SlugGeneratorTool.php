<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Seo;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SlugGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'slug-generator';
    }

    public function name(): string
    {
        return 'Tạo URL Slug Chuẩn SEO (Lọc Stop Words)';
    }

    public function categorySlug(): string
    {
        return 'seo';
    }

    public function summary(): string
    {
        return 'Chuyển đổi tiêu đề tiếng Việt sang slug URL chuẩn SEO, lọc từ dừng (Stop words) và đánh giá độ chuẩn SEO của URL.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'text' => ['required', 'string', 'max:500'],
            'separator' => ['sometimes', 'string', 'in:-,_'],
            'remove_stop_words' => ['sometimes', 'boolean'],
            'max_length' => ['sometimes', 'integer', 'min:20', 'max:200'],
            'case_format' => ['sometimes', 'string', 'in:lowercase,uppercase,camel,pascal,snake,kebab'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);

        $text = trim((string) ($input['text'] ?? ''));
        $separator = (string) ($input['separator'] ?? '-');
        $removeStopWords = !empty($input['remove_stop_words']);
        $maxLength = (int) ($input['max_length'] ?? 80);
        $caseFormat = (string) ($input['case_format'] ?? 'lowercase');

        // 1. Convert Vietnamese Accented characters to Plain ASCII
        $ascii = $this->removeVietnameseDiacritics($text);

        // 2. Remove emojis and special punctuation except letters and numbers
        $clean = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $ascii);
        $clean = (string) preg_replace('/\s+/', ' ', (string) $clean);

        // 3. Extract words
        $words = array_filter(explode(' ', strtolower($clean)));

        // 4. Filter Stop Words (Vietnamese & English)
        $removedWords = [];
        if ($removeStopWords) {
            $stopWords = [
                'va', 'la', 'cho', 'cua', 'o', 'tai', 'trong', 'voi', 'cac', 'nhung',
                'mot', 've', 'khi', 'duoc', 'nay', 'do', 'nhu', 'de', 'co', 'ra',
                'theo', 'tu', 'den', 'se', 'da', 'dang', 'tren', 'duoi',
                'a', 'an', 'the', 'and', 'or', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'it', 'this', 'that'
            ];

            $filteredWords = [];
            foreach ($words as $w) {
                if (in_array($w, $stopWords, true)) {
                    $removedWords[] = $w;
                } else {
                    $filteredWords[] = $w;
                }
            }
            if (!empty($filteredWords)) {
                $words = $filteredWords;
            }
        }

        // 5. Generate Slug based on Case Format
        $slug = '';
        if ('camel' === $caseFormat) {
            $slug = lcfirst(implode('', array_map('ucfirst', $words)));
        } elseif ('pascal' === $caseFormat) {
            $slug = implode('', array_map('ucfirst', $words));
        } elseif ('snake' === $caseFormat) {
            $slug = implode('_', $words);
        } elseif ('uppercase' === $caseFormat) {
            $slug = strtoupper(implode($separator, $words));
        } else {
            // default kebab / lowercase
            $slug = implode($separator, $words);
        }

        // 6. Enforce Max Length cleanly without cutting words
        if (mb_strlen($slug) > $maxLength) {
            $truncated = mb_substr($slug, 0, $maxLength);
            $lastSep = mb_strrpos($truncated, $separator);
            if (false !== $lastSep && $lastSep > 20) {
                $slug = mb_substr($truncated, 0, $lastSep);
            } else {
                $slug = $truncated;
            }
        }

        // 7. Alternative format previews
        $kebabSlug = implode('-', $words);
        $snakeSlug = implode('_', $words);
        $camelSlug = lcfirst(implode('', array_map('ucfirst', $words)));

        // 8. SEO Health Analysis
        $slugLen = mb_strlen($slug);
        $wordCount = count($words);
        $seoScore = 100;
        $recommendations = [];

        $isEn = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'en'
            : false;

        if ($slugLen > 75) {
            $seoScore -= 20;
            $recommendations[] = $isEn ? 'Slug is a bit long (> 75 chars), consider shortening for bot readability and easy sharing.' : 'Slug hơi dài (> 75 ký tự), nên rút gọn để Bot dễ hiểu và thân thiện khi chia sẻ.';
        } elseif ($slugLen < 10) {
            $seoScore -= 15;
            $recommendations[] = $isEn ? 'Slug is very short, it might not contain complete target keywords.' : 'Slug quá ngắn, có thể chưa chứa đầy đủ từ khóa chính.';
        }

        if ($wordCount > 8) {
            $seoScore -= 15;
            $recommendations[] = $isEn ? 'Number of words in URL (> 8 words) may dilute SEO keyword density.' : 'Số từ trong URL (> 8 từ) có thể làm loãng mật độ từ khóa SEO.';
        }

        if (empty($recommendations)) {
            $recommendations[] = $isEn ? 'Perfect length and friendly URL structure for search engine crawlers.' : 'Độ dài hoàn hảo, cấu trúc URL thân thiện với thuật toán tìm kiếm của Google.';
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $slug,
            'slug' => $slug,
            'original_text' => $text,
            'word_count' => $wordCount,
            'char_count' => $slugLen,
            'removed_stop_words' => $removedWords,
            'alternatives' => [
                'kebab_case' => $kebabSlug,
                'snake_case' => $snakeSlug,
                'camel_case' => $camelSlug,
                'url_example' => 'https://example.com/' . $slug,
            ],
            'health_score' => max(0, $seoScore),
            'recommendations' => $recommendations,
        ], executionTimeMs: $executionTimeMs);
    }

    /**
     * Map accented Vietnamese letters to standard Latin ASCII.
     */
    private function removeVietnameseDiacritics(string $str): string
    {
        $unicodeMap = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];

        foreach ($unicodeMap as $plain => $pattern) {
            $str = (string) preg_replace("/($pattern)/iu", $plain, $str);
        }

        return $str;
    }
}
