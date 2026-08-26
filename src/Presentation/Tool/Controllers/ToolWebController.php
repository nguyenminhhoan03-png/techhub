<?php

declare(strict_types=1);

namespace Presentation\Tool\Controllers;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Repositories\ToolRepositoryContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Presentation\Controller;
use Shared\Infrastructure\Http\Middleware\SetLocaleMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToolWebController extends Controller
{
    public function __construct(private readonly ToolRepositoryContract $toolRepository) {}

    public function setLocale(string $locale, Request $request): RedirectResponse
    {
        if (in_array($locale, SetLocaleMiddleware::SUPPORTED_LOCALES, true)) {
            $request->session()->put('locale', $locale);
            cookie()->queue(cookie()->forever('locale', $locale));
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        $previousUrl = $request->headers->get('referer') ?: url()->previous();

        if (!empty($previousUrl)) {
            $parsed = parse_url($previousUrl);
            $queryParams = [];
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $queryParams);
                // Strip existing conflicting 'lang' query parameter from the redirect target URL
                unset($queryParams['lang']);
            }

            $redirectUrl = ($parsed['scheme'] ?? $request->getScheme()) . '://' . ($parsed['host'] ?? $request->getHost());
            if (isset($parsed['port']) && !in_array((int)$parsed['port'], [80, 443], true)) {
                $redirectUrl .= ':' . $parsed['port'];
            }
            $redirectUrl .= ($parsed['path'] ?? '/');
            if (!empty($queryParams)) {
                $redirectUrl .= '?' . http_build_query($queryParams);
            }

            return redirect()->to($redirectUrl)->withCookie(cookie()->forever('locale', $locale));
        }

        return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
    }

    public function home(): View
    {
        $categories = $this->toolRepository->getCategories();
        $tools = $this->toolRepository->getTools();

        return view('pages.home', [
            'categories' => $categories,
            'tools' => $tools,
        ]);
    }

    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');

        $categories = $this->toolRepository->getCategories();
        $tools = $this->toolRepository->getTools(
            categorySlug: is_string($categorySlug) ? $categorySlug : null,
            search: is_string($search) ? $search : null,
        );

        return view('pages.tools.index', [
            'categories' => $categories,
            'tools' => $tools,
        ]);
    }

    public function show(string $slug): View
    {
        $tool = $this->toolRepository->getToolBySlug($slug);
        if ( ! $tool) {
            throw new NotFoundHttpException("Tool [{$slug}] not found.");
        }

        $relatedTools = Tool::query()
            ->where('category_id', $tool->category_id)
            ->where('id', '!=', $tool->id)
            ->where('is_active', true)
            ->limit(5)
            ->get();

        return view('pages.tools.show', [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
        ]);
    }

    /**
     * Dynamic SEO XML Sitemap Generator (Senior Auto-Syncing).
     * Automatically includes all active Tools, Articles, Games, and Categories in real-time.
     */
    public function sitemap(): Response
    {
        $baseUrl = rtrim(url('/'), '/');
        $nowIso  = now()->toAtomString();

        $tools       = Tool::query()->where('is_active', true)->orderByDesc('updated_at')->get();
        $toolCats    = $this->toolRepository->getCategories();
        $articles    = \Domain\Article\Entities\Article::query()->where('status', 'published')->orderByDesc('updated_at')->get();
        $articleCats = \Domain\Article\Entities\ContentCategory::all();
        $games       = \Domain\Game\Entities\Game::query()->where('is_active', true)->orderByDesc('updated_at')->get();
        $gameCats    = \Domain\Game\Entities\GameCategory::query()->where('is_active', true)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?xml-stylesheet type="text/xsl" href="' . $baseUrl . '/sitemap.xsl"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        // 1. Homepage
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$baseUrl}</loc>\n";
        $xml .= "    <lastmod>{$nowIso}</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$baseUrl}?lang=vi\" />\n";
        $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$baseUrl}?lang=en\" />\n";
        $xml .= "  </url>\n";

        // 2. Hub Indexes
        $hubs = [
            ['path' => '/tools', 'priority' => '0.9', 'freq' => 'daily'],
            ['path' => '/articles', 'priority' => '0.9', 'freq' => 'daily'],
            ['path' => '/games', 'priority' => '0.9', 'freq' => 'daily'],
            ['path' => '/compare', 'priority' => '0.8', 'freq' => 'weekly'],
            ['path' => '/reviews', 'priority' => '0.8', 'freq' => 'weekly'],
        ];

        foreach ($hubs as $hub) {
            $loc = $baseUrl . $hub['path'];
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$nowIso}</lastmod>\n";
            $xml .= "    <changefreq>{$hub['freq']}</changefreq>\n";
            $xml .= "    <priority>{$hub['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        // 3. Tools Single Workspaces & Categories
        foreach ($toolCats as $cat) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/tools?category={$cat->slug}</loc>\n";
            $xml .= "    <lastmod>{$nowIso}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        foreach ($tools as $tool) {
            $toolUrl = "{$baseUrl}/tools/{$tool->slug}";
            $lastmod = $tool->updated_at ? $tool->updated_at->toAtomString() : $nowIso;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$toolUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$toolUrl}?lang=vi\" />\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$toolUrl}?lang=en\" />\n";
            $xml .= "  </url>\n";
        }

        // 4. Articles & Article Categories
        foreach ($articleCats as $cat) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/articles?category={$cat->slug}</loc>\n";
            $xml .= "    <lastmod>{$nowIso}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        foreach ($articles as $art) {
            $artUrl  = "{$baseUrl}/articles/{$art->slug}";
            $lastmod = $art->updated_at ? $art->updated_at->toAtomString() : $nowIso;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$artUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // 5. Games & Game Categories (All 220+ Games)
        foreach ($gameCats as $cat) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/games?category={$cat->slug}</loc>\n";
            $xml .= "    <lastmod>{$nowIso}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        foreach ($games as $game) {
            $gameUrl = "{$baseUrl}/games/{$game->slug}";
            $lastmod = $game->updated_at ? $game->updated_at->toAtomString() : $nowIso;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$gameUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
