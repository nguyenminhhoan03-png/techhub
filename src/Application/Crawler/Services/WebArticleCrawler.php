<?php

declare(strict_types=1);

namespace Application\Crawler\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class WebArticleCrawler
{
    /**
     * Scrape and extract readable content from a webpage URL.
     *
     * @return array{
     *     success: bool,
     *     title: string,
     *     featured_image: string|null,
     *     text_content: string,
     *     domain: string,
     *     word_count: int,
     *     error: string|null
     * }
     */
    public function crawlUrl(string $url): array
    {
        try {
            $parsedUrl = parse_url($url);
            $domain = $parsedUrl['host'] ?? 'N/A';

            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 TechHubBot/2.0',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'vi,en-US;q=0.9,en;q=0.8',
                ])
                ->get($url);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'title' => '',
                    'featured_image' => null,
                    'text_content' => '',
                    'domain' => $domain,
                    'word_count' => 0,
                    'error' => "HTTP error code: {$response->status()}",
                ];
            }

            $html = $response->body();

            // Extract Title
            $title = '';
            if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                $title = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
            } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8');
            }

            // Extract Featured Image
            $featuredImage = null;
            if (preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                $featuredImage = $m[1];
            }

            // Clean HTML to extract pure readable text
            $cleanHtml = preg_replace('/<(script|style|nav|header|footer|aside|svg|noscript)[^>]*>.*?<\/\1>/is', '', $html);
            $text = strip_tags((string) $cleanHtml);
            $text = preg_replace('/\s+/', ' ', (string) $text);
            $text = trim((string) $text);

            $wordCount = str_word_count($text);

            return [
                'success' => true,
                'title' => $title ?: 'Tin tức công nghệ ' . date('d/m/Y'),
                'featured_image' => $featuredImage,
                'text_content' => mb_substr($text, 0, 8000), // Cap at 8,000 chars for prompt safety
                'domain' => $domain,
                'word_count' => $wordCount,
                'error' => null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'title' => '',
                'featured_image' => null,
                'text_content' => '',
                'domain' => parse_url($url, PHP_URL_HOST) ?: 'N/A',
                'word_count' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }
}
