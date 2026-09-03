<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Seo;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class OpenGraphGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'open-graph-generator';
    }

    public function name(): string
    {
        return 'Tạo Thẻ Open Graph & Twitter Cards';
    }

    public function categorySlug(): string
    {
        return 'seo';
    }

    public function summary(): string
    {
        return 'Tạo thẻ chia sẻ mạng xã hội (Facebook Open Graph, Twitter/X Card, LinkedIn) và mô phỏng giao diện xem trước.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'image_url' => ['required', 'string', 'max:500'],
            'url' => ['required', 'string', 'max:500'],
            'site_name' => ['nullable', 'string', 'max:150'],
            'og_type' => ['sometimes', 'string', 'in:website,article,product,profile,video.other'],
            'twitter_card' => ['sometimes', 'string', 'in:summary_large_image,summary,app,player'],
            'twitter_site' => ['nullable', 'string', 'max:100'],
            'twitter_creator' => ['nullable', 'string', 'max:100'],
            'fb_app_id' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);

        $title = trim((string) ($input['title'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $imageUrl = trim((string) ($input['image_url'] ?? ''));
        $url = trim((string) ($input['url'] ?? 'https://example.com'));
        $siteName = trim((string) ($input['site_name'] ?? 'TechHub'));
        $ogType = (string) ($input['og_type'] ?? 'website');
        $twitterCard = (string) ($input['twitter_card'] ?? 'summary_large_image');
        $twitterSite = trim((string) ($input['twitter_site'] ?? ''));
        $twitterCreator = trim((string) ($input['twitter_creator'] ?? ''));
        $fbAppId = trim((string) ($input['fb_app_id'] ?? ''));

        // Format Twitter Handles with @ if missing
        if (!empty($twitterSite) && !str_starts_with($twitterSite, '@')) {
            $twitterSite = '@' . $twitterSite;
        }
        if (!empty($twitterCreator) && !str_starts_with($twitterCreator, '@')) {
            $twitterCreator = '@' . $twitterCreator;
        }

        $lines = [];
        $lines[] = '<!-- Open Graph / Facebook / LinkedIn Meta Tags -->';
        $lines[] = '<meta property="og:type" content="' . htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta property="og:url" content="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta property="og:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta property="og:description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta property="og:image" content="' . htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta property="og:image:width" content="1200">';
        $lines[] = '<meta property="og:image:height" content="630">';

        if (!empty($siteName)) {
            $lines[] = '<meta property="og:site_name" content="' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '">';
        }

        if (!empty($fbAppId)) {
            $lines[] = '<meta property="fb:app_id" content="' . htmlspecialchars($fbAppId, ENT_QUOTES, 'UTF-8') . '">';
        }

        $lines[] = '';
        $lines[] = '<!-- Twitter / X Meta Tags -->';
        $lines[] = '<meta name="twitter:card" content="' . htmlspecialchars($twitterCard, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta name="twitter:url" content="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta name="twitter:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta name="twitter:description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '">';
        $lines[] = '<meta name="twitter:image" content="' . htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') . '">';

        if (!empty($twitterSite)) {
            $lines[] = '<meta name="twitter:site" content="' . htmlspecialchars($twitterSite, ENT_QUOTES, 'UTF-8') . '">';
        }

        if (!empty($twitterCreator)) {
            $lines[] = '<meta name="twitter:creator" content="' . htmlspecialchars($twitterCreator, ENT_QUOTES, 'UTF-8') . '">';
        }

        $metaHtml = implode("\n", $lines);

        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['host'] ?? 'example.com';

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $metaHtml,
            'meta_html' => $metaHtml,
            'preview' => [
                'title' => $title,
                'description' => $description,
                'image_url' => $imageUrl,
                'url' => $url,
                'domain' => $domain,
                'site_name' => $siteName,
                'twitter_card' => $twitterCard,
                'twitter_site' => $twitterSite,
            ],
            'audit' => [
                'has_og_image' => !empty($imageUrl),
                'og_image_recommendation' => ((class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication()) && \Illuminate\Support\Facades\App::getLocale() === 'en')
                    ? 'Recommended size: 1200 x 630 px (1.91:1 ratio) for crisp display on all social feeds.'
                    : 'Kích thước khuyên dùng: 1200 x 630 px (Tỷ lệ 1.91:1) để hiển thị sắc nét nhất.',
                'title_length' => mb_strlen($title),
                'description_length' => mb_strlen($description),
            ],
        ], executionTimeMs: $executionTimeMs);
    }
}
