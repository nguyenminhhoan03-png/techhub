<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Services\Compiler;

use DateTimeInterface;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;

class StaticSiteCompilerService
{
    /**
     * Biên dịch một Page thành trang HTML5 độc lập, chuẩn SEO và tối ưu hiệu năng.
     *
     * @param Website $website
     * @param Page $page
     * @param array $astContent
     * @return array{html: string, css: string}
     */
    public function compilePage(Website $website, Page $page, array $astContent): array
    {
        $components = $astContent['components'] ?? [];

        // 1. Kiểm tra nếu là dữ liệu biên dịch trực tiếp từ GrapesJS
        if (!empty($astContent['gjs_html'])) {
            $bodyHtml = $astContent['gjs_html'];
            $compiledCss = $astContent['gjs_css'] ?? '';
            $customCss = $website->settings?->custom_css ?? '';
            $finalCss = $this->minifyCss($compiledCss . "\n" . $customCss);
        } else {
            // 2. Biên dịch cây AST ra Semantic HTML và thu thập style rules
            $cssRules = [
                'desktop' => [],
                'tablet' => [],
                'mobile' => [],
            ];

            $bodyHtml = $this->renderComponents($components, $cssRules);

            // Biên dịch và nén CSS
            $compiledCss = $this->generateCss($cssRules);
            $customCss = $website->settings?->custom_css ?? '';
            $finalCss = $this->minifyCss($compiledCss . "\n" . $customCss);
        }

        // 3. Xây dựng SEO Meta Tags, OpenGraph và Schema Markup
        $headSeoHtml = $this->generateHeadSeo($website, $page);

        // 4. Lắp ráp HTML5 hoàn chỉnh
        $html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {$headSeoHtml}
    <style id="site-styles">
        {$finalCss}
    </style>
    {$website->settings?->custom_js_head}
</head>
<body class="site-body">
    {$bodyHtml}
    {$this->renderBrandingWatermark($website)}
    {$website->settings?->custom_js_body}
</body>
</html>
HTML;

        return [
            'html' => $html,
            'css' => $finalCss,
        ];
    }

