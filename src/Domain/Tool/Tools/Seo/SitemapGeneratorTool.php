<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Seo;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SitemapGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'sitemap-generator';
    }

    public function name(): string
    {
        return 'Tạo Sơ Đồ Trang Web XML Sitemap';
    }

    public function categorySlug(): string
    {
        return 'seo';
    }

    public function summary(): string
    {
        return 'Tạo và kiểm tra tệp sơ đồ trang web XML Sitemap chuẩn Sitemaps.org (hỗ trợ Priority, Changefreq, Lastmod).';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'base_url' => ['required', 'string', 'max:300'],
            'urls_list' => ['required', 'string', 'max:15000'],
            'default_changefreq' => ['sometimes', 'string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
            'default_priority' => ['sometimes', 'numeric', 'min:0.0', 'max:1.0'],
            'include_lastmod' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);

        $baseUrl = rtrim(trim((string) ($input['base_url'] ?? 'https://example.com')), '/');
        $rawUrls = (string) ($input['urls_list'] ?? '');
        $defaultChangefreq = (string) ($input['default_changefreq'] ?? 'weekly');
        $defaultPriority = isset($input['default_priority']) ? number_format((float) $input['default_priority'], 1) : '0.8';
        $includeLastmod = !empty($input['include_lastmod']);
        $currentIsoDate = date('Y-m-d');

        $lines = array_filter(array_map('trim', explode("\n", $rawUrls)));
        
        $urlEntries = [];
        foreach ($lines as $line) {
            if (empty($line) || str_starts_with($line, '#')) continue;

            // Support line formats: "/path" OR "https://full.url" OR "/path 0.9 daily"
            $parts = preg_split('/\s+/', $line);
            $urlPart = $parts[0] ?? '/';
            $priority = isset($parts[1]) && is_numeric($parts[1]) ? number_format((float) $parts[1], 1) : $defaultPriority;
            $changefreq = isset($parts[2]) && in_array($parts[2], ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'], true) ? $parts[2] : $defaultChangefreq;

            if (str_starts_with($urlPart, 'http://') || str_starts_with($urlPart, 'https://')) {
                $fullUrl = $urlPart;
            } else {
                $fullUrl = $baseUrl . '/' . ltrim($urlPart, '/');
            }

            // High priority for homepage
            if ($fullUrl === $baseUrl || $fullUrl === $baseUrl . '/') {
                $priority = '1.0';
                $changefreq = 'daily';
            }

            $urlEntries[] = [
                'loc' => $fullUrl,
                'lastmod' => $currentIsoDate,
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        }

        // Build XML output
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($urlEntries as $entry) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($entry['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            if ($includeLastmod) {
                $xml .= "    <lastmod>" . $entry['lastmod'] . "</lastmod>\n";
            }
            $xml .= "    <changefreq>" . $entry['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $entry['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $xml,
            'xml_sitemap' => $xml,
            'urls_count' => count($urlEntries),
            'size_bytes' => mb_strlen($xml),
            'size_formatted' => round(mb_strlen($xml) / 1024, 2) . ' KB',
            'is_valid_xml' => true,
            'entries_preview' => array_slice($urlEntries, 0, 10),
        ], executionTimeMs: $executionTimeMs);
    }
}
