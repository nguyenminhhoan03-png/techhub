<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Seo;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SerpPreviewTool implements ToolContract
{
    public function slug(): string
    {
        return 'serp-preview';
    }

    public function name(): string
    {
        return 'Mô Phỏng Hiển Thị Google SERP Snippet';
    }

    public function categorySlug(): string
    {
        return 'seo';
    }

    public function summary(): string
    {
        return 'Xem trước hiển thị kết quả tìm kiếm Google (Desktop & Mobile), đo độ rộng Pixel và phân tích độ dài chuẩn SEO.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:600'],
            'url' => ['required', 'string', 'max:500'],
            'site_name' => ['nullable', 'string', 'max:100'],
            'device' => ['sometimes', 'string', 'in:desktop,mobile'],
            'date' => ['nullable', 'string', 'max:50'],
            'rating_value' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'rating_count' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);

        $title = trim((string) ($input['title'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $url = trim((string) ($input['url'] ?? 'https://example.com'));
        $siteName = trim((string) ($input['site_name'] ?? ''));
        $device = (string) ($input['device'] ?? 'desktop');
        $date = trim((string) ($input['date'] ?? ''));
        $ratingValue = isset($input['rating_value']) && '' !== $input['rating_value'] ? (float) $input['rating_value'] : null;
        $ratingCount = isset($input['rating_count']) && '' !== $input['rating_count'] ? (int) $input['rating_count'] : null;

        // Parse Domain & Breadcrumb URL
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? 'example.com';
        $path = trim($parsedUrl['path'] ?? '', '/');
        
        $breadcrumbParts = [$host];
        if (!empty($siteName)) {
            $breadcrumbDisplay = $siteName . ' › ' . ($path ? str_replace('/', ' › ', $path) : '');
        } else {
            if (!empty($path)) {
                $pathSegments = explode('/', $path);
                $breadcrumbDisplay = $host . ' › ' . implode(' › ', $pathSegments);
            } else {
                $breadcrumbDisplay = $host;
            }
        }
        $breadcrumbDisplay = rtrim($breadcrumbDisplay, ' › ');

        // Pixel Width Estimator (Avg char width for Google Sans / Arial 20px for Title ~ 9.5px, 14px for Desc ~ 6.8px)
        $titleCharCount = mb_strlen($title);
        $titlePixelEst = (int) round($this->estimatePixelWidth($title, 9.6));

        $descCharCount = mb_strlen($description);
        $descPixelEst = (int) round($this->estimatePixelWidth($description, 6.7));

        $maxTitlePixels = ('mobile' === $device) ? 550 : 600;
        $maxDescPixels = ('mobile' === $device) ? 680 : 960;

        // Truncate Simulation
        $isTitleTruncated = $titlePixelEst > $maxTitlePixels || $titleCharCount > 60;
        $truncatedTitle = $isTitleTruncated ? mb_substr($title, 0, 58) . '...' : $title;

        $isDescTruncated = $descPixelEst > $maxDescPixels || $descCharCount > 160;
        $truncatedDesc = $isDescTruncated ? mb_substr($description, 0, 155) . '...' : $description;

        $isEn = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'en'
            : false;

        // SEO Health Checks
        $titleStatus = 'optimal';
        $titleMessage = $isEn ? 'Perfect title length for Google search results.' : 'Độ dài tiêu đề hoàn hảo cho kết quả tìm kiếm.';
        if ($titleCharCount < 30) {
            $titleStatus = 'too_short';
            $titleMessage = $isEn ? 'Title is too short (< 30 characters), underutilizing display space.' : 'Tiêu đề quá ngắn (< 30 ký tự), chưa tận dụng hết không gian hiển thị.';
        } elseif ($isTitleTruncated) {
            $titleStatus = 'too_long';
            $titleMessage = $isEn ? 'Title exceeds display limit and may be truncated with "..." on Google.' : 'Tiêu đề vượt quá giới hạn hiển thị, có thể bị cắt bớt dấu "..." trên Google.';
        }

        $descStatus = 'optimal';
        $descMessage = $isEn ? 'Optimal description length, providing enough information to drive clicks.' : 'Độ dài mô tả chuẩn SEO, cung cấp đầy đủ thông tin thu hút click.';
        if ($descCharCount < 70) {
            $descStatus = 'too_short';
            $descMessage = $isEn ? 'Description is too short (< 70 characters), unlikely to entice user clicks.' : 'Mô tả quá ngắn (< 70 ký tự), khó kích thích người dùng nhấp vào kết quả.';
        } elseif ($isDescTruncated) {
            $descStatus = 'too_long';
            $descMessage = $isEn ? 'Description exceeds display limit (~160 characters), trailing portion will be truncated.' : 'Mô tả vượt quá giới hạn hiển thị (~160 ký tự), phần cuối sẽ bị ẩn.';
        }

        $overallScore = 100;
        if ('optimal' !== $titleStatus) $overallScore -= 20;
        if ('optimal' !== $descStatus) $overallScore -= 20;
        if (empty($url) || 'https://example.com' === $url) $overallScore -= 10;

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'input' => [
                'title' => $title,
                'description' => $description,
                'url' => $url,
                'site_name' => $siteName,
                'device' => $device,
                'date' => $date,
                'rating_value' => $ratingValue,
                'rating_count' => $ratingCount,
            ],
            'preview' => [
                'display_url' => $url,
                'breadcrumb' => $breadcrumbDisplay,
                'host' => $host,
                'display_title' => $truncatedTitle,
                'full_title' => $title,
                'display_description' => $truncatedDesc,
                'full_description' => $description,
                'is_title_truncated' => $isTitleTruncated,
                'is_desc_truncated' => $isDescTruncated,
                'device' => $device,
                'date' => $date,
                'rating_value' => $ratingValue,
                'rating_count' => $ratingCount,
            ],
            'metrics' => [
                'title' => [
                    'char_count' => $titleCharCount,
                    'pixel_est' => $titlePixelEst,
                    'max_pixels' => $maxTitlePixels,
                    'status' => $titleStatus,
                    'message' => $titleMessage,
                ],
                'description' => [
                    'char_count' => $descCharCount,
                    'pixel_est' => $descPixelEst,
                    'max_pixels' => $maxDescPixels,
                    'status' => $descStatus,
                    'message' => $descMessage,
                ],
                'seo_score' => max(0, $overallScore),
            ],
        ], executionTimeMs: $executionTimeMs);
    }

    /**
     * Approximate character width in pixels based on character casing and wide letters.
     */
    private function estimatePixelWidth(string $text, float $avgWidth): float
    {
        $totalWidth = 0.0;
        $len = mb_strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            if (preg_match('/[WMmwQ@#%&]/', $char)) {
                $totalWidth += $avgWidth * 1.4;
            } elseif (preg_match('/[ijlIt!.,:;\'|]/', $char)) {
                $totalWidth += $avgWidth * 0.45;
            } elseif (preg_match('/[A-Z]/', $char)) {
                $totalWidth += $avgWidth * 1.15;
            } elseif (' ' === $char) {
                $totalWidth += $avgWidth * 0.5;
            } else {
                $totalWidth += $avgWidth;
            }
        }

        return $totalWidth;
    }
}