    /**
     * Sinh sitemap.xml chuẩn SEO cho toàn bộ trang của website.
     */
    public function generateSitemapXml(Website $website): string
    {
        $domain = "https://{$website->subdomain}.muabanwebsite.io.vn";
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($website->pages as $p) {
            $loc = $domain . ($p->is_home ? '' : "/{$p->slug}");
            $lastmod = $p->updated_at instanceof DateTimeInterface
                ? $p->updated_at->format(DateTimeInterface::ATOM)
                : date(DateTimeInterface::ATOM);
            $priority = $p->is_home ? '1.0' : '0.8';

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";
        return $xml;
    }

    /**
     * Sinh robots.txt chuẩn.
     */
    public function generateRobotsTxt(Website $website): string
    {
        $sitemapUrl = "https://{$website->subdomain}.muabanwebsite.io.vn/sitemap.xml";
        return <<<ROBOTS
User-agent: *
Allow: /

Sitemap: {$sitemapUrl}
ROBOTS;
    }

    /**
     * Render danh sách components đệ quy ra HTML ngữ nghĩa.
     */
    private function renderComponents(array $components, array &$cssRules): string
    {
        $html = '';

        foreach ($components as $node) {
            $html .= $this->renderNode($node, $cssRules);
        }

        return $html;
    }

    /**
     * Render từng node component theo chuẩn HTML5.
     */
    private function renderNode(array $node, array &$cssRules): string
    {
        $id = htmlspecialchars((string) ($node['id'] ?? 'cmp-' . bin2hex(random_bytes(4))));
        $type = (string) ($node['type'] ?? 'container');
        $props = (array) ($node['props'] ?? []);
        $styles = (array) ($node['styles'] ?? []);
        $children = (array) ($node['children'] ?? []);

        // Trích xuất styles cho responsive breakpoints
        if ( ! empty($styles['desktop'])) {
            $cssRules['desktop'][$id] = $styles['desktop'];
        }
        if ( ! empty($styles['tablet'])) {
            $cssRules['tablet'][$id] = $styles['tablet'];
        }
        if ( ! empty($styles['mobile'])) {
            $cssRules['mobile'][$id] = $styles['mobile'];
        }

        $innerHtml = ! empty($children) ? $this->renderComponents($children, $cssRules) : '';

        return match ($type) {
            'section' => $this->renderSection($id, $props, $innerHtml),
            'container' => $this->renderContainer($id, $props, $innerHtml),
            'row' => "<div id=\"{$id}\" class=\"builder-row\">{$innerHtml}</div>",
            'column' => "<div id=\"{$id}\" class=\"builder-column\">{$innerHtml}</div>",
            'heading' => $this->renderHeading($id, $props),
            'text' => $this->renderText($id, $props),
            'image' => $this->renderImage($id, $props),
            'button' => $this->renderButton($id, $props),
            'divider' => "<hr id=\"{$id}\" class=\"builder-divider\" />",
            'video' => $this->renderVideo($id, $props),
            default => "<div id=\"{$id}\" class=\"builder-block\">{$innerHtml}</div>",
        };
    }

    private function renderSection(string $id, array $props, string $innerHtml): string
    {
        $idAttr = ! empty($props['anchor_id'])
            ? ' id="' . htmlspecialchars((string) $props['anchor_id']) . '" data-component-id="' . $id . '"'
            : ' id="' . $id . '"';
        return "<section{$idAttr} class=\"builder-section\">{$innerHtml}</section>";
    }

    private function renderContainer(string $id, array $props, string $innerHtml): string
    {
        $fluidClass = ! empty($props['fluid']) ? 'container-fluid' : 'container';
        return "<div id=\"{$id}\" class=\"builder-container {$fluidClass}\">{$innerHtml}</div>";
    }

    private function renderHeading(string $id, array $props): string
    {
        $tag = in_array($props['tag'] ?? 'h2', ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? $props['tag'] : 'h2';
        $content = htmlspecialchars((string) ($props['content'] ?? ''));
        return "<{$tag} id=\"{$id}\" class=\"builder-heading\">{$content}</{$tag}>";
    }

    private function renderText(string $id, array $props): string
    {
        $content = (string) ($props['content'] ?? '');
        // Sanitize inline html text cho p
        $cleanContent = strip_tags($content, '<b><strong><i><em><u><a><span><br>');
        return "<div id=\"{$id}\" class=\"builder-text\">{$cleanContent}</div>";
    }

    private function renderImage(string $id, array $props): string
    {
        $src = htmlspecialchars((string) ($props['src'] ?? ''));
        $alt = htmlspecialchars((string) ($props['alt'] ?? 'Hình ảnh trên website'));
        $width = ! empty($props['width']) ? ' width="' . (int) $props['width'] . '"' : '';
        $height = ! empty($props['height']) ? ' height="' . (int) $props['height'] . '"' : '';

        // Tối ưu SEO & Performance: Bắt buộc loading="lazy" và decoding="async"
        return "<img id=\"{$id}\" src=\"{$src}\" alt=\"{$alt}\"{$width}{$height} loading=\"lazy\" decoding=\"async\" class=\"builder-img\" />";
    }

    private function renderButton(string $id, array $props): string
    {
        $text = htmlspecialchars((string) ($props['text'] ?? 'Tìm hiểu thêm'));
        $href = htmlspecialchars((string) ($props['href'] ?? '#'));
        $target = htmlspecialchars((string) ($props['target'] ?? '_self'));
        return "<a id=\"{$id}\" href=\"{$href}\" target=\"{$target}\" class=\"builder-btn\">{$text}</a>";
    }

    private function renderVideo(string $id, array $props): string
    {
        $url = htmlspecialchars((string) ($props['url'] ?? ''));
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return "<div id=\"{$id}\" class=\"builder-video-wrapper\"><iframe src=\"{$url}\" frameborder=\"0\" allowfullscreen loading=\"lazy\"></iframe></div>";
        }
        return "<video id=\"{$id}\" src=\"{$url}\" controls class=\"builder-video\"></video>";
    }

    /**
     * Sinh CSS Scoped theo quy tắc Desktop -> Tablet -> Mobile.
     */
    private function generateCss(array $cssRules): string
    {
        // Reset cơ bản để đảm bảo responsive chuẩn
        $css = "*, *::before, *::after { box-sizing: border-box; }\n";
        $css .= "body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Inter, sans-serif; }\n";
        $css .= ".builder-container { width: 100%; max-width: 1200px; margin-left: auto; margin-right: auto; padding-left: 15px; padding-right: 15px; }\n";
        $css .= ".builder-container.container-fluid { max-width: 100%; }\n";
        $css .= ".builder-img { max-width: 100%; height: auto; display: block; }\n";
        $css .= ".builder-btn { display: inline-block; text-decoration: none; cursor: pointer; text-align: center; }\n";

        // Desktop styles (mặc định)
        foreach ($cssRules['desktop'] as $id => $rules) {
            $css .= "#{$id} { " . $this->toCssProperties($rules) . " }\n";
        }

        // Tablet styles (@media max-width: 1023px)
        if ( ! empty($cssRules['tablet'])) {
            $css .= "@media (max-width: 1023px) {\n";
            foreach ($cssRules['tablet'] as $id => $rules) {
                $css .= "  #{$id} { " . $this->toCssProperties($rules) . " }\n";
            }
            $css .= "}\n";
        }

        // Mobile styles (@media max-width: 767px)
        if ( ! empty($cssRules['mobile'])) {
            $css .= "@media (max-width: 767px) {\n";
            foreach ($cssRules['mobile'] as $id => $rules) {
                $css .= "  #{$id} { " . $this->toCssProperties($rules) . " }\n";
            }
            $css .= "}\n";
        }

        return $css;
    }

    private function toCssProperties(array $props): string
    {
        $declarations = [];
        foreach ($props as $key => $val) {
            $sanitizedKey = preg_replace('/[^a-zA-Z0-9\-]/', '', (string) $key);
            $sanitizedVal = htmlspecialchars((string) $val, ENT_NOQUOTES);
            $declarations[] = "{$sanitizedKey}: {$sanitizedVal}";
        }
        return implode('; ', $declarations);
    }

    private function minifyCss(string $css): string
    {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', (string) $css);
        $css = preg_replace('/ {2,}/', ' ', (string) $css);
        $css = preg_replace('/ ?([,:;{}]) ?/', '$1', (string) $css);
        return trim((string) $css);
    }

    /**
     * Sinh thẻ Head chuẩn SEO: Title, Meta Description, OpenGraph, Canonical và Schema.org JSON-LD.
     */
    private function generateHeadSeo(Website $website, Page $page): string
    {
        $seo = $website->seo;
        $pageTitle = htmlspecialchars($page->title);
        $siteName = htmlspecialchars($website->name);
        $metaTitle = htmlspecialchars($seo?->meta_title ?: "{$pageTitle} - {$siteName}");
        $metaDesc = htmlspecialchars($seo?->meta_description ?: "Trang web {$siteName} được tạo chuyên nghiệp bằng muabanwebsite.io.vn");
        $metaKeywords = htmlspecialchars((string) ($seo?->meta_keywords ?? ''));
        $canonicalUrl = "https://{$website->subdomain}.muabanwebsite.io.vn" . ($page->is_home ? '' : "/{$page->slug}");
        $ogImage = htmlspecialchars($seo?->og_image_url ?: 'https://muabanwebsite.io.vn/images/default-og.jpg');
        $favicon = $website->settings?->favicon_url ? "<link rel=\"icon\" href=\"" . htmlspecialchars($website->settings->favicon_url) . "\">" : '';

        // Structured Data (JSON-LD)
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $metaTitle,
            'description' => $metaDesc,
            'url' => $canonicalUrl,
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
            ],
        ];
        $schemaJson = json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return <<<HTML
    <title>{$metaTitle}</title>
    <meta name="description" content="{$metaDesc}">
    <meta name="keywords" content="{$metaKeywords}">
    <link rel="canonical" href="{$canonicalUrl}">
    {$favicon}

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{$canonicalUrl}">
    <meta property="og:title" content="{$metaTitle}">
    <meta property="og:description" content="{$metaDesc}">
    <meta property="og:image" content="{$ogImage}">
    <meta property="og:site_name" content="{$siteName}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{$canonicalUrl}">
    <meta property="twitter:title" content="{$metaTitle}">
    <meta property="twitter:description" content="{$metaDesc}">
    <meta property="twitter:image" content="{$ogImage}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {$schemaJson}
    </script>
HTML;
    }

    private function renderBrandingWatermark(Website $website): string
    {
        if ($website->settings?->remove_branding) {
            return '';
        }

        return <<<HTML
<div style="position: fixed; bottom: 16px; right: 16px; z-index: 999999;">
    <a href="https://muabanwebsite.io.vn" target="_blank" rel="noopener" style="display: flex; align-items: center; gap: 8px; background: #0f172a; color: #ffffff; padding: 8px 14px; border-radius: 9999px; text-decoration: none; font-size: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.1);">
        <span>Tạo bởi</span>
        <span style="color: #38bdf8;">muabanwebsite.io.vn</span>
    </a>
</div>
HTML;
    }
}
