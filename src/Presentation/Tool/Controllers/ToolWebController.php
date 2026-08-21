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
        }

        return redirect()->back();
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
     * Dynamic SEO XML Sitemap Generator.
     */
    public function sitemap(): Response
    {
        $tools = Tool::query()->where('is_active', true)->orderByDesc('updated_at')->get();
        $categories = $this->toolRepository->getCategories();
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // 1. Homepage
        $xml .= '<url>';
        $xml .= "<loc>{$baseUrl}</loc>";
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$baseUrl}?lang=vi\" />";
        $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$baseUrl}?lang=en\" />";
        $xml .= '</url>';

        // 2. Tools Catalog
        $xml .= '<url>';
        $xml .= "<loc>{$baseUrl}/tools</loc>";
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$baseUrl}/tools?lang=vi\" />";
        $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$baseUrl}/tools?lang=en\" />";
        $xml .= '</url>';

        // 3. Category Filter Pages
        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= "<loc>{$baseUrl}/tools?category={$cat->slug}</loc>";
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // 4. Tool Workspace Single Pages
        foreach ($tools as $tool) {
            $toolUrl = "{$baseUrl}/tools/{$tool->slug}";
            $lastmod = $tool->updated_at ? $tool->updated_at->toAtomString() : now()->toAtomString();

            $xml .= '<url>';
            $xml .= "<loc>{$toolUrl}</loc>";
            $xml .= "<lastmod>{$lastmod}</lastmod>";
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$toolUrl}?lang=vi\" />";
            $xml .= "<xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$toolUrl}?lang=en\" />";
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
