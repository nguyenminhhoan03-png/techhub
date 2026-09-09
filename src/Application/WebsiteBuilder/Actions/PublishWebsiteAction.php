<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Actions;

use Application\WebsiteBuilder\Services\Compiler\StaticSiteCompilerService;
use Application\WebsiteBuilder\Services\Storage\WebsiteStorageService;
use Domain\WebsiteBuilder\Entities\PageVersion;
use Domain\WebsiteBuilder\Entities\PublishedSite;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Enums\PageStatus;
use Domain\WebsiteBuilder\Enums\PublishedSiteStatus;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublishWebsiteAction
{
    public function __construct(
        protected StaticSiteCompilerService $compiler,
        protected WebsiteStorageService $storage,
    ) {}

    /**
     * Xuất bản toàn bộ website thành các file tĩnh SSG lưu trên S3/CDN.
     */
    public function execute(Website $website, int $deployedByUserId): PublishedSite
    {
        return DB::transaction(function () use ($website, $deployedByUserId): PublishedSite {
            $releaseUlid = (string) Str::ulid();
            $versionTag = 'v' . date('Ymd-His');
            $releasePrefix = "sites/{$website->id}/releases/{$releaseUlid}";
            $manifest = [];

            $website->loadMissing(['pages', 'settings', 'seo']);

            // 1. Biên dịch từng Page và đẩy lên S3
            foreach ($website->pages as $page) {
                $compiled = $this->compiler->compilePage($website, $page, $page->draft_content);

                // Tên file HTML: trang chủ là index.html, các trang khác là {slug}.html
                $fileName = $page->is_home ? 'index.html' : "{$page->slug}.html";
                $fileUrl = $this->storage->putReleaseFile($releasePrefix, $fileName, $compiled['html'], 'text/html; charset=UTF-8');

                $manifest['pages'][] = [
                    'page_id' => $page->id,
                    'title' => $page->title,
                    'file' => $fileName,
                    'url' => $fileUrl,
                ];

                // 2. Tạo bản chụp bất biến (Immutable Page Version) để lưu lịch sử
                PageVersion::create([
                    'page_id' => $page->id,
                    'version_number' => $page->version_number,
                    'content_json' => $page->draft_content,
                    'styles_json' => ['css' => $compiled['css']],
                    'commit_message' => "Xuất bản phiên bản {$versionTag}",
                    'created_by' => $deployedByUserId,
                ]);

                // Cập nhật trạng thái trang thành published
                $page->update(['status' => PageStatus::PUBLISHED]);
            }

            // 3. Tự động sinh sitemap.xml và robots.txt chuẩn SEO đưa lên S3
            $sitemapXml = $this->compiler->generateSitemapXml($website);
            $sitemapUrl = $this->storage->putReleaseFile($releasePrefix, 'sitemap.xml', $sitemapXml, 'application/xml; charset=UTF-8');
            $manifest['sitemap'] = $sitemapUrl;

            $robotsTxt = $this->compiler->generateRobotsTxt($website);
            $robotsUrl = $this->storage->putReleaseFile($releasePrefix, 'robots.txt', $robotsTxt, 'text/plain; charset=UTF-8');
            $manifest['robots'] = $robotsUrl;

            // 4. Tạo bản ghi PublishedSite
            $publishedSite = PublishedSite::create([
                'ulid' => $releaseUlid,
                'website_id' => $website->id,
                'version_tag' => $versionTag,
                'storage_directory' => $releasePrefix,
                'manifest_json' => $manifest,
                'status' => PublishedSiteStatus::LIVE,
                'deployed_by' => $deployedByUserId,
            ]);

            // 5. Atomic Switch pointer trên Website
            $website->update([
                'status' => WebsiteStatus::PUBLISHED,
                'active_version_id' => $publishedSite->id,
                'published_at' => now(),
            ]);

            return $publishedSite;
        });
    }
}
