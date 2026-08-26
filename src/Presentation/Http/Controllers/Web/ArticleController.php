<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Web;

use Domain\Article\Entities\Article;
use Domain\Article\Entities\ContentCategory;
use Domain\Hardware\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController
{
    /**
     * Display listing of articles, comparisons, reviews, and guides.
     */
    public function index(Request $request): View
    {
        $typeFilter = $request->query('type');
        $categorySlug = $request->query('category');
        $searchQuery = trim((string) $request->query('search', ''));

        $query = Article::query()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->latest('published_at');

        if (!empty($typeFilter)) {
            $query->where('type', $typeFilter);
        }

        if (!empty($categorySlug)) {
            $query->whereHas('category', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
        }

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery): void {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('excerpt', 'like', "%{$searchQuery}%");
            });
        }

        $articles = $query->paginate(12)->withQueryString();
        $categories = ContentCategory::query()->where('is_active', true)->orderBy('sort_order')->get();
        $featuredComparisons = Article::query()
            ->where('status', 'published')
            ->where('type', 'comparison')
            ->latest('view_count')
            ->take(4)
            ->get();

        return view('pages.articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'featuredComparisons' => $featuredComparisons,
            'currentType' => $typeFilter,
            'currentCategory' => $categorySlug,
        ]);
    }

    /**
     * Display a single detailed article / comparison review.
     */
    public function show(string $slug): View
    {
        /** @var Article $article */
        $article = Article::query()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count asynchronously
        $article->increment('view_count');

        // Extract Headings for Table of Contents (TOC)
        $toc = $this->extractTableOfContents($article->content_markdown);

        // Fetch related articles
        $relatedArticles = Article::query()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        // Optional linked hardware products
        $linkedProducts = Product::query()
            ->with(['brand', 'benchmarks'])
            ->where('is_active', true)
            ->latest('overall_score')
            ->take(2)
            ->get();

        // Convert markdown to rich HTML
        $htmlContent = \Illuminate\Support\Str::markdown($article->content_markdown ?? '');

        // Add IDs to h2, h3, h4 tags for Table of Contents anchor links
        $htmlContent = preg_replace_callback('/<h([2-4])>(.*?)<\/h\1>/i', function ($matches) {
            $tag = $matches[1];
            $title = strip_tags($matches[2]);
            $slug = \Illuminate\Support\Str::slug($title);
            return "<h{$tag} id=\"{$slug}\" class=\"article-heading\">{$matches[2]}</h{$tag}>";
        }, $htmlContent);

        // Wrap tables in responsive container
        $htmlContent = preg_replace('/<table(.*?)>/i', '<div class="table-responsive"><table$1>', $htmlContent);
        $htmlContent = preg_replace('/<\/table>/i', '</table></div>', $htmlContent);

        return view('pages.articles.show', [
            'article' => $article,
            'htmlContent' => $htmlContent,
            'toc' => $toc,
            'relatedArticles' => $relatedArticles,
            'linkedProducts' => $linkedProducts,
        ]);
    }

    /**
     * Parse markdown to extract H2/H3 for automatic Table of Contents.
     *
     * @return list<array{title: string, slug: string, level: int}>
     */
    private function extractTableOfContents(string $markdown): array
    {
        $lines = explode("\n", $markdown);
        $toc = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^(#{2,3})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $title = trim($matches[2]);
                $slug = \Illuminate\Support\Str::slug($title);
                $toc[] = [
                    'title' => $title,
                    'slug' => $slug,
                    'level' => $level,
                ];
            }
        }

        return $toc;
    }
}
